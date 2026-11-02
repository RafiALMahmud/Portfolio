import assert from "node:assert/strict";
import fs from "node:fs/promises";
import path from "node:path";
import { chromium } from "playwright";
import { AxeBuilder } from "@axe-core/playwright";
import {
  isCodeFile,
  humanCommits,
  activityScore,
  eligibleRepository,
} from "./github-sync-lib.mjs";
import { syncGitHub } from "./sync-github.mjs";

const origin = process.env.PREVIEW_URL || "http://127.0.0.1:4173";
const feedUrl =
  "https://raw.githubusercontent.com/RafiALMahmud/Portfolio/main/public/github-projects.json";
const seed = JSON.parse(
  await fs.readFile("public/github-projects.json", "utf8"),
);
const root = process.cwd();
for (const file of [
  "app.py",
  "resources/view.blade.php",
  "src/a.tsx",
  "query.sql",
  "analysis.ipynb",
  "robot.ino",
])
  assert(isCodeFile(file), file);
for (const file of [
  "vendor/a.php",
  "app/node_modules/a.js",
  ".venv/a.py",
  "dist/a.js",
  "a.min.js",
  "src/a.generated.ts",
  "src/a.d.ts",
  "photo.jpg",
  "package-lock.json",
])
  assert(!isCodeFile(file), file);
const repo = (name) => ({ name, owner: { login: "RafiALMahmud" } });
assert(!eligibleRepository(repo("CSE")));
assert(!eligibleRepository(repo("Bracu-Labmid")));
assert(eligibleRepository(repo("Cse330")));
assert(!eligibleRepository({ ...repo("secret"), private: true }));
const commit = (date, extra = {}) => ({
  parents: [{}],
  author: { login: "RafiALMahmud" },
  commit: { author: { date } },
  ...extra,
});
const now = new Date("2026-11-02T12:00:00Z");
const recent = humanCommits([
  commit(now.toISOString()),
  commit("2026-10-26T12:00:00Z"),
  commit(now.toISOString(), { author: { type: "Bot" } }),
  commit(now.toISOString(), { parents: [{}, {}] }),
]);
assert.equal(recent.length, 2);
assert.equal(activityScore(recent, now.getTime()), 1.5);

// Exercise real sync pagination, truncated-tree recovery and atomic failure handling.
await fs.mkdir("test-results", { recursive: true });
const scratch = await fs.mkdtemp(
  path.join(root, "test-results", "github-sync-"),
);
const originalFetch = globalThis.fetch;
try {
  process.chdir(scratch);
  let fail = false;
  let commitPage2 = false;
  const firstPage = Array.from({ length: 100 }, () =>
    commit(now.toISOString()),
  );
  globalThis.fetch = async (url) => {
    const u = new URL(url);
    let body;
    if (u.pathname.includes("/users/"))
      body = [
        {
          ...repo("fixture"),
          size: 1,
          default_branch: "main",
          language: "Python",
        },
      ];
    else if (fail) return new Response("unavailable", { status: 403 });
    else if (u.pathname.endsWith("/commits")) {
      if (!u.searchParams.has("since")) body = [commit(now.toISOString())];
      else if (u.searchParams.get("page") === "1") body = firstPage;
      else {
        commitPage2 = true;
        body = [commit(now.toISOString())];
      }
    } else if (u.searchParams.has("recursive"))
      body = { truncated: true, tree: [] };
    else if (u.pathname.endsWith("/main"))
      body = {
        tree: [
          { type: "blob", path: "a.py" },
          { type: "tree", path: "src", sha: "child" },
        ],
      };
    else
      body = {
        tree: [
          { type: "blob", path: "b.ts" },
          { type: "blob", path: "photo.png" },
        ],
      };
    return Response.json(body);
  };
  const output = await syncGitHub({ token: "fixture-token", now });
  assert(commitPage2);
  assert.equal(output.repositories[0].codeFiles, 2);
  assert.equal(output.repositories[0].recentCommits, 101);
  const before = await fs.readFile("public/github-projects.json", "utf8");
  fail = true;
  await assert.rejects(
    () => syncGitHub({ token: "fixture-token", now }),
    /GitHub 403/,
  );
  assert.equal(
    await fs.readFile("public/github-projects.json", "utf8"),
    before,
  );
} finally {
  globalThis.fetch = originalFetch;
  process.chdir(root);
}
console.log(
  "Sync logic: source exclusions, 30-day decay, bots/merges, pagination, truncated trees and last-good feed pass.",
);

