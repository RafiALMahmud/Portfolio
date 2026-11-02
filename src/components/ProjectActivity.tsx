import type { Project } from "../data/projects";

export default function ProjectActivity({ project }: { project: Project }) {
  if (!project.activity) return null;
  const { recentCommits, codeFiles } = project.activity;
  return (
    <div className="project-activity mono">
      <span>
        {recentCommits.toLocaleString("en-US")}{" "}
        {recentCommits === 1 ? "commit" : "commits"} / 30 days
      </span>
      <span>
        {codeFiles.toLocaleString("en-US")} code{" "}
        {codeFiles === 1 ? "file" : "files"}
      </span>
    </div>
  );
}
