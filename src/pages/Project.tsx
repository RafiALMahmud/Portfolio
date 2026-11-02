import { Link } from "react-router-dom";
import { useProjectFeed } from "../components/useProjectFeed";
import ProjectActivity from "../components/ProjectActivity";
import NotFound from "./NotFound";
import { Arrow, FileHeader, Tags } from "../components/UI";
import type { PageProps } from "../routing";
export default function Project({ path }: PageProps) {
  const { projects, checked } = useProjectFeed();
  const index = projects.findIndex((p) => path === "/projects/" + p.slug),
    p = projects[index];
  const previous = projects[(index + projects.length - 1) % projects.length],
    next = projects[(index + 1) % projects.length];
  if (!p) {
    if (checked) return <NotFound />;
    return (
      <FileHeader
        number="P—INDEX"
        title="Opening the case file…"
        label="PROJECTS"
        intro="Checking the latest public project archive."
      />
    );
  }
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
        <ProjectActivity project={p} />
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
