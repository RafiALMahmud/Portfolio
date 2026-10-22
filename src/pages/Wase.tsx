import { Link } from "react-router-dom";
import { research } from "../content";
import { FileHeader, Tags } from "../components/UI";
export default function Wase() {
  return (
    <>
      <div className="research-header">
        <FileHeader
          number="P—003"
          title={research.title}
          label="PROJECTS"
          stamp="IN PREPARATION"
        >
          <span>WASE</span>
        </FileHeader>
      </div>
      <div className="case-layout">
        <aside className="case-sidebar">
          <span className="mono">RESEARCH FILE</span>
          <dl>
            <dt>TYPE</dt>
            <dd>B.Sc. thesis · BRAC University</dd>
            <dt>SUPERVISOR</dt>
            <dd>{research.supervisor}</dd>
            <dt>MANUSCRIPT</dt>
            <dd>{research.status}</dd>
            <dt>TOOLS</dt>
            <dd>
              <Tags
                items={["Python", "PyTorch", "YOLOv8-Nano", "DCP", "EMA"]}
              />
            </dd>
          </dl>
          <nav aria-label="Research sections">
            <a href="#demo">Live demonstration</a>
            <a href="#problem">The problem</a>
            <a href="#approach">The approach</a>
            <a href="#data">Data & evaluation</a>
            <a href="#results">Results</a>
            <a href="#figures">Original figures</a>
            <a href="#limitations">Limitations</a>
          </nav>
        </aside>
        <div className="case-body">
          <p className="research-subtitle">{research.subtitle}</p>
          <div className="research-hero">
            <div>
              <span className="mono">REPORTED IN THE LATEST CV</span>
              <strong>8–12%</strong>
              <p>mAP@50 gain over the baseline.</p>
            </div>
            <div>
              <span className="mono">REPORTED TEST SETUP</span>
              <strong>~17 ms</strong>
              <p>Inference time. Hardware and protocol affect timing.</p>
            </div>
          </div>
          <section id="demo">
            <h2>WASE in motion.</h2>
            <p>A live demonstration on foggy road footage.</p>
            <figure className="research-video">
              <video
                controls
                playsInline
                preload="none"
                poster="/images/wase/live-demo.webp"
                width={1102}
                height={490}
                aria-label="WASE live demonstration on foggy road footage"
                aria-describedby="wase-video-caption"
              >
                <source src="/videos/wase-live.mp4" type="video/mp4" />
                Your browser does not support embedded video. Use the download
                link below.
              </video>
              <figcaption id="wase-video-caption">
                A 15-second silent clip of a drive through fog. Trees and road
                markings fade into the mist ahead; the WASE Live overlay
                displays the running frame rate.
              </figcaption>
            </figure>
            <a className="text-link" href="/videos/wase-live.mp4" download>
              Download video (MP4, 1.8 MB)
            </a>
          </section>
          <section id="problem">
            <h2>The problem.</h2>
            <p>{research.problem}</p>
          </section>
          <section id="approach">
            <h2>A more careful teacher.</h2>
            <p>{research.approach}</p>
            <ol className="document-list">
              {research.method.map(([title, text]) => (
                <li key={title}>
                  <strong className="text-paper">{title}.</strong> {text}
                </li>
              ))}
            </ol>
            <figure className="research-figure">
              <a
                href={research.figures[0].src}
                target="_blank"
                rel="noreferrer"
              >
                <img
                  src={research.figures[0].src}
                  width={1450}
                  height={642}
                  alt={research.figures[0].alt}
                  loading="lazy"
                />
              </a>
              <figcaption>{research.figures[0].caption}</figcaption>
            </figure>
          </section>
          <section id="data">
            <h2>Data & evaluation.</h2>
            <p>{research.datasets}</p>
            <p>{research.protocol}</p>
            <p>
              Training adaptation uses zero additional manual target-domain
              annotations. The evaluation and manually retrained comparison use
              reference annotations.
            </p>
          </section>
          <section id="results">
            <h2>The temporal evidence.</h2>
            <p>
              Tables 5.3–5.5 report mean time-to-first-detection of 0.069 frames
              for WASE versus 0.384 for the manually retrained baseline, an
              approximately 82% reduction. Temporal Stability Score rises from
              0.315 to 0.551. These are thesis temporal metrics; the mAP and
              inference figures above are separately reported in the CV.
            </p>
            <div
              className="table-scroll"
              tabIndex={0}
              role="region"
              aria-label="Scrollable thesis evaluation results"
            >
              <table className="metric-table">
                <caption>
                  CSE400.pdf · Tables 5.3–5.5 · three ACDC sequences
                </caption>
                <thead>
                  <tr>
                    <th scope="col">Condition</th>
                    <th scope="col">
                      TTFD ↓<br />
                      (frames)
                    </th>
                    <th scope="col">TSS ↑</th>
                    <th scope="col">First-frame ↑</th>
                    <th scope="col">
                      Never detected ↓<br />
                      (total)
                    </th>
                  </tr>
                </thead>
                <tbody>
                  {research.temporalResults.map((r) => (
                    <tr key={r.condition}>
                      <th scope="row">{r.condition}</th>
                      <td>{r.ttfd}</td>
                      <td>{r.tss}</td>
                      <td>{r.first}</td>
                      <td>{r.missed}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
            <p className="mt-5">
              TTFD: mean Time-to-First-Detection. TSS: Temporal Stability Score.
              First-frame percentage measures tracks detected immediately. The
              final column reports total never-detected tracks.
            </p>
          </section>
          <section id="figures">
            <h2>From the thesis.</h2>
            {research.figures.slice(1).map((f) => (
              <figure className="research-figure" key={f.src}>
                <a href={f.src} target="_blank" rel="noreferrer">
                  <img
                    src={f.src}
                    alt={f.alt}
                    width={f.width}
                    height={f.height}
                    loading="lazy"
                  />
                </a>
                <figcaption>{f.caption}</figcaption>
              </figure>
            ))}
          </section>
          <section id="limitations">
            <h2>Reading the evidence carefully.</h2>
            <p>{research.limitations}</p>
            <details className="source-note">
              <summary>Source & manuscript status</summary>
              <p>
                Research details and figures come from the supplied CSE400.pdf
                thesis, dated June 2026. The latest CV reports the mAP gain and
                inference time, and describes the manuscript as in preparation
                for ICCV. A public WASE code repository has not been identified.
              </p>
            </details>
          </section>
          <section>
            <h2>Related exploration.</h2>
            <p>{research.related}</p>
          </section>
          <div className="prev-next">
            <Link to="/projects/l-essence">
              <span className="mono">← PREVIOUS FILE</span>L’Essence
            </Link>
            <Link to="/projects/mindwell">
              <span className="mono">NEXT FILE →</span>MindWell
            </Link>
          </div>
        </div>
      </div>
    </>
  );
}
