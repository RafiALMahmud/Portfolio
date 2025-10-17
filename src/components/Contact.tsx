'use client'

import { useEffect, useRef } from 'react'

const socialLinks = [
  {
    name: 'GitHub',
    url: 'https://github.com/RafiALMahmud',
    icon: (
      <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
      </svg>
    )
  },
  {
    name: 'LinkedIn',
    url: 'https://linkedin.com/in/rafi-al-mahmud',
    icon: (
      <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
      </svg>
    )
  },
  {
    name: 'Email',
    url: 'mailto:rafi.al.mahmud.dev@email.com',
    icon: (
      <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
      </svg>
    )
  }
]

export default function Contact() {
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
    <section id="contact" ref={sectionRef} className="section-padding bg-surface/30 animate-on-scroll">
      <div className="container-max-width">
        <div className="text-center mb-16">
          <h2 className="text-section font-section font-sans text-text-primary mb-4">
            Get In Touch
          </h2>
          <p className="text-body font-body text-text-secondary max-w-2xl mx-auto">
            I'm currently open to new opportunities and collaborations. Whether you have a question or just want to say hi, my inbox is always open. I'll get back to you as soon as possible!
          </p>
        </div>
        
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div className="space-y-8">
            <div className="bg-background p-6 rounded-lg border border-surface hover:border-accent transition-colors duration-300">
              <h3 className="text-xl font-sans font-semibold text-text-primary mb-4">
                Let's Work Together
              </h3>
              <p className="text-body font-body text-text-secondary mb-4">
                I'm always excited to work on new projects and collaborate with amazing people. 
                Whether you need a full-stack developer for your next big idea or want to discuss 
                potential opportunities, I'd love to hear from you.
              </p>
              <a
                href="mailto:rafi.al.mahmud.dev@email.com"
                className="inline-flex items-center gap-2 text-accent hover:text-accent/80 transition-colors duration-300 font-sans font-medium"
              >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                rafi.al.mahmud.dev@email.com
              </a>
            </div>
            
            <div className="bg-background p-6 rounded-lg border border-surface hover:border-accent transition-colors duration-300">
              <h3 className="text-xl font-sans font-semibold text-text-primary mb-4">
                Download Resume
              </h3>
              <p className="text-body font-body text-text-secondary mb-4">
                Want to see my complete professional background? Download my resume for a detailed 
                overview of my experience, skills, and achievements.
              </p>
              <a
                href="/Rafi-Al-Mahmud-FlowCV-Resume.pdf"
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center gap-2 px-4 py-2 bg-accent text-background font-sans font-medium rounded-lg hover:bg-accent/90 transition-colors duration-300"
              >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download Resume (PDF)
              </a>
            </div>
          </div>
          
          <div className="space-y-6">
            <h3 className="text-xl font-sans font-semibold text-text-primary text-center">
              Connect With Me
            </h3>
            <div className="flex flex-col gap-4">
              {socialLinks.map((social, index) => (
                <a
                  key={social.name}
                  href={social.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex items-center gap-4 p-4 bg-background rounded-lg border border-surface hover:border-accent transition-all duration-300 hover:shadow-lg hover:shadow-accent/10 group"
                  style={{ animationDelay: `${index * 100}ms` }}
                >
                  <div className="text-accent group-hover:scale-110 transition-transform duration-300">
                    {social.icon}
                  </div>
                  <div>
                    <div className="font-sans font-medium text-text-primary group-hover:text-accent transition-colors duration-300">
                      {social.name}
                    </div>
                    <div className="text-sm text-text-secondary">
                      {social.url.replace('mailto:', '').replace('https://', '').replace('http://', '')}
                    </div>
                  </div>
                  <div className="ml-auto text-text-secondary group-hover:text-accent transition-colors duration-300">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                  </div>
                </a>
              ))}
            </div>
          </div>
        </div>
        
        <div className="mt-16 text-center">
          <div className="bg-background p-8 rounded-lg border border-surface">
            <p className="text-body font-body text-text-secondary">
              Thank you for taking the time to explore my portfolio. I look forward to connecting with you!
            </p>
          </div>
        </div>
      </div>
    </section>
  )
}
