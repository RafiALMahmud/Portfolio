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
