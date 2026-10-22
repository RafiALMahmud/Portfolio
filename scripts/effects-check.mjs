import { chromium } from "playwright";
import assert from "node:assert/strict";

const origin = process.env.PREVIEW_URL || "http://127.0.0.1:4173";
const browser = await chromium.launch({ headless: true });
const errors = [];
try {
  const context = await browser.newContext({
    viewport: { width: 1440, height: 1000 },
    reducedMotion: "no-preference",
  });
  const page = await context.newPage();
  page.on("pageerror", (error) => errors.push(error.message));
  await page.goto(origin, { waitUntil: "networkidle" });
  assert.equal(
    await page.locator(".investigator-cursor").getAttribute("aria-hidden"),
    "true",
  );
  await page.mouse.move(420, 330);
  await page.waitForFunction(() =>
    document.documentElement.classList.contains("investigator-pointer"),
  );
  const reticle = await page.locator(".cursor-reticle").boundingBox();
  assert(Math.abs(reticle.x + reticle.width / 2 - 420) < 1);
  assert(Math.abs(reticle.y + reticle.height / 2 - 330) < 1);
  await page.evaluate(() => window.dispatchEvent(new Event("scroll")));
  assert.equal(
    await page
      .locator("html")
      .evaluate((el) => el.classList.contains("investigator-pointer")),
    true,
  );
  assert.equal(
    await page
      .locator(".investigator-cursor")
      .evaluate((el) => getComputedStyle(el).pointerEvents),
    "none",
  );

  const dossier = page.locator(".dossier-stage");
  const bounds = await dossier.boundingBox();
  await page.mouse.move(
    bounds.x + bounds.width * 0.75,
    bounds.y + bounds.height * 0.25,
  );
  await page.waitForFunction(() =>
    document.querySelector(".dossier-stage").classList.contains("is-tilting"),
  );
  assert.match(
    await page
      .locator(".dossier")
      .evaluate((el) => getComputedStyle(el).transform),
    /matrix3d/,
  );

  await page
    .getByRole("navigation", { name: "Main navigation" })
    .getByRole("link", { name: "Projects", exact: true })
    .click();
  await page.getByRole("heading", { name: "Case files." }).waitFor();
  const card = page.locator(".project-card").first();
  await card.locator(".text-link").hover();
  await page.waitForFunction(() =>
    document
      .querySelector(".investigator-cursor")
      .classList.contains("is-interactive"),
  );
  assert.match(
    await card.evaluate((el) => getComputedStyle(el).transform),
    /matrix3d/,
  );
  await page.mouse.down();
  assert.equal(
    await page
      .locator(".investigator-cursor")
      .evaluate((el) => el.classList.contains("is-pressed")),
    true,
  );
  await page.mouse.up();
  await page.getByRole("heading", { name: /WASE: Weather/ }).waitFor();

  await page.mouse.move(400, 250);
  await page.keyboard.press("Tab");
  assert.equal(
    await page
      .locator("html")
      .evaluate((el) => el.classList.contains("investigator-pointer")),
    false,
  );
  await page.mouse.move(410, 260);
  await page.emulateMedia({ reducedMotion: "reduce" });
  await page.waitForFunction(
    () => !document.documentElement.classList.contains("investigator-pointer"),
  );
  await page.mouse.move(430, 270);
  assert.equal(
    await page
      .locator(".investigator-cursor")
      .evaluate((el) => getComputedStyle(el).display),
    "none",
  );

  await page.goto(origin, { waitUntil: "networkidle" });
  assert.equal(
    await page
      .locator(".desk-loupe-object")
      .evaluate((el) => getComputedStyle(el).animationName),
    "none",
  );
  await page.emulateMedia({ reducedMotion: "no-preference" });
  // Media-query changes are delivered asynchronously before the next paint.
  await page.evaluate(
    () =>
      new Promise((resolve) =>
        requestAnimationFrame(() => requestAnimationFrame(resolve)),
      ),
  );
  await page.mouse.move(420, 320);
  await page.waitForFunction(() =>
    document.documentElement.classList.contains("investigator-pointer"),
  );
  await page.evaluate(() => window.dispatchEvent(new Event("blur")));
  assert.equal(
    await page
      .locator("html")
      .evaluate((el) => el.classList.contains("investigator-pointer")),
    false,
  );

  await page.emulateMedia({ media: "print" });
  assert.equal(
    await page
      .locator(".desk-loupe")
      .evaluate((el) => getComputedStyle(el).display),
    "none",
  );
  await context.close();

  const touch = await browser.newContext({
    viewport: { width: 390, height: 844 },
    isMobile: true,
    hasTouch: true,
    reducedMotion: "no-preference",
  });
  const phone = await touch.newPage();
  phone.on("pageerror", (error) => errors.push(error.message));
  await phone.goto(origin, { waitUntil: "networkidle" });
  assert.equal(await phone.locator(".investigator-cursor").count(), 0);
  assert.equal(
    await phone.evaluate(
      () => document.documentElement.scrollWidth > innerWidth,
    ),
    false,
  );
  await phone.getByRole("button", { name: "Open navigation" }).tap();
  await phone
    .getByRole("navigation", { name: "Main navigation" })
    .getByRole("link", { name: "Projects", exact: true })
    .tap();
  await phone.getByRole("heading", { name: "Case files." }).waitFor();
  assert.equal(
    await phone
      .locator("html")
      .evaluate((el) => el.classList.contains("investigator-pointer")),
    false,
  );
  await touch.close();
  assert.deepEqual(errors, []);
  console.log(
    "Passed cursor precision, link clicks, 3D surfaces, keyboard restore, live reduced-motion changes, blur, print and touch navigation.",
  );
} finally {
  await browser.close();
}
