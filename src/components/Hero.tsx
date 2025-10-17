'use client'

import { useEffect, useState } from 'react'

const TITLES = [
  'Full-Stack JavaScript Developer',
  'MERN Stack Developer',
  'React Specialist',
  'Node.js Expert'
]

export default function Hero() {
  const [isVisible, setIsVisible] = useState(false)

  useEffect(() => {
    setIsVisible(true)
  }, [])

  const [currentTitleIndex, setCurrentTitleIndex] = useState(0)

  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentTitleIndex((prev) => (prev + 1) % TITLES.length)
    }, 3000)

    return () => clearInterval(interval)
  }, [])

  return (
    <section id="hero" className="min-h-screen flex items-center justify-center section-padding">
      <div className="container-max-width text-center">
        <div className={`transition-all duration-700 ${isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'}`}>
          <h1 className="text-hero font-hero font-sans text-text-primary mb-6">
            Rafi AL Mahmud
          </h1>
          
          <div className="relative mb-8">
            <h2 className="text-2xl md:text-4xl font-section font-sans text-accent">
              {TITLES[currentTitleIndex]}
            </h2>
            <div className="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-accent transition-all duration-300 animate-typewriter"></div>
          </div>
          
          <p className="text-body font-body text-text-secondary max-w-2xl mx-auto mb-12 leading-relaxed">
            I build modern, scalable web applications using the MERN stack. 
            Passionate about creating efficient solutions and clean, maintainable code.
          </p>
          
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <button 
              onClick={() => document.getElementById('projects')?.scrollIntoView({ behavior: 'smooth' })}
              className="px-8 py-3 bg-accent text-background font-sans font-link rounded-lg hover:bg-accent/90 transition-colors duration-300"
            >
              View My Work
            </button>
            <button 
              onClick={() => document.getElementById('contact')?.scrollIntoView({ behavior: 'smooth' })}
              className="px-8 py-3 border border-accent text-accent font-sans font-link rounded-lg hover:bg-accent hover:text-background transition-all duration-300"
            >
              Get In Touch
            </button>
          </div>
        </div>
        
        {/* Scroll indicator */}
        <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
          <div className="w-6 h-10 border-2 border-text-secondary rounded-full flex justify-center">
            <div className="w-1 h-3 bg-text-secondary rounded-full mt-2 animate-pulse"></div>
          </div>
        </div>
      </div>
    </section>
  )
}
