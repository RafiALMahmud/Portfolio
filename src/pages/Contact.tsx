import { person } from "../content";
import { FileHeader } from "../components/UI";
export default function Contact() {
  return (
    <>
      <FileHeader
        number="C—001"
        title="Open a new case."
        label="CONTACT"
        intro="A project, a research question, or an opportunity to build something useful. Start with a hello."
      />
      <section className="contact-page page-section">
        <div className="contact-main">
          <span className="mono">DIRECT LINE / EMAIL</span>
          <a className="contact-email" href={"mailto:" + person.email}>
            {person.email}
            <span aria-hidden="true">↗</span>
          </a>
          <p>Every good collaboration starts with a conversation.</p>
        </div>
        <div className="contact-details">
          <div>
            <span className="mono">LOCATION</span>
            <p>{person.location}</p>
          </div>
          <div>
            <span className="mono">PHONE</span>
            <a href={person.phoneHref}>{person.phone} ↗</a>
          </div>
          <div>
            <span className="mono">ELSEWHERE</span>
            <a href={person.github} target="_blank" rel="noreferrer">
              GitHub ↗
            </a>
            <a href={person.linkedin} target="_blank" rel="noreferrer">
              LinkedIn ↗
            </a>
          </div>
        </div>
        <a className="button" href="/cv.pdf" download>
          Download CV ↓
        </a>
        <div className="contact-end mono">
          <span>END OF FILE</span>
          <span>THE NEXT ONE STARTS WITH YOU.</span>
        </div>
      </section>
    </>
  );
}
