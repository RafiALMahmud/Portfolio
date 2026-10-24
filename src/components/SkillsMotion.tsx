import { useEffect, useRef, useState, type CSSProperties } from "react";
import { Link } from "react-router-dom";
import { skills } from "../content";
import { Arrow } from "./UI";

const preview = [
  ["Backend & APIs", [...skills.Languages, ...skills.Backend]],
  ["Web & data", [...skills.Frontend, ...skills.Databases]],
  ["Research & tooling", [...skills["Data & ML"], ...skills.Tooling]],
] as [string, string[]][];

/** Native scrolling without JavaScript; pausable CSS tapes with it. */
export default function SkillsMotion({
  compact = false,
}: {
  compact?: boolean;
}) {
  const section = useRef<HTMLElement>(null);
  const [ready, setReady] = useState(false);
  const [visible, setVisible] = useState(false);
  const [hidden, setHidden] = useState(false);
  const [paused, setPaused] = useState(false);
  const groups = compact ? preview : Object.entries(skills);

  useEffect(() => {
    const motion = window.matchMedia("(prefers-reduced-motion: reduce)");
    const configure = () => setReady(!motion.matches);
    const visibility = () => setHidden(document.hidden);
    const observer = new IntersectionObserver(([entry]) =>
      setVisible(entry.isIntersecting),
    );
    if (section.current) observer.observe(section.current);
    configure();
    visibility();
    motion.addEventListener("change", configure);
    document.addEventListener("visibilitychange", visibility);
    return () => {
      observer.disconnect();
      motion.removeEventListener("change", configure);
      document.removeEventListener("visibilitychange", visibility);
    };
  }, []);

  return (
    <section
      id="skills"
      ref={section}
      className={`skills-motion ${compact ? "skills-preview" : "skills-section"}`}
      data-ready={ready}
      data-running={ready && visible && !hidden && !paused}
      data-paused={paused}
      aria-labelledby="skills-title"
    >
      <div className="skills-motion-heading">
        <div>
          <span className="mono section-index">03 / THE TOOLKIT</span>
          <h2 id="skills-title">
            {compact ? "The working toolkit." : "Tools of the trade."}
          </h2>
        </div>
        <button
          type="button"
          className="skills-motion-toggle mono"
          disabled={!ready}
          aria-pressed={paused}
          onClick={() => setPaused((value) => !value)}
        >
          <span aria-hidden="true">{ready && !paused ? "Ⅱ" : "▷"}</span>
          {!ready ? "Motion off" : paused ? "Resume motion" : "Pause motion"}
        </button>
      </div>
      <div className="skills-lanes">
        {groups.map(([group, items], index) => (
          <div className="skill-lane" key={group}>
            <h3>
              <span className="mono" aria-hidden="true">
                {String(index + 1).padStart(2, "0")}
              </span>
              {group}
            </h3>
            <div
              className="skill-lane-window"
              tabIndex={0}
              role="region"
              aria-label={`${group} technologies`}
            >
              <div
                className="skill-tape"
                style={
                  {
                    "--skill-duration": `${38 + index * 5}s`,
                    "--skill-direction": index % 2 ? "reverse" : "normal",
                  } as CSSProperties
                }
              >
                {[0, 1, 2, 3].map((copy) => (
                  <ul
                    className="skill-tape-list"
                    key={copy}
                    aria-hidden={copy ? true : undefined}
                    aria-label={copy ? undefined : `${group} skills`}
                  >
                    {items.map((skill) => (
                      <li key={skill}>
                        <span className="skill-pin" aria-hidden="true" />
                        {skill}
                      </li>
                    ))}
                  </ul>
                ))}
              </div>
            </div>
          </div>
        ))}
      </div>
      {compact && (
        <Link className="text-link skills-detail-link" to="/about#skills">
          Explore all skills <Arrow />
        </Link>
      )}
    </section>
  );
}
