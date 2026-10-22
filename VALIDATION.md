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
