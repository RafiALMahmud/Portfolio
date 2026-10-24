import { useEffect, useRef, useState } from "react";
import { Link } from "react-router-dom";
import { skills } from "../content";
import { Arrow } from "./UI";

const tools = Object.entries(skills).flatMap(([group, names]) =>
  names.map((name) => ({ name, group })),
);

/** A single unframed skill appears, holds, and vanishes before the next. */
export default function SkillsMotion({
  compact = false,
}: {
  compact?: boolean;
}) {
  const stage = useRef<HTMLDivElement>(null);
  const word = useRef<HTMLDivElement>(null);
  const animation = useRef<Animation | null>(null);
  const touch = useRef<{ x: number; y: number } | null>(null);
  const [active, setActive] = useState(0);
  const [enhanced, setEnhanced] = useState(false);
  const [motion, setMotion] = useState(false);
  const [visible, setVisible] = useState(false);
  const [hidden, setHidden] = useState(false);
  const [paused, setPaused] = useState(false);
  const running = motion && visible && !hidden && !paused;

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
    if (!motion || paused || !word.current) return;
    const effect = word.current.animate(
      [
        {
          opacity: 0,
          transform: "translateY(12px)",
          filter: "blur(5px)",
          offset: 0,
        },
        {
          opacity: 1,
          transform: "translateY(0)",
          filter: "blur(0)",
          offset: 0.16,
        },
        {
          opacity: 1,
          transform: "translateY(0)",
          filter: "blur(0)",
          offset: 0.68,
        },
        {
          opacity: 0,
          transform: "translateY(-12px)",
          filter: "blur(5px)",
          offset: 0.9,
        },
        {
          opacity: 0,
          transform: "translateY(-12px)",
          filter: "blur(5px)",
          offset: 1,
        },
      ],
      { duration: 3200, fill: "both", easing: "ease-in-out" },
    );
    effect.pause();
    effect.onfinish = () => setActive((index) => (index + 1) % tools.length);
    animation.current = effect;
    return () => {
      effect.onfinish = null;
      effect.cancel();
      animation.current = null;
    };
  }, [active, motion, paused]);

  useEffect(() => {
    if (running) animation.current?.play();
    else animation.current?.pause();
  }, [running, active, motion, paused]);

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
        aria-label="Skills, one at a time"
        tabIndex={0}
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
        <div
          className="skill-word"
          id="skill-word"
          key={active}
          ref={word}
          aria-live="off"
        >
          <span className="skill-word-category mono">
            {tools[active].group}
          </span>
          <h3>{tools[active].name}</h3>
        </div>
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
          aria-controls="skill-word"
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
          aria-controls="skill-word"
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
