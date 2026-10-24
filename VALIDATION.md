# Validation - 21 October 2026

Commit containing application source: `46d93cf`. Documentation and audit scripts were added in `f03045b`.

- Production build and TypeScript checks: pass.
- Static output: 22 independent HTML documents, including the missing-file page.
- Total output: approximately 1.21 MB, including CV, fonts, screenshots and research figures.
- Main JavaScript chunk: approximately 69.1 kB gzip. Page chunks are split by route type.
- Static checks: unique titles/descriptions, canonical/social tags, valid CV, local links and asset-size budgets pass.
- Browser checks: 44 route/viewport combinations (1440px and 390px), no accessibility violations, overflow or console errors. Filters, client navigation, menu behavior, route focus and unknown-route fallback pass.
- `npm audit --omit=dev`: zero vulnerabilities.
- Lighthouse: default mobile simulation on the local production preview, after browser tests finished. All 21 indexable routes score 95+ in all four categories. The intentional `noindex` on the missing-file page lowers its SEO score; it is preserved for correct indexing behavior.

| Route | Performance | Accessibility | Best practices | SEO |
| --- | ---: | ---: | ---: | ---: |
| `/` | 97 | 100 | 100 | 100 |
| `/live` | 97 | 100 | 100 | 100 |
| `/live/mangostudents` | 97 | 100 | 100 | 100 |
| `/live/sportevo` | 97 | 100 | 100 | 100 |
| `/projects` | 99 | 100 | 100 | 100 |
| `/projects/wase` | 100 | 100 | 100 | 100 |
| `/projects/mindwell` | 98 | 100 | 100 | 100 |
| `/projects/weather-classifier` | 95 | 100 | 100 | 100 |
| `/projects/job-portal` | 98 | 100 | 100 | 100 |
| `/projects/bucuc` | 99 | 100 | 100 | 100 |
| `/projects/hospital-management` | 99 | 100 | 100 | 100 |
| `/projects/house-price-prediction` | 99 | 100 | 100 | 100 |
| `/projects/cse423-project` | 99 | 100 | 100 | 100 |
| `/projects/cse330` | 99 | 100 | 100 | 100 |
| `/projects/bracu-labmid` | 99 | 100 | 100 | 100 |
| `/projects/cse` | 99 | 100 | 100 | 100 |
| `/projects/covid-radiography` | 99 | 100 | 100 | 100 |
| `/projects/l-essence` | 98 | 100 | 100 | 100 |
| `/about` | 99 | 100 | 100 | 100 |
| `/resume` | 100 | 100 | 100 | 100 |
| `/contact` | 99 | 100 | 100 | 100 |
| `/404` | 97 | 100 | 100 | 66 |

## Publication status

Published on 21 October 2026 to the existing `rafis-projects-d9870471/portfolio-9jtk` project using the authenticated Vercel CLI. The deployment reached `READY` and was assigned to the unchanged production domain:

https://portfolio-9jtk.vercel.app

- Published source commit: `c4997ce8b448a919d644f3b0653a3deea429c3cf`.
- Deployment: `dpl_3H2wUxkid4VTYKiqhc6Wd3cvncti`.
- Dashboard: https://vercel.com/rafis-projects-d9870471/portfolio-9jtk/3H2wUxkid4VTYKiqhc6Wd3cvncti
- All 22 public routes returned HTTP 200 with their expected prerendered titles and content.
- The public CV download matches the local source PDF byte for byte.
- Hashed JavaScript assets return `Cache-Control: public, max-age=31536000, immutable`.
- No new project or production domain was created. Existing submitted CV links continue to use the same address.

The Lighthouse scores above are the recorded local production-build results. Public route, CV and cache checks were performed against the deployed production domain.

## Remaining content item

The optional authenticated SportEvo owner-schedule screenshot needs an owner-supplied image. All public homepage screenshots and WASE thesis figures are already included.

## Detective interaction update - 22 October 2026

Published source: `e3cc468600289a99b1e6025aa8c784846ff9ab90`.

