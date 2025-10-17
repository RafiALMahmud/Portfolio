'use client'

import { useEffect, useRef } from 'react'

const skillCategories = [
  {
    categoryName: 'Programming Languages',
    skills: ['Python', 'Java', 'PHP', 'HTML5', 'CSS3']
  },
  {
    categoryName: 'Frameworks & Libraries',
    skills: ['Laravel', 'OpenGL', 'TensorFlow', 'Keras']
  },
  {
    categoryName: 'Databases & Tools',
    skills: ['MySQL', 'Git', 'GitHub', 'Postman', 'VS Code']
  },
  {
    categoryName: 'Machine Learning',
    skills: ['CycleGAN', 'MobileNetV2', 'ResNet50', 'Transfer Learning', 'Data Augmentation']
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
            Technologies and tools I've mastered for building scalable backend systems, RESTful APIs, and machine learning applications
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
              Technology evolves rapidly, and so do I. I'm constantly exploring advanced backend architectures, 
              modern PHP/Laravel patterns, machine learning applications, and scalable system design. 
              My focus is on building robust, maintainable solutions that solve real-world problems efficiently.
            </p>
          </div>
        </div>
      </div>
    </section>
  )
}
