'use client'

import { useState, useEffect } from 'react'

const navLinks = [
  { name: 'About', href: '#about' },
  { name: 'Skills', href: '#skills' },
  { name: 'Projects', href: '#projects' },
  { name: 'Contact', href: '#contact' },
]

export default function Header() {
  const [activeSection, setActiveSection] = useState('')
  const [isScrolled, setIsScrolled] = useState(false)

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 50)
      
      // Update active section based on scroll position
      const sections = navLinks.map(link => link.href.slice(1))
      const scrollPosition = window.scrollY + 100
      
      for (const section of sections) {
        const element = document.getElementById(section)
        if (element) {
          const { offsetTop, offsetHeight } = element
          if (scrollPosition >= offsetTop && scrollPosition < offsetTop + offsetHeight) {
            setActiveSection(section)
            break
          }
        }
      }
    }

    window.addEventListener('scroll', handleScroll)
    return () => window.removeEventListener('scroll', handleScroll)
  }, [])

  const scrollToSection = (href: string) => {
    const element = document.querySelector(href)
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' })
    }
  }

  return (
    <header className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
      isScrolled ? 'bg-background/80 backdrop-blur-sm border-b border-surface' : 'bg-transparent'
    }`}>
      <nav className="container-max-width section-padding py-4">
        <div className="flex items-center justify-between">
          <div 
            className="text-2xl font-sans font-bold text-accent cursor-pointer"
            onClick={() => scrollToSection('#hero')}
          >
            Rafi AL Mahmud
          </div>
          
          <ul className="hidden md:flex space-x-8">
            {navLinks.map((link) => (
              <li key={link.name}>
                <button
                  onClick={() => scrollToSection(link.href)}
                  className={`font-sans text-sm font-medium transition-colors duration-300 ${
                    activeSection === link.href.slice(1)
                      ? 'text-accent border-b-2 border-accent pb-1'
                      : 'text-text-primary hover:text-accent'
                  }`}
                >
                  {link.name}
                </button>
              </li>
            ))}
          </ul>
          
          {/* Mobile menu button */}
          <button className="md:hidden text-text-primary hover:text-accent transition-colors duration-300">
            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </nav>
    </header>
  )
}