- Added an original metallic magnifying-glass pointer, interactive card tilt/glare, a layered hero dossier and a decorative 3D desk magnifier.
- No graphics library, WebGL renderer, external models or image dependencies were added. Pointer code is a separate 1.28 kB gzip chunk, loaded only for fine pointers with motion enabled. The main JavaScript chunk is approximately 69.26 kB gzip; complete static output is approximately 1.22 MB.
- Build, type checks and all 22 static route checks pass. All 44 desktop/mobile route and accessibility checks pass.
- The effects check covers cursor accuracy, link activation, click feedback, 3D surfaces, keyboard restoration, live reduced-motion changes, blur, print and touch navigation. Mobile decorative art is placed below profile facts rather than over readable content.
- Performance measurements varied on the local machine. A controlled mobile Lighthouse comparison of the previous and new home pages scored 93 and 94 respectively; both scored 100 for accessibility, best practices and SEO. This does not replace the historical per-route results above or claim a new 95+ performance result for every page.
- Deployed to the original project as `dpl_5oToapuapTbj4n2K7hH4WeH8qG7s`; Vercel reported `READY` and verified HTTP 200 at https://portfolio-9jtk.vercel.app. The CV domain is unchanged.
- The effects check also passes against the public production URL, including cursor tracking through navigation and scrolling, 3D tilt, click activation, keyboard/reduced-motion fallback and touch navigation.

## Case-file assembly update - 22 October 2026

- Added a four-second, in-view hero assembly: layered papers, SVG profile drawing, sequential facts, red stamp impact, paperclip placement and magnifier arrival. Existing pointer tilt uses a separate transform from assembly motion.
- Added keyboard/touch replay, live reduced-motion cancellation and effect/timer cleanup. The complete file is prerendered and remains visible with JavaScript disabled.
- `npm run check:assembly` verifies the real CSS timeline, partial SVG drawing, delayed stamp/clip/magnifier, keyboard replay, live reduced motion, mobile visibility timing, touch replay, navigation cleanup and the static fallback. Desktop timeline and mobile screenshots are saved under ignored `test-results/`.
- Build/type checks and all 22 static route checks pass. Main JavaScript is approximately 69.25 kB gzip; complete static output is approximately 1.23 MB. No dependencies were added.
- All 44 desktop/mobile route and accessibility checks pass with zero violations, overflow or browser errors. The cursor/tilt interaction suite also passes after the assembly change.
- Published source `e1b9867` to the existing Vercel project as `dpl_9wgg5TuBU6dnnestoyUXjo8mU3cg`. Vercel reported `READY`, verified HTTP 200 and assigned the unchanged domain https://portfolio-9jtk.vercel.app.
- Both the assembly and cursor/tilt suites pass against the public production domain, including desktop/phone replay and reduced-motion behavior.

## About navigation recovery - 22 October 2026

- A fresh production session opened About correctly. Route imports previously had no rejection handler, so a tab referencing a removed deployment asset could change its URL while leaving the previous page visible.
- Failed route imports now fall back to a full navigation to the requested URL, fetching its current prerendered HTML and assets. Healthy navigation remains client-side. Initial boot does not enter a reload loop.
- `npm run check:navigation` deliberately returns a 404 for the first About module request on desktop and mobile. Both recover with one document load, render About, and then navigate through the home-page About link without another document load or uncaught error.

## Project selection update - 22 October 2026

- Removed CSE and Bracu-Labmid from the project data, rendered cards, generated detail pages and sitemap at the owner's request. Cse330 and Cse423-Project remain selected. The archive now contains 11 projects.
- Build/type checks and all 20 remaining static route checks pass. Browser checks confirm both removed URLs display the missing-file page, the project links are absent and the retained coursework projects remain visible.
- About navigation recovery still passes on desktop and mobile with the updated project list.
- Published source `3876dd5` (including navigation fix `474925b`) as `dpl_2CugMjMgH2g8nNkTYmWLgDezx8mu`. Vercel reported `READY` and verified HTTP 200 at the unchanged production domain.
- Public checks confirm 11 project cards, both removed links/detail pages/sitemap URLs absent, and successful About navigation recovery on desktop and mobile at https://portfolio-9jtk.vercel.app.

## Forming headline update - 22 October 2026

- Inspected the supplied reference site in Chromium. Added an original 2.6-second letter-formation effect to the existing hero headline: scattered, blurred glyphs and small grains settle into the original text, font and red accents. It runs once per home-page mount with no continuous JavaScript animation loop or new dependencies.
- The complete heading is prerendered with a single accessible name. Reduced motion cancels the effect immediately; JavaScript-disabled pages retain visible text. Layout is reserved before, during and after formation.
- `npm run check:headline` passes at 1440, 390 and 320 pixels, including actual CSS timeline progression, final text visibility, identical heading dimensions, no horizontal overflow, live reduced-motion changes, zero WCAG accessibility violations and static fallback. Narrow-screen checks also exposed and resolved decorative magnifier overflow during the existing assembly entrance.
- Build/type checks, all 20 static route checks, About navigation recovery, case-file assembly and cursor/tilt suites pass. Main JavaScript is approximately 69.20 kB gzip; the home route is approximately 2.99 kB gzip; complete output is approximately 1.22 MB.
- Published source `0164dda` as `dpl_9zSUrHKc3qWt68of6Bj1TAgAB44n`. Vercel reported `READY`, verified HTTP 200 and retained https://portfolio-9jtk.vercel.app. The headline suite also passes against that public URL at all three viewport widths, including reduced motion, accessibility and static fallback.

