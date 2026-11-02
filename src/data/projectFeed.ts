import seed from "../../public/github-projects.json";
import { projects, type Project } from "./projects";

export type ProjectSort = "activity" | "files";
export interface GitHubRepository {
  name: string;
  description: string;
  language: string;
  topics: string[];
  codeFiles: number;
  recentCommits: number;
  activityScore: number;
  lastCommitAt: string;
}
export interface GitHubFeed {
  version: number;
  owner: string;
  syncedAt: string;
  windowDays: number;
  repositories: GitHubRepository[];
}
export const feedUrl =
  "https://raw.githubusercontent.com/RafiALMahmud/Portfolio/main/public/github-projects.json";
const excluded = new Set(["cse", "bracu-labmid", "rafialmahmud"]);
export const validRepositoryName = (name: string) =>
  /^[A-Za-z0-9_.-]{1,100}$/.test(name) && !excluded.has(name.toLowerCase());

export function validFeed(value: unknown): value is GitHubFeed {
  if (!value || typeof value !== "object") return false;
  const f = value as GitHubFeed;
  return (
    f.version === 1 &&
    f.owner === "RafiALMahmud" &&
    f.windowDays === 30 &&
    typeof f.syncedAt === "string" &&
    Number.isFinite(Date.parse(f.syncedAt)) &&
    Array.isArray(f.repositories) &&
    f.repositories.length <= 1000 &&
    f.repositories.every(
      (r) =>
        r &&
        typeof r.name === "string" &&
        validRepositoryName(r.name) &&
        typeof r.description === "string" &&
        r.description.length <= 600 &&
        typeof r.language === "string" &&
        r.language.length <= 80 &&
        Array.isArray(r.topics) &&
        r.topics.length <= 8 &&
        r.topics.every((t) => typeof t === "string" && t.length <= 80) &&
        Number.isSafeInteger(r.codeFiles) &&
        r.codeFiles > 0 &&
        Number.isSafeInteger(r.recentCommits) &&
        r.recentCommits >= 0 &&
        Number.isFinite(r.activityScore) &&
        r.activityScore >= 0 &&
        typeof r.lastCommitAt === "string" &&
        Number.isFinite(Date.parse(r.lastCommitAt)),
    ) &&
    new Set(f.repositories.map((r) => r.name.toLowerCase())).size ===
      f.repositories.length
  );
}

export function projectCatalogue(feed: GitHubFeed): Project[] {
  const matched = new Set<string>();
  const catalogue: Project[] = feed.repositories.map((repo) => {
    const curated = projects.find(
      (p) =>
        p.repository?.split("/").pop()?.toLowerCase() ===
        repo.name.toLowerCase(),
    );
    if (curated) matched.add(curated.slug);
    const web = [
      "JavaScript",
      "TypeScript",
      "PHP",
      "Blade",
      "CSS",
      "HTML",
      "Vue",
      "Svelte",
    ].includes(repo.language);
    return {
      ...(curated || {
        slug: "github/" + repo.name,
        title: repo.name,
        language: repo.language,
        tags: [...new Set([repo.language, ...(web ? ["Web"] : [])])],
        description:
          repo.description ||
          `${repo.name}: a ${repo.language} project from my public GitHub archive.`,
        stack: [repo.language, ...repo.topics].slice(0, 6),
        repository: "https://github.com/RafiALMahmud/" + repo.name,
        sourceNote:
          "Public GitHub repository metadata, default-branch commit history and source-file tree. Synced automatically every six hours. Features are described only when documented by the repository owner.",
      }),
      activity: {
        codeFiles: repo.codeFiles,
        recentCommits: repo.recentCommits,
        activityScore: repo.activityScore,
        lastCommitAt: repo.lastCommitAt,
      },
    };
  });
  return [...catalogue, ...projects.filter((p) => !matched.has(p.slug))];
}

export function sortProjects(
  catalogue: Project[],
  sort: ProjectSort = "activity",
) {
  return [...catalogue].sort((a, b) => {
    const pinned = Number(b.slug === "wase") - Number(a.slug === "wase");
    const activity =
      (b.activity?.activityScore || 0) - (a.activity?.activityScore || 0);
    const files = (b.activity?.codeFiles || 0) - (a.activity?.codeFiles || 0);
    return (
      pinned ||
      (sort === "files" ? files || activity : activity || files) ||
      (b.activity?.lastCommitAt || "").localeCompare(
        a.activity?.lastCommitAt || "",
      ) ||
      a.title.localeCompare(b.title)
    );
  });
}

export const initialFeed: GitHubFeed = seed;
export const initialProjects = sortProjects(projectCatalogue(initialFeed));
