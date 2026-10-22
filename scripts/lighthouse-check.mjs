import lighthouse from "lighthouse";
import { launch } from "chrome-launcher";
import { chromium } from "playwright";
import fs from "node:fs/promises";
const routes =
  process.argv.length > 2
    ? process.argv.slice(2)
    : JSON.parse(await fs.readFile("dist/routes.json", "utf8"));
const chrome = await launch({
  chromePath: chromium.executablePath(),
  chromeFlags: ["--headless", "--no-sandbox", "--disable-dev-shm-usage"],
});
const results = [];
const origin = process.env.PREVIEW_URL || "http://127.0.0.1:4173";
try {
  for (const path of routes) {
    const { lhr } = await lighthouse(`${origin}${path}`, {
      port: chrome.port,
      output: "json",
      logLevel: "error",
      onlyCategories: ["performance", "accessibility", "best-practices", "seo"],
    });
    const scores = Object.fromEntries(
      Object.entries(lhr.categories).map(([key, value]) => [
        key,
        Math.round(value.score * 100),
      ]),
    );
    const failures = Object.values(lhr.audits)
      .filter(
        (a) =>
          a.score !== null &&
          a.score < 1 &&
          a.scoreDisplayMode !== "informative",
      )
      .map((a) => ({
        id: a.id,
        title: a.title,
        score: a.score,
        description: a.description,
        details: a.details,
      }));
    const metrics = Object.fromEntries(
      [
        "first-contentful-paint",
        "largest-contentful-paint",
        "total-blocking-time",
        "cumulative-layout-shift",
        "speed-index",
      ].map((id) => [id, lhr.audits[id].numericValue]),
    );
    results.push({
      path,
      scores,
      metrics,
      warnings: lhr.runWarnings,
      failures,
    });
    await fs.mkdir("test-results", { recursive: true });
    await fs.writeFile(
      "test-results/lighthouse-progress.json",
      JSON.stringify(results, null, 2),
    );
    console.log(path, JSON.stringify(scores));
  }
  await fs.mkdir("test-results", { recursive: true });
  await fs.writeFile(
    "test-results/lighthouse.json",
    JSON.stringify(results, null, 2),
  );
  const low = results.filter((r) =>
    Object.values(r.scores).some((score) => score < 95),
  );
  console.log(
    low.length
      ? `${low.length} routes below 95; see test-results/lighthouse.json.`
      : "Every route scored 95+ in all four categories.",
  );
} finally {
  await chrome.kill();
}
