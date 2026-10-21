import { readFile, writeFile, mkdir, rm } from "node:fs/promises";
import { render, routes, metadata, person } from "../.ssr/entry-server.js";

const template = await readFile("dist/index.html", "utf8");
const escape = (value) =>
  value
    .replaceAll("&", "&amp;")
    .replaceAll('"', "&quot;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;");
for (const path of [...routes, "/404"]) {
  const meta = metadata(path);
  const head = `<title>${escape(meta.title)}</title>
<meta name="description" content="${escape(meta.description)}" />
<meta name="robots" content="${meta.noindex ? "noindex,follow" : "index,follow"}" />
<link rel="canonical" href="${escape(meta.canonical)}" />
<meta property="og:title" content="${escape(meta.title)}" />
<meta property="og:description" content="${escape(meta.description)}" />
<meta property="og:url" content="${escape(meta.canonical)}" />
<meta property="og:type" content="website" />
<meta property="og:image" content="${person.siteUrl}/og-image.png" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="${escape(meta.title)}" />
<meta name="twitter:description" content="${escape(meta.description)}" />
<meta name="twitter:image" content="${person.siteUrl}/og-image.png" />`;
  const html = template
    .replace("<!--route-head-->", head)
    .replace("<!--app-html-->", await render(path));
  const target = path === "/" ? "dist/index.html" : `dist${path}.html`;
  await mkdir(target.slice(0, target.lastIndexOf("/")), { recursive: true });
  await writeFile(target, html);
}
await writeFile(
  "dist/sitemap.xml",
  `<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">${routes.map((path) => `<url><loc>${person.siteUrl}${path}</loc></url>`).join("")}</urlset>`,
);
await writeFile(
  "dist/robots.txt",
  `User-agent: *\nAllow: /\nSitemap: ${person.siteUrl}/sitemap.xml\n`,
);
await writeFile(
  "dist/routes.json",
  JSON.stringify([...routes, "/404"], null, 2),
);
await rm("dist/.vite", { recursive: true, force: true });
console.log(
  `Prerendered ${routes.length + 1} independent HTML documents. No server runtime required.`,
);
