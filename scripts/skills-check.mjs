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
    const front = page.locator(".skill-word");
    assert.equal(await board.getAttribute("data-running"), "false");
    await page.locator(".skill-stage").scrollIntoViewIfNeeded();
    await page.mouse.move(5, 5);
    await page.waitForFunction(
      () => document.querySelector(".skills-motion").dataset.active === "1",
    );
    assert.equal(await front.locator("h3").innerText(), "PHP");
    assert.equal(await page.locator(".skill-word").count(), 1);
    await page
      .getByRole("button", { name: "Pause motion", exact: true })
      .click();
    const paused = await board.getAttribute("data-active");
    await page.waitForTimeout(3350);
    assert.equal(await board.getAttribute("data-active"), paused);
    assert.equal(
      await page.locator(".skill-face, .skill-deck, .skill-emblem").count(),
      0,
    );
    const style = await front.evaluate((el) => ({
      opacity: getComputedStyle(el).opacity,
      border: getComputedStyle(el).borderWidth,
      background: getComputedStyle(el).backgroundColor,
      shadow: getComputedStyle(el).boxShadow,
    }));
    assert.equal(style.opacity, "1");
    assert.equal(style.border, "0px");
    assert.equal(style.background, "rgba(0, 0, 0, 0)");
    assert.equal(style.shadow, "none");
    await page
      .getByRole("button", { name: "Resume motion", exact: true })
      .click();
    const sequence = await front.evaluate((el) => {
      const effect = el.getAnimations()[0];
      effect.pause();
      effect.currentTime = 1200;
      const held = getComputedStyle(el).opacity;
      effect.currentTime = 3000;
      const vanished = getComputedStyle(el).opacity;
      effect.currentTime = 3190;
      const gap = getComputedStyle(el).opacity;
      return { held, vanished, gap };
    });
    assert.deepEqual(sequence, { held: "1", vanished: "0", gap: "0" });
    await page
      .getByRole("button", { name: "Pause motion", exact: true })
      .click();
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
    await page.screenshot({ path: `test-results/skills-words-${width}.png` });
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
      `${width}px: one plain skill appears, holds and completely vanishes before the next; automatic stepping/pause, manual/keyboard navigation, mobile swipes, all 27 skills and reduced motion pass without overflow or accessibility violations.`,
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
