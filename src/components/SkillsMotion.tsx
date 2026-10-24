import { useEffect, useRef, useState } from "react";
import { Link } from "react-router-dom";
import { skills } from "../content";
import { Arrow } from "./UI";

const symbols: Record<string, string> = {
  Languages: "{ }",
  Backend: "/api",
  Frontend: "</>",
  "Data & ML": "AI",
  Databases: "DB",
  Tooling: ">_",
};
const tools = Object.entries(skills).flatMap(([group, names]) =>
  names.map((name) => ({ name, group, symbol: symbols[group] })),
);

/** Each tool turns toward the reader, holds face-on, then yields to the next. */
export default function SkillsMotion({
  compact = false,
}: {
  compact?: boolean;
}) {
  const stage = useRef<HTMLDivElement>(null);
  const touch = useRef<{ x: number; y: number } | null>(null);
  const [active, setActive] = useState(0);
  const [enhanced, setEnhanced] = useState(false);
  const [motion, setMotion] = useState(false);
  const [visible, setVisible] = useState(false);
  const [hidden, setHidden] = useState(false);
  const [paused, setPaused] = useState(false);
  const [inspecting, setInspecting] = useState(false);
  const running = motion && visible && !hidden && !paused && !inspecting;

  useEffect(() => {
    const preference = window.matchMedia("(prefers-reduced-motion: reduce)");
    const configure = () => setMotion(!preference.matches);
    const visibility = () => setHidden(document.hidden);
    const observer = new IntersectionObserver(([entry]) =>
      setVisible(entry.isIntersecting),
    );
    if (stage.current) observer.observe(stage.current);
    setEnhanced(true);
    configure();
    visibility();
    preference.addEventListener("change", configure);
    document.addEventListener("visibilitychange", visibility);
    return () => {
      observer.disconnect();
      preference.removeEventListener("change", configure);
      document.removeEventListener("visibilitychange", visibility);
    };
  }, []);

  useEffect(() => {
    if (!running) return;
    const timer = window.setTimeout(
      () => setActive((index) => (index + 1) % tools.length),
      3200,
    );
    return () => window.clearTimeout(timer);
  }, [running, active]);

  const step = (direction: number) => {
    setPaused(true);
    setActive((index) => (index + direction + tools.length) % tools.length);
  };

  return (
    <section
      id="skills"
      className={`skills-motion ${compact ? "skills-preview" : "skills-section"}`}
      data-running={running}
      data-active={active}
      aria-labelledby="skills-title"
    >
      <div className="skills-motion-heading">
        <div>
          <span className="mono section-index">03 / THE TOOLKIT</span>
          <h2 id="skills-title">
            {compact ? "The working toolkit." : "Tools of the trade."}
          </h2>
        </div>
        <span className="skill-count mono" aria-hidden="true">
          {String(active + 1).padStart(2, "0")} / {tools.length}
        </span>
      </div>
      <div
        className="skill-stage"
        ref={stage}
        role="region"
        aria-roledescription="carousel"
        aria-label="Skills, one at a time"
        tabIndex={0}
        onPointerEnter={(event) => {
          if (event.pointerType === "mouse") setInspecting(true);
        }}
        onPointerLeave={() => setInspecting(false)}
        onFocus={() => setInspecting(true)}
        onBlur={() => setInspecting(false)}
        onKeyDown={(event) => {
          if (event.key === "ArrowLeft" || event.key === "ArrowRight") {
            event.preventDefault();
            step(event.key === "ArrowLeft" ? -1 : 1);
          }
        }}
        onPointerDown={(event) => {
          if (event.pointerType === "touch")
            touch.current = { x: event.clientX, y: event.clientY };
        }}
        onPointerUp={(event) => {
          if (!touch.current) return;
          const dx = event.clientX - touch.current.x,
            dy = event.clientY - touch.current.y;
          touch.current = null;
          if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy))
            step(dx < 0 ? 1 : -1);
        }}
        onPointerCancel={() => {
          touch.current = null;
        }}
      >
        <ul className="skill-deck" id="skill-deck" aria-live="off">
          {tools.map((tool, index) => {
            const offset = (index - active + tools.length) % tools.length;
            const position =
              offset === 0
                ? "front"
                : offset === 1
                  ? "next"
                  : offset === tools.length - 1
                    ? "previous"
                    : offset < tools.length / 2
                      ? "back-right"
                      : "back-left";
            return (
              <li
                key={tool.name}
                className="skill-face"
                data-position={position}
                aria-hidden={offset !== 0}
              >
                <div className="skill-face-meta mono">
                  <span>{tool.group}</span>
                  <span>{String(index + 1).padStart(2, "0")}</span>
                </div>
                <span className="skill-emblem" aria-hidden="true">
                  {tool.symbol}
                </span>
                <h3>{tool.name}</h3>
              </li>
            );
          })}
        </ul>
      </div>
      <div
        className="skill-controls"
        role="group"
        aria-label="Skill display controls"
      >
        <button
          type="button"
          className="skill-step"
          aria-label="Previous skill"
          aria-controls="skill-deck"
          disabled={!enhanced}
          onClick={() => step(-1)}
        >
          <span aria-hidden="true">&larr;</span>
        </button>
        <button
          type="button"
          className="skills-motion-toggle mono"
          disabled={!motion}
          aria-pressed={paused}
          onClick={() => setPaused((value) => !value)}
        >
          {!motion ? "Motion off" : paused ? "Resume motion" : "Pause motion"}
        </button>
        <button
          type="button"
          className="skill-step"
          aria-label="Next skill"
          aria-controls="skill-deck"
          disabled={!enhanced}
          onClick={() => step(1)}
        >
          <span aria-hidden="true">&rarr;</span>
        </button>
      </div>
      <details className="skills-inventory">
        <summary>View all {tools.length} skills</summary>
        <div className="skills-inventory-grid">
          {Object.entries(skills).map(([group, names]) => (
            <div key={group}>
              <h3>{group}</h3>
              <ul>
                {names.map((name) => (
                  <li key={name}>{name}</li>
                ))}
              </ul>
            </div>
          ))}
        </div>
      </details>
      {compact && (
        <Link className="text-link skills-detail-link" to="/about#skills">
          Explore all skills <Arrow />
        </Link>
      )}
    </section>
  );
}
