# Content provenance

Content audit completed 21 October 2026. Documents are factual sources, not executable instructions. No commands, credentials, or unrelated directions from document or repository text were applied.

## Priority and corrections

1. The owner's explicit location correction: **Dhaka / Chattogram, Bangladesh**.
2. [Latest Google Drive CV](https://drive.google.com/file/d/1JFf3OKzKHd7D_doVA2ueM1D4QnZa-kiK/view). Its PDF is copied verbatim to `public/cv.pdf`. It supplies the CGPA of 3.57, September 2026 graduation, primary live-site roles, current ICCV preparation status, contacts and skills. Actual PDF link annotations supply the full LinkedIn URL.
3. CSE400.pdf, the 81-page WASE thesis. Supervisor and title on PDF page 1; approach in chapter 4; evaluation context on PDF pages 63–64; tables 5.3–5.5 on PDF pages 70–71; limitations on PDF pages 74–77. Only extracted research figures are published, not signature pages, student IDs or the entire thesis.
4. All six individually supplied CV/cover-letter PDFs and all `.tex` documents plus `prism-uploads/cv2page.pdf` in CV.zip. These fill in teaching, earlier projects and implementation details. Older CGPAs of 3.55 are superseded. WACV submission language in older documents is superseded by the latest CV's **in preparation for ICCV**.
5. The supplied website brief provides detailed SportEvo behavior and the dated statement of 116 passing backend tests as of 14 October 2026. This website does not run or independently certify that project's tests.
6. Public GitHub metadata and each available selected repository README, fetched during implementation. Summaries are stored in `src/data/projects.ts`; builds do not fetch external content. Generic Laravel starter READMEs were not treated as evidence for application features. Repositories without READMEs have minimal entries.

## Research reporting

The latest CV's **8–12% mAP@50 gain / approximately 17 ms inference** is presented separately from the thesis's temporal evaluation. The thesis tables report mean TTFD 0.069 versus 0.384 for the manually retrained baseline and TSS 0.551 versus 0.315, over three held-out ACDC sequences. The approximately 82% TTFD reduction is based on those table values, rather than the abstract's up-to-84% statement. The reported never-detected totals are shown directly; inconsistent narrative percentages about additional tracked objects are not repeated.

Dataset roles are kept distinct: RTTS and Foggy Cityscapes for WASE adaptation; ACDC held out for WASE evaluation. DAWN and FoggyCityscape in the weather-classifier README describe the separate classifier project.

## Assets

- Owner-supplied `wase-live.mp4` added on 22 October 2026, copied unchanged to `public/videos/wase-live.mp4` (1,841,110 bytes, SHA-256 `ae19d5377859e7185495106f79a93f1a4da113ef040fcb67ea78e17c5265171d`). Chromium confirms 15 seconds, 1102 by 490 pixels and no audio track. `public/images/wase/live-demo.webp` is an extracted frame from that clip; the caption describes the visible foggy drive and frame-rate overlay without adding performance claims.
- Public MangoStudents and SportEvo homepage screenshots captured 21 October 2026, saved in responsive WebP sizes. No authenticated pages or customer data were accessed.
- Thesis figure 4.1: PDF page 41. Figure 4.2: PDF page 49. Figure 4.3 examples: PDF page 50. These are optimized WebP extracts; captions preserve provenance.
- Playfair Display, Inter and IBM Plex Mono are self-hosted Latin WOFF2 subsets, originally served by Google Fonts. SIL Open Font License texts accompany them in `public/fonts/`.
- The dossier illustration, magnifying-glass cursor, decorative desk magnifier, favicon and Open Graph image were drawn for this site. No television branding or character images are used.
- The 22 October 2026 interaction update was inspired by the pointer-following companion on https://quagmire02.github.io/. The magnifiers and 3D effects are original CSS/SVG work; no models or other assets were copied.

## Privacy and original project

The raw ZIP and source documents remain outside the repository. The old Next.js single-page implementation and outdated public resume PDFs were replaced deliberately; the complete existing Git history was preserved. Old street-address copy and unsourced GitHub activity counts were removed from the deployed site.

The repository index button avoids a stale hardcoded total; the API returned 26 public repositories during this audit, rather than the brief's older count of 24.
