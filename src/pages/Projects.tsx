import { useState } from "react";
import { sortProjects, type ProjectSort } from "../data/projectFeed";
import { useProjectFeed } from "../components/useProjectFeed";
import { person } from "../content";
import { Arrow, FileHeader, ProjectCard } from "../components/UI";
export default function Projects() {
  const [filter, setFilter] = useState("All");
  const [sort, setSort] = useState<ProjectSort>("activity");
  const { projects, syncedAt } = useProjectFeed();
  const tags = [
    ...new Set([
      "All",
      "Research",
      "PHP",
      "Python",
      "Web",
      ...projects.flatMap((p) => p.tags),
    ]),
  ];
  const shown = sortProjects(projects, sort).filter(
    (p) => filter === "All" || p.tags.includes(filter),
  );
  return (
    <>
      <FileHeader
        number="P—INDEX"
        title="Case files."
        label="PROJECTS"
        intro="Current work, research and coursework. New public coding projects sync from GitHub automatically."
      />
      <section className="page-section">
        <h2 className="sr-only">Selected projects</h2>
        <div className="filter-bar">
          <div
            role="group"
            aria-label="Filter projects by tag"
            className="filters"
          >
            {tags.map((tag) => (
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
        <div className="project-sort-bar">
          <label className="mono">
            SORT FILES
            <select
              value={sort}
              onChange={(e) => setSort(e.target.value as ProjectSort)}
            >
              <option value="activity">Most activity</option>
              <option value="files">Most code files</option>
            </select>
          </label>
          <p className="mono">
            AUTO-SYNC / EVERY 6 HOURS
            <br />
            UPDATED{" "}
            <time dateTime={syncedAt}>
              {new Date(syncedAt).toLocaleString("en-GB", {
                timeZone: "Asia/Dhaka",
                dateStyle: "medium",
                timeStyle: "short",
              })}{" "}
              DHAKA
            </time>
          </p>
        </div>
        <details className="project-ranking-note">
          <summary>How the files are ordered</summary>
          <p>
            WASE always leads the archive. Most activity uses human, non-merge
            commits on the default branch over the last 30 days. Work from the
            last week carries more weight; code-file count breaks ties. Most
            code files puts the largest source trees first. Source counts
            include code, templates, styles, SQL and notebooks, excluding
            dependencies, builds and generated files. Commit counts indicate
            activity rather than hours worked.
          </p>
          <p>
            Public repositories sync every six hours. Research and other
            documented projects remain in the archive, including those without a
            public repository.
          </p>
        </details>
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
