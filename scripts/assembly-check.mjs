import { chromium } from "playwright";
import assert from "node:assert/strict";
import fs from "node:fs/promises";

const origin = process.env.PREVIEW_URL || "http://127.0.0.1:4173";
const browser = await chromium.launch({ headless: true });
const errors = [];
try {
  await fs.mkdir("test-results", { recursive: true });
  const context = await browser.newContext({
    viewport: { width: 1440, height: 1000 },
    reducedMotion: "no-preference",
  });
  const page = await context.newPage();
  page.on("pageerror", (error) => errors.push(error.message));
  await page.goto(origin, { waitUntil: "networkidle" });
  const stage = page.locator(".dossier-stage");
  await page.waitForFunction(
    () =>
      document.querySelector(".dossier-stage")?.dataset.assembly === "building",
  );
  // Seek the real CSS timeline to check ordering without timing-sensitive sleeps.
  const seek = (time) =>
    stage.evaluate((el, time) => {
      const animations = el
        .getAnimations({ subtree: true })
        .filter((a) => a.animationName?.startsWith("file-"));
      for (const animation of animations) {
        animation.pause();
        animation.currentTime = time;
      }
      return animations.length;
    }, time);
  assert((await seek(1500)) >= 16);
  assert.equal(
    await page
      .locator(".dossier-stamp")
      .evaluate((el) => getComputedStyle(el).opacity),
    "0",
  );
  const drawing = await page
    .locator(".dossier-frame")
    .evaluate((el) => parseFloat(getComputedStyle(el).strokeDashoffset));
  assert(drawing > 0 && drawing < 1, "The profile frame should be drawing in");
  await page.screenshot({ path: "test-results/assembly-drawing.png" });
  await seek(3950);
  for (const selector of [
    ".dossier-stamp",
    ".paperclip",
    ".desk-loupe",
    ".dossier-facts > div",
  ]) {
    assert.equal(
      await page
        .locator(selector)
        .first()
        .evaluate((el) => getComputedStyle(el).opacity),
      "1",
    );
  }
  await page.screenshot({ path: "test-results/assembly-complete.png" });
  await page.waitForFunction(
    () =>
      document.querySelector(".dossier-stage")?.dataset.assembly === "complete",
  );
  const replay = page.getByRole("button", {
    name: "Replay assembly of the case file",
    exact: true,
  });
  await replay.focus();
  await page.keyboard.press("Enter");
  await page.waitForFunction(
    () =>
      document.querySelector(".dossier-stage")?.dataset.assembly === "building",
  );
  assert.equal(
    await page
      .locator(".dossier-stamp")
      .evaluate((el) => getComputedStyle(el).opacity),
    "0",
  );
  await page.emulateMedia({ reducedMotion: "reduce" });
  await page.waitForFunction(
    () =>
      document.querySelector(".dossier-stage")?.dataset.assembly === "complete",
  );
  assert(await page.locator(".assembly-replay").isDisabled());
  assert.equal(
    await stage.evaluate((el) => el.getAnimations({ subtree: true }).length),
    0,
  );
  assert.equal(
    await page
      .locator(".dossier-stamp")
      .evaluate((el) => getComputedStyle(el).opacity),
    "1",
  );
  await context.close();

  const mobile = await browser.newContext({
    viewport: { width: 390, height: 844 },
    isMobile: true,
    hasTouch: true,
    reducedMotion: "no-preference",
  });
  const phone = await mobile.newPage();
  phone.on("pageerror", (error) => errors.push(error.message));
  await phone.goto(origin, { waitUntil: "networkidle" });
  assert.equal(
    await phone.locator(".dossier-stage").getAttribute("data-assembly"),
    "ready",
  );
  await phone.locator(".dossier-stage").scrollIntoViewIfNeeded();
  await phone.waitForFunction(
    () =>
      document.querySelector(".dossier-stage")?.dataset.assembly === "building",
  );
  assert.equal(
    await phone.evaluate(
      () => document.documentElement.scrollWidth > innerWidth,
    ),
    false,
  );
  await phone.waitForFunction(
    () =>
      document.querySelector(".dossier-stage")?.dataset.assembly === "complete",
  );
  await phone.screenshot({ path: "test-results/assembly-mobile.png" });
  await phone
    .getByRole("button", {
      name: "Replay assembly of the case file",
      exact: true,
    })
    .tap();
  await phone.waitForFunction(
    () =>
      document.querySelector(".dossier-stage")?.dataset.assembly === "building",
  );
  await phone.goto(`${origin}/projects`, { waitUntil: "networkidle" });
  await phone.getByRole("heading", { name: "Case files." }).waitFor();
  await mobile.close();

  const staticContext = await browser.newContext({ javaScriptEnabled: false });
  const staticPage = await staticContext.newPage();
  await staticPage.goto(origin);
  assert.equal(
    await staticPage
      .locator(".dossier")
      .evaluate((el) => getComputedStyle(el).opacity),
    "1",
  );
  assert(
    await staticPage
      .getByText("Dhaka / Chattogram, BD", { exact: true })
      .isVisible(),
  );
  await staticContext.close();
  assert.deepEqual(errors, []);
  console.log(
    "Passed assembly timeline, SVG drawing, stamp/clip/loupe sequence, keyboard replay, live reduced motion, mobile in-view start, touch replay, navigation cleanup and static fallback.",
  );
} finally {
  await browser.close();
}