const browser = await chromium.launch({ headless: true });
try {
  for (const width of [1440, 390, 320]) {
    const context = await browser.newContext({
      viewport: { width, height: 900 },
      reducedMotion: "no-preference",
    });
    const page = await context.newPage();
    const errors = [];
    page.on("pageerror", (e) => errors.push(e.message));
    // Fresh data contains a repository that did not exist at build time.
    const fixture = {
      ...seed,
      syncedAt: "2099-01-01T00:00:00Z",
      repositories: [
        ...seed.repositories,
        {
          name: "new-project",
          description: "Newly discovered coding project.",
          language: "TypeScript",
          topics: [],
          codeFiles: 4,
          recentCommits: 80,
          activityScore: 80,
          lastCommitAt: now.toISOString(),
        },
        {
          name: "large-source-tree",
          description: "A larger source tree.",
          language: "Python",
          topics: [],
          codeFiles: 99999,
          recentCommits: 0,
          activityScore: 0,
          lastCommitAt: now.toISOString(),
        },
      ],
    };
    await context.route(feedUrl, (route) => route.fulfill({ json: fixture }));
    await page.goto(origin + "/projects", { waitUntil: "networkidle" });
    const cards = page.locator(".project-card");
    await page
      .getByRole("link", { name: "new-project", exact: false })
      .first()
      .waitFor();
    assert.match(await cards.first().innerText(), /new-project/);
    assert.equal(
      await page
        .locator(
          'a[href="/projects/github/CSE"], a[href="/projects/github/Bracu-Labmid"]',
        )
        .count(),
      0,
    );
    assert.equal(
      await page.locator('a.card-file-link[href="/projects/wase"]').count(),
      1,
    );
    await cards.first().scrollIntoViewIfNeeded();
    await page.waitForFunction(
      () =>
        Number(
          getComputedStyle(document.querySelector(".project-card")).opacity,
        ) > 0.9,
    );
    const liveCount = await cards.count();
    await page.getByLabel("SORT FILES").selectOption("files");
    assert.match(await cards.first().innerText(), /large-source-tree/);
    const fileCounts = await cards.evaluateAll((nodes) =>
      nodes.map((n) =>
        Number(
          (
            n.querySelector(".project-activity span:last-child")?.textContent ||
            "0"
          ).replace(/[^\d]/g, ""),
        ),
      ),
    );
    assert.deepEqual(
      fileCounts,
      [...fileCounts].sort((a, b) => b - a),
    );
    await page.getByRole("button", { name: "Python", exact: true }).click();
    assert((await cards.count()) < liveCount);
    assert.match(await cards.first().innerText(), /large-source-tree/);
    await page.getByRole("button", { name: "All", exact: true }).click();
    assert.equal(await cards.count(), liveCount);
    const audit = await new AxeBuilder({ page })
      .withTags(["wcag2a", "wcag2aa", "wcag21aa"])
      .analyze();
    assert.deepEqual(
      audit.violations.map((v) => ({
        id: v.id,
        nodes: v.nodes.map((n) => n.target),
      })),
      [],
    );
    assert.equal(
      await page.evaluate(
        () => document.documentElement.scrollWidth > innerWidth,
      ),
      false,
    );
    await page.screenshot({
      path: `test-results/github-projects-${width}.png`,
      fullPage: true,
    });
    await page
      .getByRole("link", { name: "new-project", exact: false })
      .first()
      .click({ position: { x: 6, y: 6 } });
    await page.waitForURL("**/projects/github/new-project");
    await page
      .getByRole("heading", { name: "new-project", exact: true })
      .waitFor();
    await page.waitForFunction(() => document.title.includes("new-project"));
    assert.match(await page.title(), /new-project/);
    assert.equal(
      await page
        .getByRole("link", { name: "View source on GitHub" })
        .getAttribute("href"),
      "https://github.com/RafiALMahmud/new-project",
    );
    await page.reload({ waitUntil: "networkidle" });
    await page
      .getByRole("heading", { name: "new-project", exact: true })
      .waitFor();
    await page.goto(origin, { waitUntil: "networkidle" });
    assert.match(
      await page.locator(".project-card").first().innerText(),
      /new-project/,
    );
    assert.equal(await page.locator(".project-card").count(), 3);
    assert.deepEqual(errors, []);
    await context.close();

    // Malformed or unavailable remote data must leave the complete built-in archive.
    for (const response of [
      { status: 503, body: "unavailable" },
      { json: { version: 1, repositories: [{ name: "javascript:bad" }] } },
    ]) {
      const fallback = await browser.newContext({
        viewport: { width, height: 900 },
        reducedMotion: "reduce",
      });
      await fallback.route(feedUrl, (route) => route.fulfill(response));
      const p = await fallback.newPage();
      await p.goto(origin + "/projects", { waitUntil: "networkidle" });
      assert.equal(
        await p.locator(".project-card").count(),
        seed.repositories.length + 2,
      );
      await p.goto(origin + "/projects/github/not-a-real-project", {
        waitUntil: "networkidle",
      });
      await p
        .getByRole("heading", { name: "File not found.", exact: true })
        .waitFor();
      await fallback.close();
    }
    console.log(
      `${width}px: new-project discovery, both sort orders, filters, fresh deep links/reloads, home ranking, visible cards, accessibility and offline fallback pass.`,
    );
  }
  const noJS = await browser.newContext({ javaScriptEnabled: false });
  const p = await noJS.newPage();
  await p.goto(origin + "/projects");
  assert.equal(
    await p.locator(".project-card").count(),
    seed.repositories.length + 2,
  );
  await noJS.close();
} finally {
  await browser.close();
}
