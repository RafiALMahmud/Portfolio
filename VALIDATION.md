# Validation ? 21 October 2026

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

The completed source was pushed to `RafiALMahmud/Portfolio`, branch `main`, without rewriting history. The existing Vercel production URL was still serving the previous application at the last check; GitHub had no deployment check or status for the new commit. A Vercel connection or dashboard import/redeployment is required to complete publication. The repository contains the verified static build configuration and exact import instructions in README.md.

No production deployment is claimed by these local checks. Re-run Lighthouse against the production URL after deployment to confirm CDN behavior and headers.

## Remaining content item

The optional authenticated SportEvo owner-schedule screenshot needs an owner-supplied image. All public homepage screenshots and WASE thesis figures are already included.
