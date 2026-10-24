import { Link } from "react-router-dom";
import { person } from "../content";
import SkillsMotion from "../components/SkillsMotion";
import { ContactCTA, FileHeader, Results } from "../components/UI";
export default function About() {
  return (
    <>
      <FileHeader
        number="B—001"
        title="Behind the files."
        label="BACKGROUND"
        intro="Developer. Researcher. A patient guide to the first few lines of code."
      />
      <div className="about-intro page-section">
        <span className="mono">THE SHORT VERSION</span>
        <p>{person.bio}</p>
        <p className="mono">BASED IN {person.shortLocation}</p>
      </div>
      <div className="about-grid">
        <section className="document-block">
          <span className="mono section-index">01 / EDUCATION</span>
          <h2>
            A foundation in
            <br />
            computer science.
          </h2>
          <h3>B.Sc. Computer Science & Engineering</h3>
          <p>
            BRAC University · Dhaka, Bangladesh
            <br />
            September 2022 – September 2026
            <br />
            CGPA: 3.57 / 4.00
          </p>
          <p>
            Thesis:{" "}
            <Link className="inline-link" to="/projects/wase">
              WASE: Weather-Aware Surgical Evaluation
            </Link>
          </p>
          <h3>Higher Secondary Certificate</h3>
          <p>
            Islamia Degree College · Chattogram
            <br />
            2019 – 2021 · GPA: 5.00 / 5.00
          </p>
        </section>
        <section className="document-block">
          <span className="mono section-index">02 / TEACHING</span>
          <h2>
            Make it work.
            <br />
            Then ask why.
          </h2>
          <p>{person.teaching}</p>
          <p>{person.philosophy}</p>
          <details className="source-note">
            <summary>Teaching interests & approach</summary>
            <p>
              Programming fundamentals, object-oriented programming, data
              structures and algorithms, database management, web development,
              software engineering, and introductory AI and computer vision.
            </p>
            <p>
              I favor hands-on exercises, clear grading rubrics, actionable
              feedback and time for consultation.
            </p>
          </details>
        </section>
      </div>
      <SkillsMotion />
      <div className="about-grid">
        <section className="document-block">
          <span className="mono section-index">04 / SERVICE & PRACTICE</span>
          <h2>Outside the editor.</h2>
          <p>
            As an Associate Sub-Body Member of BRAC University Cultural Club
            from 2024 to 2026, I worked with club leadership on its website and
            member records.
          </p>
          <p>
            I have also taken freelance requests through Upwork and Fiverr:
            clarifying requirements, building features and revising them after
            feedback.
          </p>
        </section>
        <section className="document-block">
          <span className="mono section-index">05 / CONTINUED LEARNING</span>
          <h2>Keep asking.</h2>
          <h3>Associate Data Analyst</h3>
          <p>DataCamp certification</p>
          <p>
            I work across backend development and computer vision, and speak
            Bengali and English.
          </p>
          <Link className="text-link" to="/resume">
            Read the full CV ↗
          </Link>
        </section>
      </div>
      <Results />
      <ContactCTA />
    </>
  );
}
