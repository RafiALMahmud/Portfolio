import { Link } from "react-router-dom";
import { projects } from "../data/projects";
import { Arrow, FileHeader, Tags } from "../components/UI";
import type { PageProps } from "../routing";
export default function Project({ path }: PageProps) {
  const index = projects.findIndex((p) => path === "/projects/" + p.slug),
    p = projects[index];
  const previous = projects[(index + projects.length - 1) % projects.length],
    next = projects[(index + 1) % projects.length];
  return (
    <>
      <FileHeader
        number={"P—" + String(index + 3).padStart(3, "0")}
        title={p.title}
        label="PROJECTS"
        intro={p.description}
      >
        {p.slug.toUpperCase()}
      </FileHeader>
      <div className="document-page page-section">
        <Tags items={p.stack.length ? p.stack : [p.language]} />
        {p.body && (
          <section>
            <h2>What it does.</h2>
            <p>{p.body}</p>
          </section>
        )}
        {p.repository && (
          <a
            className="button primary"
            href={p.repository}
            target="_blank"
            rel="noreferrer"
          >
            View source on GitHub <Arrow />
          </a>
        )}
        {p.external && (
          <a
            className="button primary"
            href={p.external}
            target="_blank"
            rel="noreferrer"
          >
            Visit website <Arrow />
          </a>
        )}
        <details className="source-note">
          <summary>Source notes</summary>
          <p>{p.sourceNote}</p>
        </details>
        <div className="prev-next">
          <Link to={"/projects/" + previous.slug}>
            <span className="mono">← PREVIOUS FILE</span>
            {previous.title}
          </Link>
          <Link to={"/projects/" + next.slug}>
            <span className="mono">NEXT FILE →</span>
            {next.title}
          </Link>
        </div>
      </div>
    </>
  );
}
