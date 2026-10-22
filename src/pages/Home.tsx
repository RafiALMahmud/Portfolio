import { Link } from "react-router-dom";
import { home, person } from "../content";
import { projects } from "../data/projects";
import { liveSites } from "../data/liveSites";
import DeskLoupe from "../components/DeskLoupe";
import {
  Arrow,
  ContactCTA,
  LiveCard,
  ProjectCard,
  Results,
  SectionHeading,
} from "../components/UI";

export default function Home() {
  return (
    <>
      <section className="hero halftone">
        <div className="hero-topline mono">
          <span>INDEPENDENT DEVELOPER / PERSONAL ARCHIVE</span>
          <span>
            EST. 2026 <span className="small-cross">+</span>
          </span>
        </div>
        <div className="hero-grid">
          <div className="hero-copy">
            <p className="eyebrow mono">
              <span className="red-line" /> CASE FILE · RAFI AL MAHMUD
            </p>
            <h1>
              Everybody’s
              <br />
              code <em>lies.</em>
              <br />I read the logs<span className="period">.</span>
            </h1>
            <p className="hero-subline">{home.subline}</p>
            <div className="hero-actions">
              <Link className="button primary" to="/live">
                Live sites <Arrow />
              </Link>
              <Link className="button" to="/projects">
                Projects <Arrow />
              </Link>
            </div>
            <div className="hero-secondary">
              <a href="/cv.pdf" download>
                Download CV <span aria-hidden="true">↓</span>
              </a>
              <a href={person.github} target="_blank" rel="noreferrer">
                GitHub <Arrow />
              </a>
            </div>
          </div>
          <div className="dossier-stage" data-tilt>
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
                    <rect width="320" height="235" fill="url(#grid)" />
                    <circle
                      cx="160"
                      cy="115"
                      r="77"
                      stroke="currentColor"
                      strokeDasharray="3 5"
                    />
                    <circle cx="160" cy="115" r="58" stroke="currentColor" />
                    <path
                      d="M80 115h160M160 35v160M103 58l114 114M103 172L217 58"
                      stroke="currentColor"
                      strokeWidth=".4"
                    />
                    <rect
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
            </div>
            <DeskLoupe />
          </div>
        </div>
        <div className="hero-bottom mono">
          <span>{person.shortLocation}</span>
          <span>
            SCROLL TO EXAMINE THE EVIDENCE <span aria-hidden="true">↓</span>
          </span>
        </div>
      </section>
      <section className="work-section">
        <SectionHeading
          index="01"
          title="Live cases."
          to="/live"
          link="View all live sites"
        />
        <div className="grid gap-6 md:grid-cols-2">
          {liveSites.map((site) => (
            <LiveCard key={site.slug} site={site} />
          ))}
        </div>
      </section>
      <section className="work-section">
        <SectionHeading
          index="02"
          title="From the case files."
          to="/projects"
          link="Browse the archive"
        />
        <div className="grid gap-5 md:grid-cols-3">
          {projects.slice(0, 3).map((p, i) => (
            <ProjectCard key={p.slug} project={p} index={i} />
          ))}
        </div>
      </section>
      <Results />
      <section className="field-note reveal">
        <span className="mono">A NOTE FROM THE DESK</span>
        <p>
          Good systems start with the right questions.
          <br />
          Then come the{" "}
          <button
            className="redaction"
            aria-label="Reveal the word details"
            onClick={(e) => e.currentTarget.classList.toggle("revealed")}
          >
            details
          </button>
          .
        </p>
        <Link className="text-link" to="/about">
          Meet the person behind the files <Arrow />
        </Link>
      </section>
      <ContactCTA />
    </>
  );
}
