import { chromium } from "playwright";
import assert from "node:assert/strict";

const origin = process.env.PREVIEW_URL || "http://127.0.0.1:4173";
const browser = await chromium.launch({ headless: true });
try {
  for (const width of [1440, 390]) {
    const context = await browser.newContext({
      viewport: { width, height: 900 },
      reducedMotion: "reduce",
      ...(width === 390 ? { isMobile: true, hasTouch: true } : {}),
    });
    const page = await context.newPage();
    const errors = [];
    page.on("pageerror", (error) => errors.push(error.message));
    await page.goto(origin, { waitUntil: "networkidle" });
    let failures = 0;
    let documentRequests = 0;
    page.on("request", (request) => {
      if (
        request.isNavigationRequest() &&
        new URL(request.url()).pathname === "/about"
      )
        documentRequests++;
    });
    // Simulate a stale tab requesting an About chunk removed by a deployment.
    await page.route("**/assets/About-*.js", async (route) => {
      if (failures++ === 0) {
        await route.fulfill({
          status: 404,
          contentType: "text/plain",
          body: "Removed deployment asset",
        });
      } else {
        await route.continue();
      }
    });
    if (width === 390)
      await page.getByRole("button", { name: "Open navigation" }).click();
    await page
      .getByRole("navigation", { name: "Main navigation" })
      .getByRole("link", { name: "About", exact: true })
      .click();
    await page.getByRole("heading", { name: "Behind the files." }).waitFor();
    await page.waitForLoadState("networkidle");
    assert.equal(new URL(page.url()).pathname, "/about");
    assert.equal(
      documentRequests,
      1,
      "A failed chunk should fall back to one full document navigation",
    );
    assert(
      failures >= 2,
      "The new document should request the current About asset",
    );
    assert.deepEqual(errors, []);
    await page.unroute("**/assets/About-*.js");
    await page.locator(".brand").click();
    await page
      .getByRole("heading", { level: 1 })
      .filter({ hasText: "Everybody" })
      .waitFor();
    // The second About link must also work after recovery.
    await page
      .getByRole("link", { name: /Meet the person behind the files/ })
      .click();
    await page.getByRole("heading", { name: "Behind the files." }).waitFor();
    assert.equal(
      documentRequests,
      1,
      "Healthy navigation should remain client-side",
    );
    await context.close();
    console.log(
      `${width}px: About recovers from a missing deployment asset; header and home-page links work.`,
    );
  }
} finally {
  await browser.close();
}
