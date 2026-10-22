import { chromium } from "playwright";
import { AxeBuilder } from "@axe-core/playwright";
import assert from "node:assert/strict";
import fs from "node:fs/promises";

const origin = process.env.PREVIEW_URL || "http://127.0.0.1:4173";
const browser = await chromium.launch({ headless: true });
const name = "Everybody’s code lies. I read the logs.";
try {
  await fs.mkdir("test-results", { recursive: true });
  for (const width of [1440, 390, 320]) {
    const context = await browser.newContext({
      viewport: { width, height: 1000 },
      reducedMotion: "no-preference",
    });
    const page = await context.newPage();
    const errors = [];
    page.on("pageerror", (error) => errors.push(error.message));
    await page.goto(origin, { waitUntil: "networkidle" });
    const heading = page.getByRole("heading", { name, exact: true });
    assert.equal(await heading.count(), 1);
    const bounds = await heading.boundingBox();
    const count = await heading.evaluate((el) => {
      const animations = el.getAnimations({ subtree: true });
      for (const animation of animations) {
        animation.pause();
        animation.currentTime = 600;
      }
      return animations.length;
    });
    assert(count > 50, "Each letter and its grain should animate");
    assert.equal(
      await heading
        .locator(".forming-letter")
        .last()
        .evaluate((el) => getComputedStyle(el).opacity),
      "0",
    );
    assert.equal(
      await page.evaluate(
        () => document.documentElement.scrollWidth > innerWidth,
      ),
      false,
    );
    await page.screenshot({
      path: `test-results/headline-forming-${width}.png`,
    });
    await heading.evaluate((el) => {
      for (const animation of el.getAnimations({ subtree: true }))
        animation.currentTime = 2590;
    });
    const complete = await heading.boundingBox();
    assert.equal(
      complete.height,
      bounds.height,
      "Text formation must reserve its final layout",
    );
    assert.equal(complete.width, bounds.width);
    assert.equal(
      await heading
        .locator(".forming-letter")
        .last()
        .evaluate((el) => getComputedStyle(el).opacity),
      "1",
    );
    await page.screenshot({
      path: `test-results/headline-complete-${width}.png`,
    });
    await page.waitForFunction(
      () =>
        document.querySelector(".forming-headline")?.dataset.forming ===
        "false",
    );
    assert.equal(
      await heading.evaluate(
        (el) => el.getAnimations({ subtree: true }).length,
      ),
      0,
    );
    await page.reload({ waitUntil: "networkidle" });
    await page.emulateMedia({ reducedMotion: "reduce" });
    await page.waitForFunction(
      () =>
        document.querySelector(".forming-headline")?.dataset.forming ===
        "false",
    );
    assert.equal(
      await heading.evaluate(
        (el) => el.getAnimations({ subtree: true }).length,
      ),
      0,
    );
    const audit = await new AxeBuilder({ page })
      .withTags(["wcag2a", "wcag2aa", "wcag21a", "wcag21aa"])
      .analyze();
    assert.deepEqual(
      audit.violations.map((v) => ({
        id: v.id,
        nodes: v.nodes.map((n) => n.target),
      })),
      [],
    );
    assert.deepEqual(errors, []);
    await context.close();
    console.log(
      `${width}px: text forms, finishes without layout shift, respects reduced motion and passes accessibility checks.`,
    );
  }
  const context = await browser.newContext({ javaScriptEnabled: false });
  const page = await context.newPage();
  await page.goto(origin);
  const heading = page.getByRole("heading", { name, exact: true });
  assert(await heading.isVisible());
  assert.equal(
    await heading
      .locator(".forming-letter")
      .last()
      .evaluate((el) => getComputedStyle(el).opacity),
    "1",
  );
  await context.close();
  console.log(
    "Static headline remains visible and accessible without JavaScript.",
  );
} finally {
  await browser.close();
}
