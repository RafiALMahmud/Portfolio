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
                I'm a passionate full-stack JavaScript developer with a strong focus on the MERN stack. 
                My journey in web development began with a curiosity about how digital experiences are created, 
                and it has evolved into a deep expertise in building scalable, user-friendly applications.
              </p>
              <p>
                What drives me is the challenge of solving complex problems through clean, efficient code. 
                I believe in the power of technology to create meaningful solutions that make a difference 
                in people's lives. Whether it's building an e-commerce platform, a medical portal, or a 
                volunteer management system, I approach each project with attention to detail and a commitment to excellence.
              </p>
              <p>
                When I'm not coding, you'll find me exploring new technologies, contributing to open-source projects, 
                or sharing knowledge with the developer community. I'm always eager to learn and grow, 
                and I'm excited about the opportunity to bring my skills and passion to your next project.
              </p>
            </div>
          </div>
          
          <div className="space-y-6">
            <div className="bg-surface p-6 rounded-lg border border-surface hover:border-accent transition-colors duration-300">
              <h3 className="text-xl font-sans font-semibold text-text-primary mb-3">
                My Approach
              </h3>
              <p className="text-body font-body text-text-secondary">
                I believe in writing code that is not just functional, but also maintainable, 
                scalable, and well-documented. Every project is an opportunity to learn and improve.
              </p>
            </div>
            
            <div className="bg-surface p-6 rounded-lg border border-surface hover:border-accent transition-colors duration-300">
              <h3 className="text-xl font-sans font-semibold text-text-primary mb-3">
                What I Value
              </h3>
              <p className="text-body font-body text-text-secondary">
                Clean architecture, user experience, performance optimization, and continuous learning 
                are the pillars of my development philosophy.
              </p>
            </div>
            
            <div className="bg-surface p-6 rounded-lg border border-surface hover:border-accent transition-colors duration-300">
              <h3 className="text-xl font-sans font-semibold text-text-primary mb-3">
                Beyond Code
              </h3>
              <p className="text-body font-body text-text-secondary">
                I'm passionate about mentoring, contributing to open source, and staying up-to-date 
                with the latest trends in web development.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
