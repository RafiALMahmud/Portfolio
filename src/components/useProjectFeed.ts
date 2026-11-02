import { useSyncExternalStore } from "react";
import {
  feedUrl,
  initialFeed,
  initialProjects,
  projectCatalogue,
  sortProjects,
  validFeed,
} from "../data/projectFeed";

const initialState = {
  projects: initialProjects,
  syncedAt: initialFeed.syncedAt,
  checked: false,
};
let state = initialState;
const listeners = new Set<() => void>();
let pending = false;
let attemptedAt = 0;
const refreshEvery = 15 * 60 * 1000;

async function refresh() {
  if (
    pending ||
    (attemptedAt && Date.now() - attemptedAt < refreshEvery) ||
    document.hidden
  )
    return;
  attemptedAt = Date.now();
  pending = true;
  try {
    const response = await fetch(feedUrl, {
      cache: "no-cache",
      signal: AbortSignal.timeout(8000),
    });
    if (!response.ok) throw new Error("Feed unavailable");
    const feed: unknown = await response.json();
    if (!validFeed(feed)) throw new Error("Invalid feed");
    if (feed.syncedAt >= state.syncedAt) {
      state = {
        projects: sortProjects(projectCatalogue(feed)),
        syncedAt: feed.syncedAt,
        checked: true,
      };
    }
  } catch {
    // Keep the last complete snapshot. GitHub outages never empty the portfolio.
  } finally {
    pending = false;
    state = { ...state, checked: true };
    listeners.forEach((listener) => listener());
  }
}

function subscribe(listener: () => void) {
  listeners.add(listener);
  void refresh();
  const timer = window.setInterval(() => void refresh(), 60000);
  const wake = () => void refresh();
  window.addEventListener("focus", wake);
  document.addEventListener("visibilitychange", wake);
  return () => {
    listeners.delete(listener);
    window.clearInterval(timer);
    window.removeEventListener("focus", wake);
    document.removeEventListener("visibilitychange", wake);
  };
}

export function useProjectFeed() {
  return useSyncExternalStore(
    subscribe,
    () => state,
    () => initialState,
  );
}
