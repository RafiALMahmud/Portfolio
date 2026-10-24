import { Link } from "react-router-dom";
import { home, person } from "../content";
import { projects } from "../data/projects";
import { liveSites } from "../data/liveSites";
import CaseFile from "../components/CaseFile";
import FormingHeadline from "../components/FormingHeadline";
import SkillsMotion from "../components/SkillsMotion";
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
            <FormingHeadline />
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
          <CaseFile />
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
      <SkillsMotion compact />
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
