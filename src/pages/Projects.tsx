import { useState } from "react";
import { projects } from "../data/projects";
import { person } from "../content";
import { Arrow, FileHeader, ProjectCard } from "../components/UI";
export default function Projects() {
  const [filter, setFilter] = useState("All");
  const shown = projects.filter(
    (p) => filter === "All" || p.tags.includes(filter),
  );
  return (
    <>
      <FileHeader
        number="P—INDEX"
        title="Case files."
        label="PROJECTS"
        intro="Research and coursework. Source on GitHub."
      />
      <section className="page-section">
        <h2 className="sr-only">Selected projects</h2>
        <div className="filter-bar">
          <div
            role="group"
            aria-label="Filter projects by tag"
            className="filters"
          >
            {["All", "Research", "PHP", "Python", "Web"].map((tag) => (
              <button
                key={tag}
                aria-pressed={tag === filter}
                onClick={() => setFilter(tag)}
              >
                {tag}
              </button>
            ))}
          </div>
          <p role="status" aria-live="polite" className="mono">
            {shown.length} FILES FOUND
          </p>
        </div>
        <div className="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
          {shown.map((p) => (
            <ProjectCard key={p.slug} project={p} index={projects.indexOf(p)} />
          ))}
        </div>
        <div className="archive-end">
          <span className="mono">END OF SELECTED FILES</span>
          <a
            className="button"
            href={person.github + "?tab=repositories"}
            target="_blank"
            rel="noreferrer"
          >
            See all repositories <Arrow />
          </a>
        </div>
      </section>
    </>
  );
}
