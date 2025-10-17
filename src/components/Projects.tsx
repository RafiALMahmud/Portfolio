'use client'

import { useEffect, useRef } from 'react'

const projects = [
  {
    title: 'Job Portal',
    description: 'A secure, multi-user job portal platform featuring role-based access control (RBAC) for three distinct user types: job seekers, employers, and administrators. Designed with a normalized MySQL database schema for efficient data integrity. Includes comprehensive admin dashboard with full CRUD functionality and secure Laravel authentication.',
    technologies: ['Laravel', 'PHP', 'MySQL', 'HTML5', 'CSS3'],
    liveUrl: '#',
    repoUrl: 'https://github.com/RafiALMahmud'
  },
  {
    title: 'BUCuC - BRAC University Community Club',
    description: 'A comprehensive community platform for BRAC University featuring event management, member profiles, and community engagement tools. Contributed to the backend architecture and API development ensuring scalable and efficient data handling. The platform connects university members and facilitates seamless community interactions.',
    technologies: ['Laravel', 'PHP', 'MySQL', 'REST API', 'HTML5', 'CSS3'],
    liveUrl: 'https://www.bucuc.org/',
    repoUrl: 'https://github.com/Rudian420/BUCuC-WEB-New'
  },
  {
    title: 'E-Commerce Web Application',
    description: 'A full-stack e-commerce platform with complete product and inventory management capabilities for administrators. Implements core customer-facing features including user authentication, shopping cart functionality, and order processing workflows. Optimized database schema for complex relationships.',
    technologies: ['Laravel', 'PHP', 'MySQL', 'HTML5', 'CSS3'],
    liveUrl: '#',
    repoUrl: 'https://github.com/RafiALMahmud'
  }
]

export default function Projects() {
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
    <section id="projects" ref={sectionRef} className="section-padding animate-on-scroll">
      <div className="container-max-width">
        <div className="text-center mb-16">
          <h2 className="text-section font-section font-sans text-text-primary mb-4">
            Featured Projects
          </h2>
          <p className="text-body font-body text-text-secondary max-w-2xl mx-auto">
            A showcase of my backend development, API design, and machine learning expertise demonstrated through real-world projects
          </p>
        </div>
        
        <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
          {projects.map((project, index) => (
            <div 
              key={project.title}
              className="bg-surface p-6 rounded-lg border border-surface hover:border-accent transition-all duration-300 hover:shadow-lg hover:shadow-accent/10 card-hover group"
              style={{ animationDelay: `${index * 200}ms` }}
            >
              <div className="mb-4">
                <h3 className="text-project font-project font-sans text-text-primary mb-3 group-hover:text-accent transition-colors duration-300">
                  {project.title}
                </h3>
                <p className="text-body font-body text-text-secondary leading-relaxed">
                  {project.description}
                </p>
              </div>
              
              <div className="mb-6">
                <div className="flex flex-wrap gap-2">
                  {project.technologies.map((tech) => (
                    <span
                      key={tech}
                      className="px-2 py-1 bg-background text-tech font-mono text-accent rounded border border-surface hover:border-accent transition-colors duration-300"
                    >
                      {tech}
                    </span>
                  ))}
                </div>
              </div>
              
              <div className="flex flex-col sm:flex-row gap-3">
                <a
                  href={project.liveUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex-1 px-4 py-2 bg-accent text-background font-sans font-medium text-center rounded-lg hover:bg-accent/90 transition-colors duration-300"
                >
                  Live Demo
                </a>
                <a
                  href={project.repoUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex-1 px-4 py-2 border border-accent text-accent font-sans font-medium text-center rounded-lg hover:bg-accent hover:text-background transition-all duration-300"
                >
                  Source Code
                </a>
              </div>
            </div>
          ))}
        </div>
        
        <div className="mt-16 text-center">
          <div className="bg-surface p-8 rounded-lg border border-surface">
            <h3 className="text-xl font-sans font-semibold text-text-primary mb-4">
              More Projects
            </h3>
            <p className="text-body font-body text-text-secondary mb-6 max-w-2xl mx-auto">
              These are just a few highlights from my portfolio. I'm constantly working on new projects 
              and exploring different technologies. Check out my GitHub for more examples of my work.
            </p>
            <a
              href="https://github.com/RafiALMahmud"
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 px-6 py-3 border border-accent text-accent font-sans font-medium rounded-lg hover:bg-accent hover:text-background transition-all duration-300"
            >
              <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
              </svg>
              View All Projects
            </a>
          </div>
        </div>
      </div>
    </section>
  )
}
