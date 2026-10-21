# Rafi Al Mahmud ? Case files

A noir personal portfolio built with Vite, React, TypeScript, Tailwind CSS 4 and React Router. Each known route is prerendered to a separate HTML document with unique metadata. The deployed site is entirely static: no functions, database, runtime secrets, contact form or external build-time content fetches.

## Run and build

Use Node.js 22.12+.

```sh
npm ci
npm run dev
npm run build
npm run check
npm run preview
```

The production build type-checks, creates the client bundle, builds a temporary React server-rendering entry in `.ssr/`, then renders every route into `dist/`. The temporary server entry is never deployed. `dist/` is the complete deployment artifact; sitemap and robots files are generated there. The build works offline after dependencies are installed.

Fonts and optimized WebP images are self-hosted. Hashed assets receive immutable caching on Vercel. The latest downloaded Drive CV is `public/cv.pdf`; it is not fetched during builds.

## Verify

With `npm run preview` running in another terminal:

```sh
npx playwright install chromium
npm run check:browser
npm run check:lighthouse
```

The browser check covers every route at 1440px and 390px, accessibility rules, overflow, console errors, project filters, mobile navigation, route focus and unknown routes. Lighthouse uses its default mobile simulation and writes reports to ignored `test-results/`. To investigate individual pages: `node scripts/lighthouse-check.mjs / /live /projects/wase`. Scores can vary with host load; avoid running browser suites concurrently with performance audits. The missing-file page deliberately uses `noindex`.

## Edit content or add a page

- `src/content.ts`: identity, contact links, location, skills, research content and results.
- `src/data/liveSites.ts`: production platforms, contribution paragraphs, technical sections and screenshots.
- `src/data/projects.ts`: project metadata and locally stored README/CV summaries.
- `src/routing.ts`: route generation, page loaders and unique titles/descriptions.
- `src/pages/`: page-specific presentations; `src/components/UI.tsx`: shared case-file components.
- `src/styles.css`: Tailwind theme, local font declarations, layout, motion, mobile and print styles.

Add a project object with a unique slug, verified description, tags, stack and source note. A deep-linkable project page and sitemap entry are generated automatically. Add a live-site object to generate its case page. For a new page type, add a loader and route metadata in `src/routing.ts`, then implement its component. Run the build and checks before pushing. Keep unknown facts out of the public copy and mark genuine source gaps with `TODO` comments.

Update `person.siteUrl` before moving to a different domain. This value controls all canonicals, social images, robots and sitemap URLs. The current domain comes from the latest CV.

## Deploy on Vercel

Repository: https://github.com/RafiALMahmud/Portfolio ? branch `main`.

1. In Vercel, choose **Add New ? Project**.
2. Import **RafiALMahmud/Portfolio**. If it is already connected, use the existing project instead of creating another.
3. Select framework preset **Vite** and root directory `.`.
4. Set build command **npm run build** and output directory **dist**.
5. Select Node.js **22.x** or newer. No environment variables are needed.
6. Deploy. Future pushes to `main` trigger the connected Vercel project automatically.

`vercel.json` also supplies these build settings, clean extensionless URLs, security/cache headers and a filesystem-aware SPA fallback. Known routes serve their prerendered HTML, so direct links have the correct metadata without JavaScript. Unknown routes display the themed missing-file page in the client. No GitHub Pages workflow is used.

The original Next.js single-page source and obsolete resume PDFs were replaced. Existing commits were retained; no force push is needed.

## Routes

- `/`
- `/live`
- `/live/mangostudents`
- `/live/sportevo`
- `/projects`
- `/projects/wase`
- `/projects/mindwell`
- `/projects/weather-classifier`
- `/projects/job-portal`
- `/projects/bucuc`
- `/projects/hospital-management`
- `/projects/house-price-prediction`
- `/projects/cse423-project`
- `/projects/cse330`
- `/projects/bracu-labmid`
- `/projects/cse`
- `/projects/covid-radiography`
- `/projects/l-essence`
- `/about`
- `/resume`
- `/contact`
- `/404`
- `*` ? themed missing-file fallback

## Sources and remaining work

See [SOURCES.md](SOURCES.md) for the PDF/ZIP/Drive/GitHub audit, conflict resolution, research evaluation context and asset attribution.

- TODO: Add an owner-supplied screenshot of SportEvo's authenticated owner schedule to the gallery. Public homepage screenshots are already included. This optional screenshot is not present in the supplied documents.

The WASE datasets, method, results, original figures, supervisor, teaching copy, contact links, both live-site roles and additional CV projects have been filled from the supplied sources. No public WASE repository has been identified, so the research page has no code link. The three sparse coursework repositories remain limited to documented names, languages and source links.
