import { chromium } from "playwright";
import { AxeBuilder } from "@axe-core/playwright";
import fs from "node:fs/promises";
import assert from "node:assert/strict";

const origin = process.env.PREVIEW_URL || "http://127.0.0.1:4173";
const browser = await chromium.launch({ headless: true });
const routes = JSON.parse(await fs.readFile("dist/routes.json", "utf8"));
const results = [];
try {
  for (const width of [1440, 390]) {
    const context = await browser.newContext({
      viewport: { width, height: 900 },
      reducedMotion: "reduce",
    });
    const page = await context.newPage();
    const errors = [];
    page.on("pageerror", (e) => errors.push(e.message));
    page.on("console", (m) => {
      if (m.type() === "error") errors.push(m.text());
    });
    for (const path of routes) {
      const response = await page.goto(origin + path, {
        waitUntil: "networkidle",
      });
      await page.locator("main h1").waitFor();
      const a11y = await new AxeBuilder({ page })
        .withTags(["wcag2a", "wcag2aa", "wcag21a", "wcag21aa"])
        .analyze();
      const overflow = await page.evaluate(
        () => document.documentElement.scrollWidth > innerWidth,
      );
      const result = {
        width,
        path,
        status: response.status(),
        overflow,
        errors: [...errors],
        violations: a11y.violations.map((x) => ({
          id: x.id,
          impact: x.impact,
          nodes: x.nodes.map((n) => ({
            target: n.target,
            summary: n.failureSummary,
          })),
        })),
      };
      results.push(result);
      errors.length = 0;
      console.log(
        `${width}px ${path}: ${result.violations.length} accessibility violations${overflow ? " OVERFLOW" : ""}${result.errors.length ? " ERRORS" : ""}`,
      );
    }
    await page.goto(origin + "/projects");
    await page.getByRole("button", { name: "PHP", exact: true }).click();
    assert.match(await page.getByRole("status").innerText(), /4 FILES FOUND/);
    assert.equal(await page.locator(".project-card").count(), 4);
    if (width === 390) {
      await page.getByRole("button", { name: "Open navigation" }).click();
      await page
        .getByRole("navigation", { name: "Main navigation" })
        .getByRole("link", { name: "Contact" })
        .click();
      await page.waitForURL("**/contact");
      await page.getByRole("heading", { name: "Open a new case." }).waitFor();
      assert.equal(
        await page
          .getByRole("button", { name: "Open navigation" })
          .getAttribute("aria-expanded"),
        "false",
      );
    } else {
      await page
        .getByRole("navigation", { name: "Main navigation" })
        .getByRole("link", { name: "About", exact: true })
        .click();
      await page.getByRole("heading", { name: "Behind the files." }).waitFor();
      assert.match(await page.title(), /person behind the files/);
      assert.equal(
        await page
          .locator("main")
          .evaluate((el) => document.activeElement === el),
        true,
      );
    }
    await page.goto(origin + "/unresolved-case");
    await page.getByRole("heading", { name: "File not found." }).waitFor();
    assert.match(
      await page.locator('meta[name="robots"]').getAttribute("content"),
      /noindex/,
    );
    assert.equal(errors.length, 0, errors.join("\n"));
    await context.close();
  }
  await fs.mkdir("test-results", { recursive: true });
  await fs.writeFile(
    "test-results/browser-audit.json",
    JSON.stringify(results, null, 2),
  );
  const failures = results.filter(
    (x) =>
      x.overflow || x.errors.length || x.violations.length || x.status !== 200,
  );
  assert.equal(failures.length, 0, JSON.stringify(failures, null, 2));
  console.log(
    `Passed ${results.length} route/viewport checks plus filters, navigation, focus and unknown routes.`,
  );
} finally {
  await browser.close();
}
