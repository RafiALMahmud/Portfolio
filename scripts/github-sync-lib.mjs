import path from "node:path";

// Exact names, case-insensitive. Preserve the owner's explicit exclusions.
export const owner = "RafiALMahmud";
export const excludedRepositories = new Set([
  "portfolio",
  "cse",
  "bracu-labmid",
  owner.toLowerCase(),
]);
const extensions = new Set(
  ".py .pyi .ipynb .js .jsx .mjs .cjs .ts .tsx .php .blade.php .html .htm .css .scss .sass .less .sql .java .kt .kts .c .h .cpp .hpp .cc .cs .go .rs .rb .swift .vue .svelte .astro .sh .bash .ps1 .r .R .m .mm .dart .ex .exs .erl .hs .lua .pl .scala .sol .asm .s .ino .graphql .gql .prisma"
    .toLowerCase()
    .split(" "),
);
const ignoredDirectories =
  /(^|\/)(node_modules|vendor|dist|build|out|coverage|target|venv|\.venv|env|\.git|\.next|\.nuxt|\.ssr|__pycache__|site-packages|third_party|third-party|external|generated|\.vercel)(\/|$)/i;

export function isCodeFile(file) {
  if (ignoredDirectories.test(file)) return false;
  if (/(\.min\.(js|css)|\.d\.ts|\.generated\.[^.]+|\.g\.cs)$/i.test(file))
    return false;
  return extensions.has(path.posix.extname(file).toLowerCase());
}

export function humanCommits(commits) {
  return commits.filter(
    (c) =>
      (c.parents?.length ?? 1) < 2 &&
      c.author?.type !== "Bot" &&
      !/\[bot\]$|^dependabot/i.test(
        c.author?.login || c.commit?.author?.name || "",
      ),
  );
}

export function activityScore(commits, now) {
  return (
    Math.round(
      commits.reduce((sum, c) => {
        const days = Math.max(
          0,
          (now - Date.parse(c.commit.author.date)) / 86400000,
        );
        return sum + Math.pow(0.5, days / 7);
      }, 0) * 1000,
    ) / 1000
  );
}

export function eligibleRepository(repo) {
  return (
    !repo.private &&
    !repo.archived &&
    !repo.disabled &&
    repo.owner.login.toLowerCase() === owner.toLowerCase() &&
    !excludedRepositories.has(repo.name.toLowerCase())
  );
}
