import { chromium } from "playwright";
import { AxeBuilder } from "@axe-core/playwright";
import assert from "node:assert/strict";
import fs from "node:fs/promises";

const origin = process.env.PREVIEW_URL || "http://127.0.0.1:4173";
const browser = await chromium.launch({ headless: true });
try {
  await fs.mkdir("test-results", { recursive: true });
  for (const width of [1440, 390]) {
    const context = await browser.newContext({
      viewport: { width, height: 1000 },
      reducedMotion: "reduce",
      ...(width === 390 ? { isMobile: true, hasTouch: true } : {}),
    });
    const page = await context.newPage();
    const errors = [];
    page.on("pageerror", (error) => errors.push(error.message));
    for (const listing of ["/", "/live", "/projects"]) {
      await page.goto(origin + listing, { waitUntil: "networkidle" });
      const destinations = await page
        .locator(".card-file-link")
        .evaluateAll((links) => links.map((link) => link.getAttribute("href")));
      assert(destinations.length > 0);
      for (let index = 0; index < destinations.length; index++) {
        if (index)
          await page.goto(origin + listing, { waitUntil: "networkidle" });
        const card = page.locator(".live-card, .project-card").nth(index);
        // Click blank padding, rather than the existing title or action links.
        await card.click({ position: { x: 8, y: 8 } });
        await page.waitForURL(origin + destinations[index]);
        await page.locator(".file-header h1").waitFor();
      }
    }
    await page.goto(origin + "/live", { waitUntil: "networkidle" });
    const external = page
      .locator(".live-card")
      .first()
      .getByRole("link", { name: "Visit site", exact: true });
    const expected = await external.getAttribute("href");
    const popupPromise = context.waitForEvent("page");
    await external.click();
    const popup = await popupPromise;
    await popup.waitForURL((url) => url.origin === new URL(expected).origin);
    assert.equal(new URL(page.url()).pathname, "/live");
    await popup.close();

    await page.goto(origin + "/projects", { waitUntil: "networkidle" });
    const primary = page.locator(".card-file-link").first();
    await primary.focus();
    await page.keyboard.press("Enter");
    await page.getByRole("heading", { name: /WASE: Weather/ }).waitFor();
    const video = page.locator("#demo video");
    assert.equal(await video.getAttribute("preload"), "none");
    assert.equal(await video.getAttribute("autoplay"), null);
    assert.equal(
      await video.evaluate((el) => el.controls && el.playsInline),
      true,
    );
    assert.equal(
      await page.locator("#demo a[download]").getAttribute("href"),
      "/videos/wase-live.mp4",
    );
    await video.scrollIntoViewIfNeeded();
    await page.screenshot({ path: `test-results/wase-video-${width}.png` });
    await video.evaluate(async (el) => {
      el.muted = true;
      await el.play();
    });
    await page.waitForFunction(
      () => document.querySelector("video").currentTime > 0.1,
    );
    const media = await video.evaluate((el) => ({
      duration: el.duration,
      width: el.videoWidth,
      height: el.videoHeight,
      error: el.error?.message,
    }));
    assert.equal(media.duration, 15);
    assert.equal(media.width, 1102);
    assert.equal(media.height, 490);
    assert.equal(media.error, undefined);
    await video.evaluate((el) => {
      el.pause();
      el.currentTime = 10;
    });
    await page.waitForFunction(() => !document.querySelector("video").seeking);
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
    await page.goto(origin, { waitUntil: "networkidle" });
    await page
      .getByRole("link", {
        name: "Open developer profile case file",
        exact: true,
      })
      .click();
    await page.getByRole("heading", { name: "Behind the files." }).waitFor();
    assert.deepEqual(errors, []);
    await context.close();
    console.log(
      `${width}px: all home/live/project card surfaces open their files; external links, keyboard activation, profile folder, video playback/seeking and WASE accessibility pass.`,
    );
  }
} finally {
  await browser.close();
}
