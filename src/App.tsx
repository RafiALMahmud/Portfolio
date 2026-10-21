import { useEffect, useRef, useState, type ComponentType } from "react";
import { Link, NavLink, useLocation } from "react-router-dom";
import { loadPage, metadata, type PageProps } from "./routing";
import { person } from "./content";

function Head({ path }: { path: string }) {
  useEffect(() => {
    const m = metadata(path);
    document.title = m.title;
    const set = (attribute: string, name: string, content: string) => {
      let el = document.head.querySelector<HTMLMetaElement>(
        `meta[${attribute}="${name}"]`,
      );
      if (!el) {
        el = document.createElement("meta");
        el.setAttribute(attribute, name);
        document.head.append(el);
      }
      el.content = content;
    };
    set("name", "description", m.description);
    set("name", "robots", m.noindex ? "noindex,follow" : "index,follow");
    set("property", "og:title", m.title);
    set("property", "og:description", m.description);
    set("property", "og:url", m.canonical);
    set("property", "og:type", "website");
    set("property", "og:image", person.siteUrl + "/og-image.png");
    set("name", "twitter:card", "summary_large_image");
    set("name", "twitter:title", m.title);
    set("name", "twitter:description", m.description);
    set("name", "twitter:image", person.siteUrl + "/og-image.png");
    let canonical = document.head.querySelector<HTMLLinkElement>(
      'link[rel="canonical"]',
    );
    if (!canonical) {
      canonical = document.createElement("link");
      canonical.rel = "canonical";
      document.head.append(canonical);
    }
    canonical.href = m.canonical;
  }, [path]);
  return null;
}

export default function App({
  initialPage,
  initialPath,
}: {
  initialPage: ComponentType<PageProps>;
  initialPath: string;
}) {
  const location = useLocation();
  const pathname = location.pathname.replace(/\/$/, "") || "/";
  const [loaded, setLoaded] = useState({
    path: initialPath,
    Page: initialPage,
  });
  const [menuOpen, setMenuOpen] = useState(false);
  const main = useRef<HTMLElement>(null);
  const menuButton = useRef<HTMLButtonElement>(null);
  const previousPath = useRef(initialPath);
  useEffect(() => {
    setMenuOpen(false);
    if (loaded.path === pathname) return;
    let current = true;
    loadPage(pathname).then((Page) => {
      if (current) setLoaded({ path: pathname, Page });
    });
    return () => {
      current = false;
    };
  }, [pathname, loaded.path]);
  useEffect(() => {
    if (previousPath.current !== loaded.path) {
      previousPath.current = loaded.path;
      window.scrollTo({ top: 0, behavior: "instant" });
      main.current?.focus({ preventScroll: true });
    }
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
    const observer = new IntersectionObserver(
      (entries) =>
        entries.forEach((e) => {
          if (e.isIntersecting) {
            e.target.classList.add("is-visible");
            observer.unobserve(e.target);
          }
        }),
      { threshold: 0.08 },
    );
    document.querySelectorAll(".reveal").forEach((el) => observer.observe(el));
    return () => observer.disconnect();
  }, [loaded.path]);
  useEffect(() => {
    const close = (event: KeyboardEvent) => {
      if (event.key === "Escape" && menuOpen) {
        setMenuOpen(false);
        menuButton.current?.focus();
      }
    };
    window.addEventListener("keydown", close);
    return () => window.removeEventListener("keydown", close);
  }, [menuOpen]);
  const Page = loaded.Page;
  return (
    <>
      <Head path={loaded.path} />
      <a className="skip-link" href="#main">
        Skip to content
      </a>
      <header className="site-header">
        <div className="nav-container">
          <Link className="brand" to="/" title="Home">
            <span className="brand-stamp">R</span>
            <span>
              RAFI AL MAHMUD
              <span className="brand-caption">DEVELOPER / CASE ARCHIVE</span>
            </span>
          </Link>
          <button
            ref={menuButton}
            className="menu-button"
            aria-expanded={menuOpen}
            aria-controls="primary-nav"
            aria-label={menuOpen ? "Close navigation" : "Open navigation"}
            onClick={() => setMenuOpen(!menuOpen)}
          >
            <span /> <span />
          </button>
          <nav
            id="primary-nav"
            className={menuOpen ? "nav-links open" : "nav-links"}
            aria-label="Main navigation"
          >
            {[
              ["/live", "Live Sites"],
              ["/projects", "Projects"],
              ["/about", "About"],
              ["/resume", "Resume"],
              ["/contact", "Contact"],
            ].map(([to, label]) => (
              <NavLink
                key={to}
                to={to}
                className={({ isActive }) =>
                  `${isActive ? "active " : ""}${to === "/contact" ? "nav-contact" : ""}`
                }
              >
                {label}
                {to === "/contact" && <span aria-hidden="true">↗</span>}
              </NavLink>
            ))}
          </nav>
        </div>
      </header>
      <main id="main" tabIndex={-1} ref={main} className="container">
        <div
          className="page"
          key={loaded.path}
          aria-busy={loaded.path !== pathname}
        >
          <Page path={loaded.path} />
        </div>
      </main>
      <footer className="site-footer">
        <div className="container footer-content">
          <Link className="footer-signature" to="/">
            Rafi Al Mahmud<span className="mono">EVERY DETAIL MATTERS.</span>
          </Link>
          <div className="footer-links">
            <a href={`mailto:${person.email}`}>Email ↗</a>
            <a href={person.github} target="_blank" rel="noreferrer">
              GitHub ↗
            </a>
            <a href={person.linkedin} target="_blank" rel="noreferrer">
              LinkedIn ↗
            </a>
          </div>
          <p>© 2026 Rafi Al Mahmud</p>
        </div>
      </footer>
    </>
  );
}
