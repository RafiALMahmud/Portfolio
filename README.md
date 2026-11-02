# Rafi Al Mahmud ? Case files

Live site: https://portfolio-9jtk.vercel.app - deployed to the existing project; keep this domain for submitted CV links.

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

Fonts and optimized WebP images are self-hosted. Hashed assets receive immutable caching on Vercel. `public/cv.pdf` is the Software Engineer PDF from the owner's `Rafi_Updated_CVs_2026.zip`, supplied 2 November 2026. All existing CV links use the same URL, with revalidation to avoid stale downloads.

## Automatic GitHub projects

WASE is always pinned first on Home and in both archive sort orders. The home heading is "Cases worked on." Filters continue to show matching projects; other projects retain their automatic ranking.

`.github/workflows/sync-github.yml` runs every six hours (00:23, 06:23, 12:23 and 18:23 UTC), on relevant source changes, or manually from GitHub Actions. It uses GitHub's automatically supplied, short-lived `GITHUB_TOKEN`; no personal token, AI session, npm install or Vercel secret is required. `scripts/sync-github.mjs` paginates public owned repositories and 30-day default-branch commit histories, counts coding files, then commits `public/github-projects.json` only after the whole sync succeeds. The timestamp keeps the publishing repository active even when projects are quiet. GitHub may delay scheduled runs.

The static website ships a complete snapshot, then reads the latest public JSON directly from this repository, once on opening and every 15 minutes while visible. Data changes do not require redeployment. GitHub outages or invalid responses preserve the last good archive. New repositories get full clickable case files and direct-link support immediately; known projects retain their verified descriptions and existing URLs. Home shows the top three ranked projects. Research and documented work without public repositories remain available.

Default ranking sums recent human, non-merge commits over 30 days with a seven-day half-life, then uses code-file count to break ties. A second sort orders by code-file count first. This measures visible commit activity, not effort or hours. Counts include code, templates, styles, SQL and notebooks, excluding dependency/build/generated directories, minified files, declaration files and non-code assets. Large truncated trees are traversed fully rather than reported as exact partial counts. CSE, Bracu-Labmid and the profile README repository stay excluded; archived/private/disabled and zero-code repositories are omitted. Forks require Rafi-authored recent commits, except the explicitly documented bari.com project.

For local data refresh use `GITHUB_TOKEN` in the process environment and run `npm run sync:github`. Never put a token in browser code or commit it. To change exclusions or file classification, edit `scripts/github-sync-lib.mjs` and the matching client validation in `src/data/projectFeed.ts`. The schedule publishes data independently of the Vercel Git integration.

The detective effects use an original magnifying-glass cursor, pointer-responsive case cards, a layered dossier and a floating desk magnifier. They use SVG/CSS 3D and one event-driven pointer handler, with no graphics library or continuous JavaScript render loop. Touch devices retain normal navigation, keyboard use restores the native cursor, and reduced-motion preferences disable animated pointer and tilt effects.

The home-page case file assembles once in four seconds when most of it enters view: paper layers settle, the profile draws in, details appear, and the stamp, paperclip and magnifier land. Reduced motion and pages without JavaScript show the complete file immediately. The folder opens About; its mobile layout preserves the full tab and paperclip without clipping. There is no replay control.

Case and project cards use full-surface native links, while separate live-site actions retain their external destinations. The WASE page includes the owner's original 15-second silent MP4, an extracted preview frame, native playback controls and a download link. It does not autoplay or preload the video file.

The hero headline forms from scattered, blurred letters and small grains in a single 2.6-second sequence. Its layout is reserved throughout, with one complete accessible heading. Reduced motion and JavaScript-disabled pages show the full text immediately.

Skills appear as unframed lettering on Home and About: the actual font outlines draw letter by letter, fill in, hold, disappear fully, then the next name begins. There are no arrow or pause buttons. Arrow keys and mobile swipes can still change the skill. Opening the full 27-skill list or focusing the display stops motion for reading. Motion also stops offscreen or in a hidden tab. Reduced motion and pages without JavaScript show fully formed letters.

Letter construction now takes about twice as long. Small paper and red particles emerge from behind the text plane and converge onto sampled points on the real glyph contours before fading into the letters. Particle counts are capped at 48 on phones and 84 on larger screens; resize, navigation and reduced-motion changes clean up the effect.

## Verify

With `npm run preview` running in another terminal:

```sh
npx playwright install chromium
npm run check:browser
npm run check:effects
npm run check:assembly
npm run check:navigation
npm run check:headline
npm run check:cards-video
npm run check:skills
npm run check:github
npm run check:lighthouse
```

The browser check covers every route at 1440px and 390px, accessibility rules, overflow, console errors, project filters, mobile navigation, route focus and unknown routes. Lighthouse uses its default mobile simulation and writes reports to ignored `test-results/`. To investigate individual pages: `node scripts/lighthouse-check.mjs / /live /projects/wase`. Scores can vary with host load; avoid running browser suites concurrently with performance audits. The missing-file page deliberately uses `noindex`.

## Edit content or add a page

- `src/content.ts`: identity, contact links, location, skills, research content and results.
- `src/data/liveSites.ts`: production platforms, contribution paragraphs, technical sections and screenshots.
- `src/data/projects.ts`: curated project metadata and verified README/CV summaries.
- `src/data/projectFeed.ts`, `src/components/useProjectFeed.ts` and `public/github-projects.json`: validated auto-sync data, ranking, static fallback and shared browser refresh.
- `src/routing.ts`: route generation, page loaders and unique titles/descriptions.
- `src/pages/`: page-specific presentations; `src/components/UI.tsx`: shared case-file components.
- `src/styles.css`: Tailwind theme, local font declarations, layout, motion, mobile and print styles.
- `src/spatial.css` and `src/components/InvestigatorEffects.tsx`: detective cursor, 3D surfaces and pointer/motion behavior. `DeskLoupe.tsx` draws the decorative hero magnifier.
- `src/components/CaseFile.tsx`: in-view case-file assembly, profile-file link and motion-preference lifecycle.
- `src/components/FormingHeadline.tsx`: finite letter formation with static and reduced-motion fallbacks.
- `src/components/SkillsMotion.tsx` and `src/skills.css`: letter-outline construction, single-name disappearance, navigation controls and static inventory. The skill names still come from `src/content.ts`. `src/data/skillGlyphs.json` contains outlines derived from the existing Playfair font; the optional `scripts/generate-skill-glyphs.py` helper regenerates them with fontTools and Brotli. Normal builds require no Python packages.
- `src/components/skillParticles.ts`: bounded particles sampled from glyph paths, projected from behind the text with CSS 3D and native browser animations.

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
- `/projects/covid-radiography`
- `/projects/l-essence`
- `/about`
- `/resume`
- `/contact`
- `/404`
- `*` ? themed missing-file fallback

See [VALIDATION.md](VALIDATION.md) for recorded browser checks, build size, per-route Lighthouse scores and publication status.

## Sources and remaining work

See [SOURCES.md](SOURCES.md) for the PDF/ZIP/Drive/GitHub audit, conflict resolution, research evaluation context and asset attribution.

- TODO: Add an owner-supplied screenshot of SportEvo's authenticated owner schedule to the gallery. Public homepage screenshots are already included. This optional screenshot is not present in the supplied documents.

The WASE datasets, method, results, original figures, supervisor, teaching copy, contact links, both live-site roles and additional CV projects have been filled from the supplied sources. No public WASE repository has been identified, so the research page has no code link. The two sparse coursework repositories remain limited to documented names, languages and source links. The generic CSE and Bracu-Labmid repositories were removed from the site at the owner's request.
