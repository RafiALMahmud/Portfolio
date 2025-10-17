'use client'

import { useEffect, useRef } from 'react'

const skillCategories = [
  {
    categoryName: 'Languages',
    skills: ['JavaScript', 'TypeScript', 'HTML5', 'CSS3', 'Python']
  },
  {
    categoryName: 'Frameworks & Libraries',
    skills: ['React', 'Node.js', 'Express.js', 'Next.js', 'Tailwind CSS', 'Material-UI']
  },
  {
    categoryName: 'Databases',
    skills: ['MongoDB', 'Firebase', 'MySQL', 'PostgreSQL']
  },
  {
    categoryName: 'Tools & Platforms',
    skills: ['Git', 'GitHub', 'Vercel', 'VS Code', 'Figma', 'Postman', 'Docker']
  }
]

export default function Skills() {
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
    <section id="skills" ref={sectionRef} className="section-padding bg-surface/30 animate-on-scroll">
      <div className="container-max-width">
        <div className="text-center mb-16">
          <h2 className="text-section font-section font-sans text-text-primary mb-4">
            Skills & Technologies
          </h2>
          <p className="text-body font-body text-text-secondary max-w-2xl mx-auto">
            A comprehensive overview of the technologies and tools I use to bring ideas to life
          </p>
        </div>
        
        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
          {skillCategories.map((category, index) => (
            <div 
              key={category.categoryName}
              className="bg-background p-6 rounded-lg border border-surface hover:border-accent transition-all duration-300 hover:shadow-lg hover:shadow-accent/10"
              style={{ animationDelay: `${index * 100}ms` }}
            >
              <h3 className="text-xl font-sans font-semibold text-text-primary mb-4">
                {category.categoryName}
              </h3>
              <div className="flex flex-wrap gap-2">
                {category.skills.map((skill, skillIndex) => (
                  <span
                    key={skill}
                    className="px-3 py-1 bg-surface text-tech font-mono text-accent rounded-full border border-surface hover:border-accent transition-colors duration-300"
                    style={{ animationDelay: `${(index * 100) + (skillIndex * 50)}ms` }}
                  >
                    {skill}
                  </span>
                ))}
              </div>
            </div>
          ))}
        </div>
        
        <div className="mt-12 text-center">
          <div className="bg-background p-8 rounded-lg border border-surface">
            <h3 className="text-xl font-sans font-semibold text-text-primary mb-4">
              Continuous Learning
            </h3>
            <p className="text-body font-body text-text-secondary max-w-3xl mx-auto">
              Technology evolves rapidly, and so do I. I'm constantly exploring new frameworks, 
              tools, and methodologies to stay at the forefront of web development. Currently, 
              I'm diving deeper into advanced React patterns, serverless architectures, and 
              performance optimization techniques.
            </p>
          </div>
        </div>
      </div>
    </section>
  )
}
