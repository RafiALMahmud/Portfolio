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
    const word = page.locator(".skill-word");
    const name = () => word.locator("h3").getAttribute("aria-label");
    assert.equal(
      await page
        .locator(".skills-motion-toggle, .skill-step, .skill-controls")
        .count(),
      0,
    );
    assert.equal(await board.getAttribute("data-running"), "false");
    await page.locator(".skill-stage").scrollIntoViewIfNeeded();
    await page.mouse.move(5, 5);
    await page.waitForFunction(
      () => document.querySelector(".skills-motion").dataset.active === "1",
    );
    assert.equal(await name(), "PHP");
    assert.equal(await page.locator(".skill-word").count(), 1);
    assert.equal(await word.locator(".skill-letter path").count(), 3);
    const stages = await word.evaluate((el) => {
      const effects = el.getAnimations({ subtree: true });
      const master = effects.find((effect) => effect.effect.target === el);
      const paths = el.querySelectorAll(".skill-letter path");
      const seek = (time) => {
        for (const effect of effects) {
          effect.pause();
          effect.currentTime = time;
        }
      };
      seek(0);
      const initial = getComputedStyle(paths[0]);
      const empty = {
        fill: initial.fillOpacity,
        dash: parseFloat(initial.strokeDashoffset),
      };
      seek(350);
      const drawing = {
        fill: getComputedStyle(paths[0]).fillOpacity,
        dash: parseFloat(getComputedStyle(paths[0]).strokeDashoffset),
        laterDash: parseFloat(getComputedStyle(paths[2]).strokeDashoffset),
      };
      seek(1500);
      const held = Array.from(paths).map(
        (path) => getComputedStyle(path).fillOpacity,
      );
      seek(Number(master.effect.getTiming().duration) - 100);
      const vanished = getComputedStyle(el).opacity;
      return { empty, drawing, held, vanished };
    });
    assert.deepEqual(stages.empty, { fill: "0", dash: 1 });
    assert.equal(stages.drawing.fill, "0");
    assert(
      stages.drawing.dash > 0 && stages.drawing.dash < 1,
      "The first letter outline must be partially drawn",
    );
    assert(
      stages.drawing.laterDash > stages.drawing.dash,
      "Later letters must follow the first letter",
    );
    assert.deepEqual(stages.held, ["1", "1", "1"]);
    assert.equal(stages.vanished, "0");
    await word.evaluate((el) => {
      for (const effect of el.getAnimations({ subtree: true }))
        effect.currentTime = 350;
    });
    await page.screenshot({ path: `test-results/skills-drawing-${width}.png` });
    await word.evaluate((el) =>
      el
        .getAnimations()
        .find((effect) => effect.effect.target === el)
        .finish(),
    );
    await page.waitForFunction(
      () => document.querySelector(".skills-motion").dataset.active === "2",
    );
    assert.equal(await name(), "Java");
    await page.getByText("View all 27 skills", { exact: true }).click();
    assert.equal(
      await word.evaluate((el) => el.getAnimations({ subtree: true }).length),
      0,
    );
    const current = await board.getAttribute("data-active");
    await page.waitForTimeout(3350);
    assert.equal(await board.getAttribute("data-active"), current);
    assert.equal(await page.locator(".skills-inventory li").count(), 27);
    await page.locator(".skill-stage").focus();
    await page.keyboard.press("ArrowRight");
    assert.equal(await name(), "JavaScript / TypeScript");
    await page.keyboard.press("ArrowLeft");
    assert.equal(await name(), "Java");
    await page.locator(".skill-stage").focus();
    await page.keyboard.press("ArrowLeft");
    assert.equal(await name(), "PHP");
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
      assert.equal(await name(), "Java");
      await session.detach();
    }
    await page.getByRole("link", { name: "Explore all skills" }).click();
    await page.getByRole("heading", { name: "Tools of the trade." }).waitFor();
    await page.waitForFunction(
      () => document.querySelector("#skills").getBoundingClientRect().top < 160,
    );
    await page.getByText("View all 27 skills", { exact: true }).click();
    await page.emulateMedia({ reducedMotion: "reduce" });
    assert.equal(
      await page
        .locator(".skill-stage")
        .evaluate((el) => el.getAnimations({ subtree: true }).length),
      0,
    );
    await page.locator(".skill-stage").focus();
    await page.keyboard.press("ArrowRight");
    assert.equal(await name(), "PHP");
    assert.equal(
      await word
        .locator(".skill-letter path")
        .first()
        .evaluate((el) => getComputedStyle(el).fillOpacity),
      "1",
    );
    for (let index = 0; index < 27; index++) {
      const label = await name();
      assert.equal(
        await word.locator(".skill-letter path").count(),
        label.replaceAll(" ", "").length,
      );
      assert.equal(
        await page.evaluate(
          () => document.documentElement.scrollWidth > innerWidth,
        ),
        false,
      );
      await page.keyboard.press("ArrowRight");
    }
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
      `${width}px: no motion or arrow buttons, real glyph outlines build in order and fill, the word vanishes, the full list stops motion, and keyboard/touch/reduced-motion/accessibility checks pass.`,
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
  assert.equal(
    await page
      .locator(".skill-letter path")
      .first()
      .evaluate((el) => getComputedStyle(el).fillOpacity),
    "1",
  );
  await context.close();
  console.log(
    "Fully formed letters and the native skill inventory work without JavaScript.",
  );
} finally {
  await browser.close();
}
