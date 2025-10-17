'use client'

import { useEffect, useRef } from 'react'

export default function About() {
  const sectionRef = useRef<HTMLElement>(null)

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible')
          }
        })
      },
      { threshold: 0.1 }
    )

    if (sectionRef.current) {
      observer.observe(sectionRef.current)
    }

    return () => observer.disconnect()
  }, [])

  return (
    <section id="about" ref={sectionRef} className="section-padding animate-on-scroll">
      <div className="container-max-width">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div>
            <h2 className="text-section font-section font-sans text-text-primary mb-6">
              About Me
            </h2>
            <div className="space-y-6 text-body font-body text-text-secondary leading-relaxed">
              <p>
                I'm a detail-oriented and motivated software developer with a strong foundation in backend and full-stack development. 
                I specialize in building scalable web applications using Laravel, PHP, Python, and MySQL. 
                My expertise spans from designing RESTful APIs and efficient database architectures to implementing secure authentication systems and role-based access control.
              </p>
              <p>
                I thrive in fast-paced, agile environments where I can leverage my analytical problem-solving skills to deliver high-quality, 
                reliable software solutions. Whether it's engineering a secure job portal with RBAC, developing deep learning pipelines for medical imaging, 
                or building full-stack e-commerce platforms, I approach each project with meticulous attention to detail and a commitment to clean, maintainable code.
              </p>
              <p>
                I hold a B.Sc. in Computer Science and Engineering from BRAC University (CGPA: 3.54) and an HSC from Islamia Degree College. 
                I'm passionate about continuous learning, staying updated with emerging technologies, and contributing to the developer community. 
                I'm always eager for new challenges and opportunities to grow professionally.
              </p>
            </div>
          </div>
          
          <div className="space-y-6">
            <div className="bg-surface p-6 rounded-lg border border-surface hover:border-accent transition-colors duration-300">
              <h3 className="text-xl font-sans font-semibold text-text-primary mb-3">
                Education
              </h3>
              <div className="space-y-3 text-body font-body text-text-secondary">
                <div>
                  <div className="font-semibold text-text-primary">B.Sc. Computer Science & Engineering</div>
                  <div>BRAC University, Bangladesh</div>
                  <div className="text-sm">CGPA: 3.54</div>
                </div>
                <div>
                  <div className="font-semibold text-text-primary">Higher Secondary Certificate</div>
                  <div>Islamia Degree College, Chittagong</div>
                  <div className="text-sm">Grade: 5/5</div>
                </div>
              </div>
            </div>
            
            <div className="bg-surface p-6 rounded-lg border border-surface hover:border-accent transition-colors duration-300">
              <h3 className="text-xl font-sans font-semibold text-text-primary mb-3">
                GitHub Activity
              </h3>
              <div className="space-y-3">
                <div className="flex justify-between items-center">
                  <span className="text-text-secondary">Total Repositories</span>
                  <span className="text-accent font-semibold">14+</span>
                </div>
                <div className="flex justify-between items-center">
                  <span className="text-text-secondary">Contributions (1 year)</span>
                  <span className="text-accent font-semibold">161+</span>
                </div>
                <a
                  href="https://github.com/RafiALMahmud"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="inline-flex items-center gap-2 text-accent hover:text-accent/80 transition-colors duration-300 font-sans font-medium mt-2"
                >
                  <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                  </svg>
                  View on GitHub
                </a>
              </div>
            </div>
            
            <div className="bg-surface p-6 rounded-lg border border-surface hover:border-accent transition-colors duration-300">
              <h3 className="text-xl font-sans font-semibold text-text-primary mb-3">
                Location & Contact
              </h3>
              <div className="space-y-2 text-body font-body text-text-secondary">
                <div>
                  <div className="font-semibold text-text-primary">📍 Based in Dhaka</div>
                  <div className="text-sm">House 27, Road No 3, Block-D, Banasree, Rampura</div>
                </div>
                <div>
                  <div className="font-semibold text-text-primary">📞 Phone</div>
                  <div className="text-sm">+8801974616402</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
