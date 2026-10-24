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
      ...(width < 700 ? { isMobile: true, hasTouch: true } : {}),
    });
    const page = await context.newPage();
    const errors = [];
    page.on("pageerror", (error) => errors.push(error.message));
    await page.goto(origin, { waitUntil: "networkidle" });
    const board = page.locator(".skills-motion");
    const front = page.locator('.skill-face[data-position="front"]');
    assert.equal(await board.getAttribute("data-running"), "false");
    await page.locator(".skill-stage").scrollIntoViewIfNeeded();
    await page.mouse.move(5, 5);
    await page.waitForFunction(
      () => document.querySelector(".skills-motion").dataset.active === "1",
    );
    assert.equal(await front.locator("h3").innerText(), "PHP");
    assert.equal(
      await page.locator('.skill-face[aria-hidden="false"]').count(),
      1,
    );
    await page
      .getByRole("button", { name: "Pause motion", exact: true })
      .click();
    const paused = await board.getAttribute("data-active");
    await page.waitForTimeout(3350);
    assert.equal(await board.getAttribute("data-active"), paused);
    const facing = await front.evaluate((el) => {
      const matrix = new DOMMatrixReadOnly(getComputedStyle(el).transform);
      return { xz: matrix.m13, zx: matrix.m31 };
    });
    assert.equal(facing.xz, 0);
    assert.equal(facing.zx, 0);
    const side = await page
      .locator('.skill-face[data-position="next"]')
      .evaluate(
        (el) => new DOMMatrixReadOnly(getComputedStyle(el).transform).m13,
      );
    assert(
      Math.abs(side) > 0.1,
      "The next card should be angled away from the reader",
    );
    await page.getByRole("button", { name: "Next skill", exact: true }).click();
    assert.equal(await front.locator("h3").innerText(), "Java");
    await page
      .getByRole("button", { name: "Previous skill", exact: true })
      .focus();
    await page.keyboard.press("Enter");
    assert.equal(await front.locator("h3").innerText(), "PHP");
    await page.locator(".skill-stage").focus();
    await page.keyboard.press("ArrowLeft");
    assert.equal(await front.locator("h3").innerText(), "Python");
    if (width < 700) {
      const bounds = await page.locator(".skill-stage").boundingBox();
      const x = bounds.x + bounds.width / 2,
        y = bounds.y + bounds.height / 2;
      const session = await context.newCDPSession(page);
      await session.send("Input.dispatchTouchEvent", {
        type: "touchStart",
        touchPoints: [{ x: x + 45, y }],
      });
      await session.send("Input.dispatchTouchEvent", {
        type: "touchMove",
        touchPoints: [{ x: x - 45, y }],
      });
      await session.send("Input.dispatchTouchEvent", {
        type: "touchEnd",
        touchPoints: [],
      });
      assert.equal(await front.locator("h3").innerText(), "PHP");
      await session.detach();
    }
    await page
      .getByRole("button", { name: "Resume motion", exact: true })
      .click();
    await page.mouse.move(5, 5);
    await page.waitForFunction(
      () => document.querySelector(".skills-motion").dataset.running === "true",
    );
    await page
      .getByRole("button", { name: "Pause motion", exact: true })
      .click();
    await page.getByRole("link", { name: "Explore all skills" }).click();
    await page.getByRole("heading", { name: "Tools of the trade." }).waitFor();
    await page.waitForFunction(
      () => document.querySelector("#skills").getBoundingClientRect().top < 160,
    );
    await page
      .getByRole("button", { name: "Pause motion", exact: true })
      .click();
    await page.mouse.move(5, 5);
    await page
      .locator(".skill-stage")
      .evaluate((el) =>
        el
          .getAnimations({ subtree: true })
          .forEach((animation) => animation.finish()),
      );
    await page.screenshot({ path: `test-results/skills-face-${width}.png` });
    await page.getByText("View all 27 skills", { exact: true }).click();
    assert.equal(await page.locator(".skills-inventory li").count(), 27);
    await page.emulateMedia({ reducedMotion: "reduce" });
    await page
      .getByRole("button", { name: "Motion off", exact: true })
      .waitFor();
    assert.equal(
      await page
        .locator(".skill-stage")
        .evaluate((el) => el.getAnimations({ subtree: true }).length),
      0,
    );
    await page.getByRole("button", { name: "Next skill", exact: true }).click();
    assert.equal(await front.locator("h3").innerText(), "PHP");
    assert.equal(
      await page.evaluate(
        () => document.documentElement.scrollWidth > innerWidth,
      ),
      false,
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
      `${width}px: one skill faces front, side cards turn away, automatic stepping/pause, manual/keyboard navigation, mobile swipes, all 27 skills and reduced motion pass without overflow or accessibility violations.`,
    );
  }
  const context = await browser.newContext({ javaScriptEnabled: false });
  const page = await context.newPage();
  await page.goto(origin + "/about#skills");
  await page.getByText("View all 27 skills", { exact: true }).click();
  assert.equal(await page.locator(".skills-inventory li").count(), 27);
  assert(
    await page
      .getByRole("heading", { name: "Python", exact: true })
      .isVisible(),
  );
  await context.close();
  console.log(
    "The first skill and the complete native disclosure remain available without JavaScript.",
  );
} finally {
  await browser.close();
}