## Card navigation, WASE video and mobile folder update - 22 October 2026

- All live-case and project cards now have a full-surface native file link, including image, description, tags and blank padding. Separate external site links remain above that surface. The profile folder opens About; links retain keyboard and native modified-click behavior. The custom cursor yields to native video controls.
- Added the supplied, unchanged `wase-live.mp4` to a prominent WASE demonstration section, with an extracted WebP poster, native controls, inline playback, descriptive text and download link. The silent 15-second clip plays and seeks correctly without autoplay or preloading the video file.
- Removed the assembly replay control and its toolbar. The initial assembly remains. Removed mobile clipping, contained folder margins within their layout, and moved the magnifier/stamp entrances inward for narrow viewports. A 360px screenshot shows the entire tab, paperclip, document and magnifier. Animation samples at 50ms intervals across the full entrance show no horizontal overflow at 320, 360 or 390 pixels.
- Build/type checks and all 20 static route checks pass; output is approximately 3.00 MB including the video. All 40 desktop/mobile route and accessibility checks pass with zero violations, overflow or browser errors.
- `npm run check:cards-video` clicks blank padding on every card in the home, live and project lists on desktop and mobile. It verifies correct destinations, separate external links, keyboard activation, profile-folder navigation, video playback/seeking and WASE accessibility.
- Updated assembly checks verify that the replay control is absent and reduced-motion behavior remains correct. Assembly, headline (1440/390/320px) and cursor/tilt suites pass.
- Published source `689773f` as `dpl_C5qkwRCAckGGmdHmWz5mHZM7rbTF`. Vercel reported `READY`, verified HTTP 200 and retained https://portfolio-9jtk.vercel.app.
- Public card/video checks pass on desktop and mobile, and public assembly checks pass with no replay control. The served MP4 matches the source SHA-256 and returns HTTP 206 for byte-range requests. A public 360px screenshot confirms the complete folder is visible without clipping or horizontal overflow.

## Skills in motion - 25 October 2026

- Added alternating moving technology badges to the homepage and replaced the About toolkit with six grouped skill rows. All 27 skill entries come from the existing content data; no proficiency scores or new skill claims were added.
- CSS controls the loops, with no JavaScript render loop or new dependencies. Pause/resume stops all rows, hover or keyboard inspection pauses a row, and offscreen or hidden-tab state stops motion. Reduced motion shows wrapped lists; without JavaScript, native horizontal scrolling preserves access to every skill. Repeated visual copies are hidden from assistive technology.
- Added a homepage link to `/about#skills` and route-change anchor positioning so visitors land on the toolkit with the existing sticky-header offset.
- `npm run check:skills` passes at 1440, 390 and 320 pixels: motion in view, idle state offscreen, keyboard pause/resume, row inspection, anchor navigation, all 27 canonical skills, live reduced motion, no overflow, zero WCAG violations and JavaScript-disabled scrolling.
- Build/type checks and all 20 static route checks pass. Main JavaScript is approximately 69.26 kB gzip; the shared skills chunk is approximately 1.13 kB gzip; total static output is approximately 3.02 MB. Cursor/tilt and navigation-recovery suites pass.
- All 40 desktop/mobile route and accessibility checks pass with zero violations, overflow or browser errors.
- Published source `ada8e99` as `dpl_3AHnR6ahyPt22EwWaMpCPLXSUHp3`. Vercel reported `READY`, verified HTTP 200 and retained https://portfolio-9jtk.vercel.app.
- The full skills suite passes against production at all three viewport widths. A separate touch-phone check confirms that the direct homepage `#skills` link, tap-to-pause and tap-to-resume work without overflow.

## One-at-a-time skill display correction - 25 October 2026

