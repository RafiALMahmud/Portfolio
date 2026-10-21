import { Link } from "react-router-dom";
import { FileHeader } from "../components/UI";
export default function NotFound() {
  return (
    <>
      <FileHeader
        number="404"
        title="File not found."
        label="UNRESOLVED"
        stamp="MISSING"
        intro="The trail ends here. This file may have moved, or the address may be incomplete."
      />
      <section className="missing-page">
        <span aria-hidden="true">404</span>
        <p>There are still other leads to follow.</p>
        <div className="page-actions">
          <Link className="button primary" to="/">
            Return to the desk ↗
          </Link>
          <Link className="button" to="/projects">
            Explore case files ↗
          </Link>
        </div>
      </section>
    </>
  );
}
