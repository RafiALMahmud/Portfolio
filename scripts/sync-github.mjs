import { mkdir, writeFile } from "node:fs/promises";
import { pathToFileURL } from "node:url";
import {
  owner,
  eligibleRepository,
  isCodeFile,
  humanCommits,
  activityScore,
} from "./github-sync-lib.mjs";

export async function syncGitHub({
  token = process.env.GITHUB_TOKEN,
  now = new Date(),
} = {}) {
  const headers = {
    Accept: "application/vnd.github+json",
    "User-Agent": "Rafi-Portfolio-Sync",
    "X-GitHub-Api-Version": "2022-11-28",
  };
  if (token) headers.Authorization = `Bearer ${token}`;
  // Sequential, bounded requests stay below GitHub's secondary rate limits.
  async function get(endpoint) {
    for (let attempt = 0; attempt < 3; attempt++) {
      const response = await fetch(`https://api.github.com${endpoint}`, {
        headers,
        signal: AbortSignal.timeout(30000),
      });
      if (response.ok) return response.json();
      if (response.status >= 500 && attempt < 2) {
        await new Promise((resolve) =>
          setTimeout(resolve, 1500 * (attempt + 1)),
        );
        continue;
      }
      // Do not log response bodies, headers or credentials.
      throw new Error(`GitHub ${response.status}: ${endpoint}`);
    }
  }
  async function allPages(endpoint) {
    const result = [];
    for (let page = 1; ; page++) {
      const batch = await get(
        `${endpoint}${endpoint.includes("?") ? "&" : "?"}per_page=100&page=${page}`,
      );
      result.push(...batch);
      if (batch.length < 100) return result;
    }
  }
  async function codeFiles(repo, ref) {
    const prefix = `/repos/${owner}/${encodeURIComponent(repo)}/git/trees/`;
    const tree = await get(`${prefix}${encodeURIComponent(ref)}?recursive=1`);
    if (!tree.truncated)
      return tree.tree.filter((f) => f.type === "blob" && isCodeFile(f.path))
        .length;
    // Never report an incomplete recursive tree as an exact count.
    let count = 0;
    const queue = [{ sha: ref, path: "" }];
    while (queue.length) {
      const current = queue.shift();
      const subtree = await get(`${prefix}${encodeURIComponent(current.sha)}`);
      if (subtree.truncated) throw new Error(`Incomplete tree: ${repo}`);
      for (const entry of subtree.tree) {
        const file = current.path + entry.path;
        if (entry.type === "tree")
          queue.push({ sha: entry.sha, path: file + "/" });
        else if (entry.type === "blob" && isCodeFile(file)) count++;
      }
    }
    return count;
  }

  const since = new Date(now.getTime() - 30 * 86400000).toISOString();
  const repos = (
    await allPages(`/users/${owner}/repos?type=owner&sort=full_name`)
  ).filter(eligibleRepository);
  const repositories = [];
  for (const repo of repos) {
    if (!repo.size) continue;
    const recent = humanCommits(
      await allPages(
        `/repos/${owner}/${encodeURIComponent(repo.name)}/commits?sha=${encodeURIComponent(repo.default_branch)}&since=${encodeURIComponent(since)}`,
      ),
    );
    // A fork is included only when Rafi has authored recent work, or the new CV explicitly identifies it.
    if (
      repo.fork &&
      repo.name !== "bari.com" &&
      !recent.some(
        (c) => c.author?.login?.toLowerCase() === owner.toLowerCase(),
      )
    )
      continue;
    const count = await codeFiles(repo.name, repo.default_branch);
    if (!count) continue;
    const latest = await get(
      `/repos/${owner}/${encodeURIComponent(repo.name)}/commits?sha=${encodeURIComponent(repo.default_branch)}&per_page=1`,
    );
    repositories.push({
      name: repo.name,
      description: (repo.description || "").slice(0, 600),
      language: repo.language || "Code",
      topics: (repo.topics || []).slice(0, 8),
      codeFiles: count,
      recentCommits: recent.length,
      activityScore: activityScore(recent, now.getTime()),
      lastCommitAt: latest[0]?.commit.author.date || repo.pushed_at,
    });
    console.log(
      `${repo.name}: ${recent.length} recent commits, ${count} code files`,
    );
  }
  repositories.sort(
    (a, b) =>
      b.activityScore - a.activityScore ||
      b.codeFiles - a.codeFiles ||
      a.name.localeCompare(b.name),
  );
  const snapshot = {
    version: 1,
    owner,
    syncedAt: now.toISOString(),
    windowDays: 30,
    repositories,
  };
  await mkdir("public", { recursive: true });
  // Replace only after every request succeeds. Failed runs preserve the last good feed.
  await writeFile(
    "public/github-projects.json",
    JSON.stringify(snapshot, null, 2) + "\n",
  );
  console.log(`Synced ${repositories.length} public coding repositories.`);
  return snapshot;
}

if (
  process.argv[1] &&
  import.meta.url === pathToFileURL(process.argv[1]).href
) {
  await syncGitHub();
}
