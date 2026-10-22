import { Link } from "react-router-dom";
import type { ReactNode } from "react";
import { results } from "../content";
import type { LiveSite } from "../data/liveSites";
import type { Project } from "../data/projects";

export function Arrow({ diagonal = false }: { diagonal?: boolean }) {
  return <span aria-hidden="true">{diagonal ? "↗" : "↗"}</span>;
}
export function Stamp({ children }: { children: ReactNode }) {
  return <span className="stamp">{children}</span>;
}
export function Tags({ items }: { items: string[] }) {
  return (
    <ul className="tags" aria-label="Technologies">
      {items.map((t) => (
        <li key={t}>{t}</li>
      ))}
    </ul>
  );
}
export function FileHeader({
  number,
  title,
  label,
  intro,
  stamp,
  children,
}: {
  number: string;
  title: string;
  label: string;
  intro?: string;
  stamp?: string;
  children?: ReactNode;
}) {
  return (
    <header className="file-header halftone">
      <div className="file-tab">
        PERSONAL ARCHIVE <span>／</span> {label}
      </div>
      <div className="file-meta">
        <span>FILE NO. {number}</span>
        <span lang="ja" aria-hidden="true">
          {label === "CONTACT"
            ? "連絡"
            : label === "BACKGROUND"
              ? "経歴"
              : "事件"}
        </span>
      </div>
      <div className="title-row">
        <h1>{title}</h1>
        {stamp && <Stamp>{stamp}</Stamp>}
      </div>
      {intro && <p className="intro">{intro}</p>}
      <nav className="breadcrumbs" aria-label="Breadcrumb">
        <Link to="/">FILE</Link>
        <span aria-hidden="true">/</span>
        <span>{label}</span>
        {children && (
          <>
            <span aria-hidden="true">/</span>
            {children}
          </>
        )}
      </nav>
    </header>
  );
}
export function Results() {
  return (
    <section className="results" aria-label="Evidence">
      <div className="evidence-label">
        THE EVIDENCE <span aria-hidden="true">↓</span>
      </div>
      <div className="results-grid">
        {results.map((r, i) => (
          <div className="stat" key={r.label}>
            <span className="mono">EXHIBIT {String.fromCharCode(65 + i)}</span>
            <div>
              <strong>{r.value}</strong>
              <span className="mono">{r.label}</span>
            </div>
            <p>{r.note}</p>
          </div>
        ))}
      </div>
    </section>
  );
}
export function CaseArt({
  kind,
  large = false,
}: {
  kind: string;
  large?: boolean;
}) {
  return (
    <div
      className={`case-art ${kind} ${large ? "large" : ""}`}
      aria-hidden="true"
    >
      {kind === "mangostudents" ? (
        <>
          <div className="art-browser">
            <span>m.</span>
            <i />
            <i />
            <i />
          </div>
          <div className="mango-layout">
            <span className="art-category">A SPACE FOR STUDENT VOICES</span>
            <strong>
              Stories worth
              <br />
              <em>telling.</em>
            </strong>
            <div className="art-columns">
              <i />
              <i />
              <i />
            </div>
          </div>
          <span className="art-foot">WORDS. ART. CURIOSITY.</span>
        </>
      ) : (
        <>
          <div className="art-browser">
            <span>
              SPORT<span className="outlined">EVO</span>
            </span>
            <i />
            <i />
          </div>
          <div className="pitch">
            <div className="pitch-center" />
            <div className="pitch-goal left" />
            <div className="pitch-goal right" />
          </div>
          <div className="sport-copy">
            YOUR FIELD.
            <br />
            <em>YOUR GAME.</em>
          </div>
          <span className="art-foot">FIND A FIELD. MAKE IT HAPPEN.</span>
        </>
      )}
    </div>
  );
}
export function LiveCard({ site }: { site: LiveSite }) {
  return (
    <article className="panel live-card spatial-card reveal" data-tilt>
      <span className="panel-glare" aria-hidden="true" />
      <Link
        className="card-visual"
        to={"/live/" + site.slug}
        tabIndex={-1}
        aria-hidden="true"
      >
        {site.screenshots[0] ? (
          <img
            src={site.screenshots[0].src}
            srcSet={`/images/${site.slug}-480.webp 480w, /images/${site.slug}-800.webp 800w, /images/${site.slug}.webp 1200w`}
            sizes="(min-width: 768px) 480px, calc(100vw - 78px)"
            width="1200"
            height="750"
            loading="lazy"
            alt=""
          />
        ) : (
          <CaseArt kind={site.slug} />
        )}
      </Link>
      <div className="card-content">
        <div className="card-eyebrow">
          <span>LIVE CASE / {site.number}</span>
          <Stamp>LIVE</Stamp>
        </div>
        <h3>
          <Link to={"/live/" + site.slug}>{site.title}</Link>
        </h3>
        <p>{site.description}</p>
        <Tags items={site.stack.slice(0, 4)} />
        <div className="card-actions">
          <Link to={"/live/" + site.slug}>
            Open case file <Arrow />
          </Link>
          <a href={site.url} target="_blank" rel="noreferrer">
            Visit site <Arrow diagonal />
          </a>
        </div>
      </div>
    </article>
  );
}
export function ProjectCard({
  project,
  index,
}: {
  project: Project;
  index: number;
}) {
  return (
    <article className="panel project-card spatial-card reveal" data-tilt>
      <span className="panel-glare" aria-hidden="true" />
      <div className="card-eyebrow">
        <span>FILE / {String(index + 3).padStart(3, "0")}</span>
        <span>
          {project.slug === "wase"
            ? "RESEARCH"
            : project.language.toUpperCase()}
        </span>
      </div>
      <h3>
        <Link to={"/projects/" + project.slug}>
          {project.title}
          <Arrow />
        </Link>
      </h3>
      {project.description && <p>{project.description}</p>}
      <Tags items={project.stack.slice(0, 3)} />
      <Link className="text-link" to={"/projects/" + project.slug}>
        Read the file <Arrow />
      </Link>
    </article>
  );
}
export function SectionHeading({
  index,
  title,
  to,
  link,
}: {
  index: string;
  title: string;
  to?: string;
  link?: string;
}) {
  return (
    <div className="section-heading">
      <div>
        <span className="section-index mono">{index} / SELECTED WORK</span>
        <h2>{title}</h2>
      </div>
      {to && (
        <Link className="text-link" to={to}>
          {link} <Arrow />
        </Link>
      )}
    </div>
  );
}
export function ContactCTA() {
  return (
    <section className="contact-cta reveal">
      <div>
        <span className="mono">HAVE SOMETHING WORTH SOLVING?</span>
        <h2>Open a new case.</h2>
      </div>
      <Link className="button primary" to="/contact">
        Let’s talk <Arrow />
      </Link>
    </section>
  );
}
export function BookingFlow() {
  return (
    <figure className="flow">
      <figcaption className="mono">REQUEST → REVIEW → RESOLUTION</figcaption>
      <ol>
        {[
          "Browse complex",
          "Choose field & slot",
          "Send request",
          "Owner reviews",
          "Accept or reject",
        ].map((s) => (
          <li key={s}>{s}</li>
        ))}
      </ol>
    </figure>
  );
}
