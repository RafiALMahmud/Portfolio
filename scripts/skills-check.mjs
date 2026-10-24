import { chromium } from "playwright";
import { AxeBuilder } from "@axe-core/playwright";
import assert from "node:assert/strict";
import fs from "node:fs/promises";

const origin = process.env.PREVIEW_URL || "http://127.0.0.1:4173";
const browser = await chromium.launch({ headless: true });
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
    const board = page.locator(".skills-motion");
    assert.equal(
      await board.getAttribute("data-running"),
      "false",
      "Offscreen skills should be idle",
    );
    await board.scrollIntoViewIfNeeded();
    await page.waitForFunction(
      () => document.querySelector(".skills-motion").dataset.running === "true",
    );
    const tape = page.locator(".skill-tape").first();
    const first = await tape.evaluate((el) => getComputedStyle(el).transform);
    await page.waitForFunction(
      (first) =>
        getComputedStyle(document.querySelector(".skill-tape")).transform !==
        first,
      first,
    );
    await page
      .getByRole("button", { name: "Pause motion", exact: true })
      .click();
    assert.equal(
      await tape.evaluate((el) => getComputedStyle(el).animationPlayState),
      "paused",
    );
    const paused = await tape.evaluate((el) => getComputedStyle(el).transform);
    await page.evaluate(
      () =>
        new Promise((resolve) =>
          requestAnimationFrame(() => requestAnimationFrame(resolve)),
        ),
    );
    assert.equal(
      await tape.evaluate((el) => getComputedStyle(el).transform),
      paused,
    );
    await page
      .getByRole("button", { name: "Resume motion", exact: true })
      .focus();
    await page.keyboard.press("Enter");
    assert.equal(
      await tape.evaluate((el) => getComputedStyle(el).animationPlayState),
      "running",
    );
    await page.locator(".skill-lane-window").first().focus();
    assert.equal(
      await tape.evaluate((el) => getComputedStyle(el).animationPlayState),
      "paused",
      "Keyboard inspection should pause the row",
    );
    await page.getByRole("link", { name: "Explore all skills" }).click();
    await page.getByRole("heading", { name: "Tools of the trade." }).waitFor();
    await page.waitForFunction(
      () => document.querySelector("#skills").getBoundingClientRect().top < 160,
    );
    assert.equal(await page.locator(".skill-lane").count(), 6);
    const canonical = page.locator(
      '.skill-tape-list:not([aria-hidden="true"])',
    );
    assert.equal(await canonical.locator("li").count(), 27);
    assert.equal(
      await page.evaluate(
        () => document.documentElement.scrollWidth > innerWidth,
      ),
      false,
    );
    await page.screenshot({ path: `test-results/skills-motion-${width}.png` });
    await page.emulateMedia({ reducedMotion: "reduce" });
    await page.waitForFunction(
      () => document.querySelector(".skills-motion").dataset.ready === "false",
    );
    assert.equal(
      await page
        .locator(".skills-motion")
        .evaluate((el) => el.getAnimations({ subtree: true }).length),
      0,
    );
    assert.equal(
      await page
        .locator(".skill-tape")
        .first()
        .evaluate((el) => getComputedStyle(el).width),
      await page
        .locator(".skill-lane-window")
        .first()
        .evaluate((el) => getComputedStyle(el).width),
    );
    assert(await page.getByRole("button", { name: "Motion off" }).isDisabled());
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
    assert.equal(
      await page.evaluate(
        () => document.documentElement.scrollWidth > innerWidth,
      ),
      false,
    );
    assert.deepEqual(errors, []);
    await context.close();
    console.log(
      `${width}px: skills move in view, pause/resume by keyboard, pause for inspection, link to all 27 skills, and respect reduced motion with no overflow or accessibility violations.`,
    );
  }
  const context = await browser.newContext({ javaScriptEnabled: false });
  const page = await context.newPage();
  await page.goto(origin + "/about#skills");
  assert.equal(
    await page.locator('.skill-tape-list:not([aria-hidden="true"]) li').count(),
    27,
  );
  assert.equal(
    await page
      .locator('.skill-tape-list[aria-hidden="true"]')
      .first()
      .evaluate((el) => getComputedStyle(el).display),
    "none",
  );
  assert.equal(
    await page
      .locator(".skill-lane-window")
      .first()
      .evaluate((el) => getComputedStyle(el).overflowX),
    "auto",
  );
  await context.close();
  console.log(
    "All skills remain available through native scrolling without JavaScript.",
  );
} finally {
  await browser.close();
}
