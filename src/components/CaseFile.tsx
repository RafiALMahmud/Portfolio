import { useEffect, useRef, useState } from "react";
import { Link } from "react-router-dom";
import DeskLoupe from "./DeskLoupe";

/** One short assembly; the server-rendered file is always complete. */
export default function CaseFile() {
  const stage = useRef<HTMLDivElement>(null);
  const [phase, setPhase] = useState<"ready" | "building" | "complete">(
    "ready",
  );

  useEffect(() => {
    const preference = window.matchMedia("(prefers-reduced-motion: reduce)");
    let observer: IntersectionObserver | undefined;
    const configure = () => {
      observer?.disconnect();
      if (preference.matches) {
        setPhase("complete");
        return;
      }
      observer = new IntersectionObserver(
        ([entry]) => {
          if (entry.isIntersecting && entry.intersectionRatio >= 0.6) {
            setPhase("building");
            observer?.disconnect();
          }
        },
        { threshold: 0.6 },
      );
      if (stage.current) observer.observe(stage.current);
    };
    configure();
    preference.addEventListener("change", configure);
    return () => {
      observer?.disconnect();
      preference.removeEventListener("change", configure);
    };
  }, []);

  useEffect(() => {
    if (phase !== "building") return;
    const timer = window.setTimeout(() => setPhase("complete"), 4000);
    return () => window.clearTimeout(timer);
  }, [phase]);

  return (
    <div className="case-file">
      <div
        className="dossier-stage"
        data-tilt
        ref={stage}
        data-assembly={phase}
      >
        <div className="dossier" aria-label="Developer profile case file">
          <div className="dossier-tab mono">SUBJECT / 001</div>
          <div className="dossier-inner">
            <div className="dossier-heading mono">
              <span>DEVELOPER PROFILE</span>
              <span>CONFIDENTIAL*</span>
            </div>
            <div className="dossier-graphic" aria-hidden="true">
              <svg viewBox="0 0 320 235" fill="none">
                <defs>
                  <pattern
                    id="grid"
                    width="20"
                    height="20"
                    patternUnits="userSpaceOnUse"
                  >
                    <path
                      d="M20 0H0V20"
                      stroke="currentColor"
                      strokeWidth=".3"
                    />
                  </pattern>
                </defs>
                <rect
                  className="dossier-grid"
                  width="320"
                  height="235"
                  fill="url(#grid)"
                />
                <circle
                  cx="160"
                  cy="115"
                  r="77"
                  stroke="currentColor"
                  strokeDasharray="3 5"
                />
                <circle
                  className="dossier-draw"
                  pathLength="1"
                  cx="160"
                  cy="115"
                  r="58"
                  stroke="currentColor"
                />
                <path
                  className="dossier-draw dossier-cross"
                  pathLength="1"
                  d="M80 115h160M160 35v160M103 58l114 114M103 172L217 58"
                  stroke="currentColor"
                  strokeWidth=".4"
                />
                <rect
                  className="dossier-draw dossier-frame"
                  pathLength="1"
                  x="119"
                  y="75"
                  width="82"
                  height="80"
                  stroke="#c1121f"
                  strokeWidth="2"
                />
                <path
                  d="M114 75h-8v15M206 75h8v15M114 155h-8v-15M206 155h8v-15"
                  stroke="#c1121f"
                />
                <text
                  className="dossier-initial"
                  x="160"
                  y="130"
                  textAnchor="middle"
                  fill="#ede6da"
                  stroke="none"
                  fontFamily="Georgia,serif"
                  fontSize="43"
                >
                  R.
                </text>
                <path d="M20 197h70M230 35h70" stroke="currentColor" />
                <text
                  x="20"
                  y="212"
                  fill="currentColor"
                  stroke="none"
                  fontSize="7"
                  fontFamily="monospace"
                >
                  TRACE / ANALYZE / BUILD
                </text>
                <text
                  x="240"
                  y="28"
                  fill="currentColor"
                  stroke="none"
                  fontSize="7"
                  fontFamily="monospace"
                >
                  IDENTITY VERIFIED
                </text>
              </svg>
              <div className="dossier-stamp">
                OPEN TO
                <br />
                NEW CASES
              </div>
            </div>
            <dl className="dossier-facts">
              <div>
                <dt>NAME</dt>
                <dd>Rafi Al Mahmud</dd>
              </div>
              <div>
                <dt>LOCATION</dt>
                <dd>Dhaka / Chattogram, BD</dd>
              </div>
              <div>
                <dt>FOCUS</dt>
                <dd>Backend / Web / Research</dd>
              </div>
            </dl>
            <div className="dossier-note mono">
              * NOTHING TO HIDE. PLENTY TO FIND.
            </div>
          </div>
          <span className="paperclip" aria-hidden="true" />
          <Link className="dossier-file-link" to="/about">
            <span className="sr-only">Open developer profile case file</span>
          </Link>
        </div>
        <DeskLoupe />
      </div>
    </div>
  );
}
