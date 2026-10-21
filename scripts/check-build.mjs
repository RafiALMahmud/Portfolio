import assert from "node:assert/strict";
import { readFile, readdir, stat } from "node:fs/promises";
import { gzipSync } from "node:zlib";
const routes = JSON.parse(await readFile("dist/routes.json", "utf8"));
const titles = new Set(),
  descriptions = new Set();
for (const path of routes) {
  const html = await readFile(
    path === "/" ? "dist/index.html" : `dist${path}.html`,
    "utf8",
  );
  const title = html.match(/<title>(.*?)<\/title>/)?.[1];
  const description = html.match(
    /<meta name="description" content="(.*?)"/,
  )?.[1];
  assert(title && !titles.has(title), `Missing or duplicate title: ${path}`);
  titles.add(title);
  assert(
    description && !descriptions.has(description),
    `Missing or duplicate description: ${path}`,
  );
  descriptions.add(description);
  for (const needle of [
    'rel="canonical"',
    'property="og:title"',
    'name="twitter:card"',
    "<h1",
    'id="main"',
    "Skip to content",
  ])
    assert(html.includes(needle), `${path} missing ${needle}`);
  assert(!html.includes("<!--app-html-->"), `Empty prerender: ${path}`);
  assert(
    (html.match(/<h1[ >]/g) || []).length === 1,
    `Incorrect heading count: ${path}`,
  );
  for (const match of html.matchAll(/href="(\/(?!\/)[^"?#]*)/g)) {
    const link = match[1];
    if (!routes.includes(link))
      assert(
        await stat(`dist${link}`).catch(() => null),
        `Broken local link ${link} on ${path}`,
      );
  }
}
let bytes = 0;
async function walk(dir) {
  for (const f of await readdir(dir, { withFileTypes: true })) {
    const path = `${dir}/${f.name}`;
    if (f.isDirectory()) await walk(path);
    else {
      const size = (await stat(path)).size;
      bytes += size;
      if (path.endsWith(".js"))
        assert(
          gzipSync(await readFile(path)).length < 100 * 1024,
          `Oversized JS chunk: ${path}`,
        );
    }
  }
}
await walk("dist");
assert(bytes < 50 * 1024 * 1024, "Deployed site exceeds 50 MB");
assert(
  (await readFile("dist/cv.pdf")).subarray(0, 4).toString() === "%PDF",
  "Invalid CV",
);
console.log(
  `Verified ${routes.length} routes: static content, unique metadata, local links, CV and JS budgets. Total ${(bytes / 1024 / 1024).toFixed(2)} MB.`,
);
