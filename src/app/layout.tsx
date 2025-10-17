import type { Metadata } from 'next'
import './globals.css'

export const metadata: Metadata = {
  title: 'Rafi AL Mahmud | Full-Stack JavaScript Developer',
  description: 'The personal portfolio of Rafi AL Mahmud, a MERN stack developer specializing in building modern, responsive web applications.',
  authors: [{ name: 'Rafi AL Mahmud' }],
  keywords: ['Full-Stack Developer', 'MERN Stack', 'JavaScript', 'React', 'Node.js', 'MongoDB', 'Portfolio'],
  openGraph: {
    title: 'Rafi AL Mahmud | Full-Stack JavaScript Developer',
    description: 'The personal portfolio of Rafi AL Mahmud, a MERN stack developer specializing in building modern, responsive web applications.',
    type: 'website',
  },
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  )
}
