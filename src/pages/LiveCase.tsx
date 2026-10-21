import { Link } from "react-router-dom";
import { liveSites } from "../data/liveSites";
import type { PageProps } from "../routing";
import {
  Arrow,
  BookingFlow,
  CaseArt,
  FileHeader,
  Tags,
} from "../components/UI";
export default function LiveCase({ path }: PageProps) {
  const site = liveSites.find((s) => path === "/live/" + s.slug)!;
  return (
    <>
      <FileHeader
        number={"L—" + site.number}
        title={site.title}
        label="LIVE"
        intro={site.description}
        stamp="LIVE"
      >
        {site.title.toUpperCase()}
      </FileHeader>
      <div className="case-cover">
        {site.screenshots[0] ? (
          <img
            src={site.screenshots[0].src}
            alt={site.screenshots[0].alt}
            width="1200"
            height="750"
          />
        ) : (
          <CaseArt kind={site.slug} large />
        )}
      </div>
      <div className="case-layout">
        <aside className="case-sidebar">
          <span className="mono">FILE DETAILS</span>
          <dl>
            <dt>CLASSIFICATION</dt>
            <dd>{site.category}</dd>
            <dt>STATUS</dt>
            <dd>Live platform</dd>
            <dt>STACK</dt>
            <dd>
              <Tags items={site.stack} />
            </dd>
          </dl>
          <a
            className="button primary"
            href={site.url}
            target="_blank"
            rel="noreferrer"
          >
            Visit site <Arrow />
          </a>
          <nav aria-label="Case sections">
            <a href="#my-role">My role</a>
            {site.sections.map((s, i) => (
              <a key={s.title} href={"#section-" + i}>
                {s.title}
              </a>
            ))}
            <a href="#gallery">Screenshots</a>
          </nav>
        </aside>
        <div className="case-body">
          <section id="my-role">
            <span className="mono section-index">PERSONAL CONTRIBUTION</span>
            <h2>My role.</h2>
            <p>{site.role}</p>
          </section>
          {site.sections.map((s, i) => (
            <section id={"section-" + i} key={s.title} className="reveal">
              <h2>{s.title}.</h2>
              {s.body && <p>{s.body}</p>}
              {s.title === "Booking flow" && <BookingFlow />}
              {s.items && (
                <ul className="document-list">
                  {s.items.map((item) => (
                    <li key={item}>{item}</li>
                  ))}
                </ul>
              )}
            </section>
          ))}
          <section id="gallery">
            <h2>Field evidence.</h2>
            {site.screenshots.length > 0 ? (
              site.screenshots.map((shot) => (
                <figure className="screenshot" key={shot.src}>
                  <a href={shot.src} target="_blank" rel="noreferrer">
                    <img
                      src={shot.src}
                      alt={shot.alt}
                      width="1200"
                      height="750"
                      loading="lazy"
                    />
                  </a>
                  <figcaption>{shot.caption}</figcaption>
                </figure>
              ))
            ) : (
              <div className="placeholder">
                <span className="mono">SCREENSHOT / AWAITING CAPTURE</span>
                <p>
                  {site.slug === "sportevo"
                    ? "The 3D stadium hero and the owner schedule belong here."
                    : "The magazine homepage and editorial interface belong here."}
                </p>
              </div>
            )}
          </section>
          <div className="page-actions">
            <a
              className="button primary"
              href={site.url}
              target="_blank"
              rel="noreferrer"
            >
              Visit site <Arrow />
            </a>
            <Link className="button" to="/live">
              ← Back to live cases
            </Link>
          </div>
        </div>
      </div>
    </>
  );
}