- Replaced the scrolling rows following the owner's clarification. One skill rotates to the front and faces the reader, holds for reading, then turns aside as the next arrives every 3.2 seconds. Inactive neighboring cards are decorative; only the current slide is exposed to assistive technology.
- Added previous/next controls, left/right keyboard navigation, touch swiping, pause/resume and a complete native disclosure containing all 27 original skill entries. Manual navigation pauses autoplay. Reduced motion disables autoplay/transitions while preserving manual selection; without JavaScript the first skill and full disclosure remain available.
- Motion uses a single timeout per change with cleanup, plus CSS 3D transitions. It stops offscreen, during hover/focus inspection, when paused and in hidden tabs. No rendering library or frame loop was added.
- The revised skills suite passes at 1440, 390 and 320 pixels: actual auto-advance and pause, front-facing versus angled card matrices, one accessible active card, previous/next and keyboard controls, real touch swipes, anchor navigation, full inventory, reduced motion, static fallback, no overflow and zero accessibility violations.
- Build/type checks and all 20 static route checks pass. The shared skills chunk is approximately 1.66 kB gzip; total static output is approximately 3.02 MB.
- All 40 desktop/mobile route and accessibility checks pass with zero violations, overflow or browser errors.
- Published source `cef8150` as `dpl_GRaX2Em6fS5VNsLUMQ2bseBgxfre`. Vercel reported `READY` and verified HTTP 200 at the unchanged https://portfolio-9jtk.vercel.app domain.
- The revised one-at-a-time skills suite also passes against the public production URL at 1440, 390 and 320 pixels, including auto-advance, face-on matrices, pause, manual/keyboard selection, touch swipes, accessibility and static fallback.

## Plain appearing/disappearing skills - 25 October 2026

- Removed the skill cards, frames, emblems, 3D transforms and neighboring previews following the owner's clarification. A single plain-text skill fades in, holds, fades completely out and leaves a brief empty interval before the next begins. Only one skill name exists in the animated display at a time.
- A browser animation owns the 3.2-second sequence; its completion starts the next name. Offscreen/hidden-tab pauses preserve progress. Explicit pause and manual selection leave a readable, static name. Reduced motion disables animation while retaining controls and the full 27-skill disclosure.
- Updated skills checks validate the actual appearance, held and fully vanished timeline states, a blank interval, one displayed name, no card markup/border/background/shadow, automatic advance, pause/resume, keyboard selection, real touch swipes, inventory, anchor navigation, reduced motion and static fallback. No new dependencies were added.
- Build/type checks and all 20 static route checks pass. Total static output is approximately 3.01 MB.
- The revised skills suite passes at 1440, 390 and 320 pixels with zero accessibility violations, horizontal overflow or browser errors.
- Published source `150db0f` as `dpl_EbCN82dnNsSPgtQAbK8ZF1qZfoif`. Vercel reported `READY` and verified HTTP 200 at the unchanged https://portfolio-9jtk.vercel.app domain.
- The plain-text skills suite also passes against production at all three widths, including the completely vanished interval, single unframed name, controls, real touch swipes, reduced motion, accessibility and static fallback.

## Constructed letters and pause-button removal - 25 October 2026

- Removed the pause/resume/motion-off button at the owner's request. Each skill now draws real Playfair Display letter outlines in sequence, then fills them in, holds and disappears before the next begins. There are no cards or boxes around the letters.
- Converted 49 glyphs from the site's existing licensed font into checked-in SVG path data. Runtime drawing uses native SVG paths and browser animations; no rendering library or JavaScript frame loop is needed. Python fontTools and Brotli are only optional asset-generation tools, not application dependencies.
- Previous/next, arrow keys and touch swipes remain available. Keyboard focus inspection and the expanded full-skill list leave readable, fully formed letters and stop animation. Closing the list restarts construction; offscreen/hidden-tab changes pause animation progress. Reduced motion and JavaScript-disabled pages show fully formed glyphs and retain the native inventory.
- The updated skills suite passes at 1440, 390 and 320 pixels: no pause button, partial outline tracing, staggered later letters, completed fills, fully vanished words, auto-advance, full-list pause, keyboard/touch navigation, reduced motion, static fallback, no overflow, zero accessibility violations and no browser errors.
- Build/type checks and all 20 static route checks pass. Static output is approximately 3.06 MB, including the pre-existing WASE video.
- All 40 desktop/mobile route and accessibility checks pass with zero violations, overflow or browser errors.
- Published source `2ab37c9` as `dpl_5PsWm9Qxdvfd3vhpcwfVvWUiJHkE`. Vercel reported `READY` and verified HTTP 200 at the unchanged https://portfolio-9jtk.vercel.app domain.
- Public skills checks pass at 1440, 390 and 320 pixels, including real outline construction, filling and disappearance, button removal, glyph coverage/layout for all 27 skills, full-list pause, keyboard/touch navigation, accessibility, reduced motion and JavaScript-disabled fallback.
