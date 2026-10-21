export interface CaseSection {
  title: string;
  body?: string;
  items?: string[];
}
export interface LiveSite {
  slug: string;
  number: string;
  title: string;
  category: string;
  url: string;
  description: string;
  stack: string[];
  role: string;
  sections: CaseSection[];
  screenshots: { src: string; alt: string; caption: string }[];
}
export const liveSites: LiveSite[] = [
  {
    slug: "mangostudents",
    number: "001",
    title: "MangoStudents",
    category: "PUBLISHING & LEARNING",
    url: "https://mangostudent.com",
    description:
      "A student-led digital magazine and learning platform. A space to publish, find your voice, and learn something new.",
    stack: [
      "Laravel",
      "PHP",
      "MySQL",
      "HTML",
      "CSS",
      "JavaScript",
      "Hostinger",
    ],
    role: "I am the primary developer of the magazine. I built and deployed its Laravel and MySQL publishing system, with an admin panel that lets editors manage content without touching code. I structured the database around categories, authors and editorial workflows, and used eager loading to avoid N+1 queries in the post feed.",
    sections: [
      {
        title: "What it is",
        body: "A student-led digital magazine and learning platform, based in Dhaka. Readers browse posts by category, enter writing, art and music competitions, use English-learning and support pages, and submit their own work.",
      },
      {
        title: "Content areas",
        items: [
          "Mango Support: assignments, proofreading and editing; creative and academic writing; research and learning projects; foreign applications including SOPs, CVs and personal statements; career and freelancing guidance; mental health support; ethical use of AI; research support and digital skills.",
          "English Learning: spoken English, IELTS preparation and BCS English support.",
          "Competition: art, stories, poetry and music.",
          "Literature: podcasts, discussions and reviews.",
          "Features: personal essays, culture and heritage, and student life.",
          "Interviews; Food reviews and vlogs; Gallery; Submit Work.",
        ],
      },
      {
        title: "Platform features",
        items: [
          "Category-based posts with featured and latest feeds.",
          "Reader registration and login.",
          "Public work submission and moderated editorial workflows.",
          "An administrative interface for non-technical editors.",
        ],
      },
    ],
    screenshots: [
      {
        src: "/images/mangostudents.webp",
        alt: "MangoStudents magazine homepage with category navigation and a featured article.",
        caption: "Public homepage · captured 21 October 2026.",
      },
    ],
  },
  {
    slug: "sportevo",
    number: "002",
    title: "SportEvo",
    category: "SPORTS FACILITY BOOKING",
    url: "https://sportevo.games",
    description:
      "From an open field to a confirmed booking. A sports facility platform built around real scheduling constraints.",
    stack: [
      "Laravel 12",
      "PHP",
      "MySQL",
      "React 19",
      "Vite",
      "Tailwind",
      "Three.js",
      "Sanctum",
      "Hostinger",
    ],
    role: "I built the live sports facility booking platform, from per-field slots and role-based access to booking overlap checks, owner approvals and the commission ledger. The React and TypeScript interface connects to a Laravel and MySQL backend.",
    sections: [
      {
        title: "What it is",
        body: "A national turf and sports-complex booking platform for Bangladesh. Customers pick a field and a generated slot, then send a booking request. Owners accept or reject requests; admins approve complexes and track commission. The interface supports English and Bangla, with prices in BDT.",
      },
      {
        title: "Roles & access",
        body: "Customer, complex owner, admin and superadmin. Public signup creates only customers and owners. Admin accounts are provisioned manually.",
      },
      {
        title: "Booking flow",
        body: "Browse approved complexes → choose a field → choose a generated slot → send a request → owner accepts or rejects.",
        items: [
          "Statuses: available, unavailable (owner-blocked), pending, booked, rejected, cancelled and completed.",
        ],
      },
      {
        title: "Concurrency rules",
        body: "Database row locks prevent overlapping bookings on the same field and prevent a customer from holding overlapping pending or booked requests across venues. Adjacent slots, such as 10:00–11:00 and 11:00–12:00, are allowed.",
      },
      {
        title: "Owner tools",
        items: [
          "Register one complex with fields and upload JPG, PNG or WebP photographs: up to 8 per upload and 24 per field.",
          "Generate slots lasting 15–240 minutes.",
          "Block time for phone bookings or maintenance, accept or reject requests, and inspect the commission wallet.",
        ],
      },
      {
        title: "Pricing & commission",
        body: "The server quotes fixed-slot or hourly prices. The price and commission rate are snapshotted at request time, so later changes do not alter existing bookings. Default commission is 10%, adjustable by admins from 0 to 100%.",
        items: [
          "The wallet records charges, settlements and reversals.",
          "Cancelling an accepted booking requires owner approval, followed by admin approval for the commission reversal.",
          "There is no customer payment gateway. The platform tracks owner commission only.",
        ],
      },
      {
        title: "Security & privacy",
        items: [
          "Names, emails and phone numbers encrypted at rest.",
          "Cookie-based Sanctum sessions with a 45-minute idle logout.",
          "Role and ownership checks on every API route.",
          "Rate limits on authentication and contact endpoints, plus security headers.",
        ],
      },
      {
        title: "Email delivery",
        body: "A tracked outbox records delivery, with retries handled through a queue worker.",
      },
      {
        title: "Interface",
        body: "English and Bangla, dark and light themes, and a responsive interface with an interactive stadium hero made in React Three Fiber.",
      },
      {
        title: "Quality evidence",
        body: "116 backend PHPUnit tests passing as of 14 October 2026. This is the dated test result supplied for this case file.",
      },
    ],
    // TODO: Add an owner-supplied screenshot of the authenticated owner schedule. Do not publish customer details.
    screenshots: [
      {
        src: "/images/sportevo.webp",
        alt: "SportEvo public homepage with its stadium hero and sports complex navigation.",
        caption: "Public homepage and stadium hero · captured 21 October 2026.",
      },
    ],
  },
];
