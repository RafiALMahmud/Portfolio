import { Fragment, useEffect, useRef, useState } from "react";
import { Link } from "react-router-dom";
import { skills } from "../content";
import { Arrow } from "./UI";
import font from "../data/skillGlyphs.json";

const tools = Object.entries(skills).flatMap(([group, names]) =>
  names.map((name) => ({ name, group })),
);

function SkillLetters({ name }: { name: string }) {
  const glyphs = font.glyphs as Record<string, { width: number; path: string }>;
  const height = font.ascent - font.descent;
  return (
    <span aria-hidden="true">
      {name.split(" ").map((part, index) => (
        <Fragment key={index}>
          {index > 0 && " "}
          <span className="skill-written-word">
            {Array.from(part).map((letter, position) => {
              const glyph = glyphs[letter];
              if (!glyph) return <span key={position}>{letter}</span>;
              return (
                <svg
                  key={position}
                  className="skill-letter"
                  viewBox={`0 ${-font.ascent} ${glyph.width} ${height}`}
                  width={`${glyph.width / font.units}em`}
                  height={`${height / font.units}em`}
                  aria-hidden="true"
                  focusable="false"
                >
                  <path d={glyph.path} transform="scale(1,-1)" pathLength={1} />
                </svg>
              );
            })}
          </span>
        </Fragment>
      ))}
    </span>
  );
}

/** Real letter outlines draw and fill before the skill disappears. */
export default function SkillsMotion({
  compact = false,
}: {
  compact?: boolean;
}) {
  const stage = useRef<HTMLDivElement>(null);
  const word = useRef<HTMLDivElement>(null);
  const animations = useRef<Animation[]>([]);
  const touch = useRef<{ x: number; y: number } | null>(null);
  const [active, setActive] = useState(0);
  const [enhanced, setEnhanced] = useState(false);
  const [motion, setMotion] = useState(false);
  const [visible, setVisible] = useState(false);
  const [hidden, setHidden] = useState(false);
  const [inventoryOpen, setInventoryOpen] = useState(false);
  const [inspecting, setInspecting] = useState(false);
  const running = motion && visible && !hidden && !inventoryOpen && !inspecting;

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
    if (!motion || inventoryOpen || inspecting || !word.current) return;
    const paths = Array.from(
      word.current.querySelectorAll(".skill-letter path"),
    );
    const build = 900 + Math.max(0, paths.length - 1) * 70;
    const duration = build + 1950;
    const sequence = word.current.animate(
      [
        {
          opacity: 1,
          transform: "translateY(0)",
          filter: "blur(0)",
          offset: 0,
        },
        {
          opacity: 1,
          transform: "translateY(0)",
          filter: "blur(0)",
          offset: (build + 1200) / duration,
        },
        {
          opacity: 0,
          transform: "translateY(-10px)",
          filter: "blur(4px)",
          offset: (build + 1700) / duration,
        },
        {
          opacity: 0,
          transform: "translateY(-10px)",
          filter: "blur(4px)",
          offset: 1,
        },
      ],
      { duration, fill: "both", easing: "ease-in-out" },
    );
    sequence.pause();
    sequence.onfinish = () => setActive((index) => (index + 1) % tools.length);
    const letters = paths.map((path, index) => {
      const effect = path.animate(
        [
          {
            strokeDashoffset: "1",
            strokeOpacity: 0,
            fillOpacity: 0,
            offset: 0,
          },
          {
            strokeDashoffset: "1",
            strokeOpacity: 1,
            fillOpacity: 0,
            offset: 0.08,
          },
          {
            strokeDashoffset: "0",
            strokeOpacity: 1,
            fillOpacity: 0,
            offset: 0.68,
          },
          {
            strokeDashoffset: "0",
            strokeOpacity: 0,
            fillOpacity: 1,
            offset: 1,
          },
        ],
        {
          duration: 900,
          delay: index * 70,
          fill: "both",
          easing: "ease-in-out",
        },
      );
      effect.pause();
      return effect;
    });
    animations.current = [sequence, ...letters];
    return () => {
      sequence.onfinish = null;
      animations.current.forEach((effect) => effect.cancel());
      animations.current = [];
    };
  }, [active, motion, inventoryOpen, inspecting]);

  useEffect(() => {
    animations.current.forEach((effect) =>
      running ? effect.play() : effect.pause(),
    );
  }, [running, active, motion, inventoryOpen, inspecting]);

  const step = (direction: number) => {
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
          <h3 aria-label={tools[active].name}>
            <SkillLetters name={tools[active].name} />
          </h3>
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
          className="skill-step"
          aria-label="Next skill"
          aria-controls="skill-word"
          disabled={!enhanced}
          onClick={() => step(1)}
        >
          <span aria-hidden="true">&rarr;</span>
        </button>
      </div>
      <details
        className="skills-inventory"
        onToggle={(event) => setInventoryOpen(event.currentTarget.open)}
      >
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
