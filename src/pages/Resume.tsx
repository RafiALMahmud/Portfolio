import { FileHeader } from "../components/UI";
export default function Resume() {
  return (
    <>
      <FileHeader
        number="R—001"
        title="The full record."
        label="RESUME"
        intro="Education, research and applied experience. The latest CV, in one place."
      />
      <section className="page-section">
        <div className="resume-toolbar">
          <a className="button primary" href="/cv.pdf" download>
            Download CV ↓
          </a>
          <a
            className="text-link"
            href="/cv.pdf"
            target="_blank"
            rel="noreferrer"
          >
            Open in new tab ↗
          </a>
          <span className="mono">PDF / UPDATED 21 OCT 2026</span>
        </div>
        <object
          className="pdf-viewer"
          data="/cv.pdf"
          type="application/pdf"
          title="Rafi Al Mahmud’s curriculum vitae"
        >
          <div className="pdf-fallback">
            <h2>A paper trail worth keeping.</h2>
            <p>
              Your browser cannot display this PDF. Open it in a new tab or
              download a copy to read it.
            </p>
            <a className="button primary" href="/cv.pdf">
              Open CV ↗
            </a>
          </div>
        </object>
        <p className="resume-fallback">
          If the viewer doesn’t load,{" "}
          <a
            className="inline-link"
            href="/cv.pdf"
            target="_blank"
            rel="noreferrer"
          >
            open the PDF directly
          </a>{" "}
          or use the download button.
        </p>
      </section>
    </>
  );
}
