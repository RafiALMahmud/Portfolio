import type { ComponentType } from "react";
import { projects } from "./data/projects";
import { liveSites } from "./data/liveSites";
import { person, research } from "./content";

export type PageProps = { path: string };
export const routes = [
  "/",
  "/live",
  ...liveSites.map((s) => "/live/" + s.slug),
  "/projects",
  ...projects.map((p) => "/projects/" + p.slug),
  "/about",
  "/resume",
  "/contact",
];
const loaders = {
  home: () => import("./pages/Home"),
  live: () => import("./pages/Live"),
  liveCase: () => import("./pages/LiveCase"),
  projects: () => import("./pages/Projects"),
  project: () => import("./pages/Project"),
  wase: () => import("./pages/Wase"),
  about: () => import("./pages/About"),
  resume: () => import("./pages/Resume"),
  contact: () => import("./pages/Contact"),
  missing: () => import("./pages/NotFound"),
};
export function pageKey(path: string): keyof typeof loaders {
  if (path === "/") return "home";
  if (path === "/live") return "live";
  if (liveSites.some((s) => path === "/live/" + s.slug)) return "liveCase";
  if (path === "/projects") return "projects";
  if (path === "/projects/wase") return "wase";
  if (projects.some((p) => path === "/projects/" + p.slug)) return "project";
  if (path === "/about") return "about";
  if (path === "/resume") return "resume";
  if (path === "/contact") return "contact";
  return "missing";
}
export async function loadPage(
  path: string,
): Promise<ComponentType<PageProps>> {
  return (await loaders[pageKey(path)]()).default;
}
export function metadata(path: string) {
  const live = liveSites.find((s) => path === "/live/" + s.slug);
  const project = projects.find((p) => path === "/projects/" + p.slug);
  const base: Record<string, [string, string]> = {
    "/": [
      "Rafi Al Mahmud · Backend Developer",
      "Backend development, live web platforms and fog-robust computer vision research. Explore Rafi Al Mahmud’s case files.",
    ],
    "/live": [
      "Live cases",
      "Explore MangoStudents and SportEvo: live publishing and sports booking platforms built by Rafi Al Mahmud.",
    ],
    "/projects": [
      "Case files",
      "Browse backend development, Python, PHP and computer vision research projects, with locally stored GitHub project summaries.",
    ],
    "/about": [
      "The person behind the files",
      "Rafi Al Mahmud’s education, teaching experience, development skills and research background. B.Sc. CSE, BRAC University.",
    ],
    "/resume": [
      "Resume",
      "Read and download Rafi Al Mahmud’s latest CV, including education, backend development, teaching and computer vision research.",
    ],
    "/contact": [
      "Open a new case",
      "Contact Rafi Al Mahmud by email or phone. Based in Dhaka / Chattogram, Bangladesh. Find GitHub, LinkedIn and the latest CV.",
    ],
  };
  const known = routes.includes(path);
  const [title, description] = live
    ? [live.title, live.description]
    : project
      ? [
          project.slug === "wase" ? research.title : project.title,
          project.description ||
            `View ${project.title}, its repository and documented language.`,
        ]
      : base[path] || [
          "File not found",
          "This case file could not be found. Return to the portfolio or explore the project archive.",
        ];
  return {
    title: path === "/" ? title : `${title} · ${person.name}`,
    description,
    canonical: person.siteUrl + (known ? path : "/404"),
    noindex: !known,
  };
}
