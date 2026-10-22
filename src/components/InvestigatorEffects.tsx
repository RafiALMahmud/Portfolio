import { useEffect, useRef } from "react";

const interactive = "a, button, summary, [role='button']";
const nativePointer =
  "input, textarea, select, [contenteditable], iframe, object, video, audio";

/** Pointer decoration and shallow CSS 3D, with no WebGL or render loop at rest. */
export default function InvestigatorEffects() {
  const cursor = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const media = window.matchMedia(
      "(hover: hover) and (pointer: fine) and (prefers-reduced-motion: no-preference)",
    );
    const root = document.documentElement;
    let dispose = () => {};

    const configure = () => {
      dispose();
      if (!media.matches || !cursor.current) return;
      const lens = cursor.current;
      const events = new AbortController();
      let frame = 0;
      let x = 0;
      let y = 0;
      let previousX = 0;
      let roll = 0;
      let target: Element | null = null;
      let tilted: HTMLElement | null = null;

      const resetTilt = () => {
        if (!tilted) return;
        tilted.classList.remove("is-tilting");
        for (const name of [
          "--tilt-x",
          "--tilt-y",
          "--tilt-lift",
          "--shine-x",
          "--shine-y",
        ]) {
          tilted.style.removeProperty(name);
        }
        tilted = null;
      };

      const hide = () => {
        if (frame) cancelAnimationFrame(frame);
        frame = 0;
        root.classList.remove("investigator-pointer");
        lens.classList.remove("is-visible", "is-pressed", "is-interactive");
        resetTilt();
      };

      const render = () => {
        frame = 0;
        const next = target?.closest<HTMLElement>("[data-tilt]") ?? null;
        // Read layout before writing transforms, and only for the hovered surface.
        const bounds = next?.getBoundingClientRect();
        if (next !== tilted) resetTilt();
        if (next && bounds) {
          tilted = next;
          const px = Math.max(0, Math.min(1, (x - bounds.left) / bounds.width));
          const py = Math.max(0, Math.min(1, (y - bounds.top) / bounds.height));
          const depth = next.classList.contains("dossier-stage") ? 14 : 8;
          next.style.setProperty(
            "--tilt-x",
            `${((0.5 - py) * depth).toFixed(2)}deg`,
          );
          next.style.setProperty(
            "--tilt-y",
            `${((px - 0.5) * depth).toFixed(2)}deg`,
          );
          next.style.setProperty("--tilt-lift", "-5px");
          next.style.setProperty("--shine-x", `${(px * 100).toFixed(1)}%`);
          next.style.setProperty("--shine-y", `${(py * 100).toFixed(1)}%`);
          next.classList.add("is-tilting");
        }
        lens.style.transform = `translate3d(${x}px, ${y}px, 0)`;
        lens.style.setProperty("--cursor-roll", `${roll.toFixed(1)}deg`);
        lens.classList.toggle(
          "is-interactive",
          Boolean(target?.closest(interactive)),
        );
        lens.classList.add("is-visible");
        // Hide the native cursor only after its replacement is positioned.
        root.classList.add("investigator-pointer");
      };

      const move = (event: PointerEvent) => {
        target = event.target instanceof Element ? event.target : null;
        if (event.pointerType !== "mouse" || target?.closest(nativePointer)) {
          hide();
          return;
        }
        x = event.clientX;
        y = event.clientY;
        roll = Math.max(-12, Math.min(12, (x - previousX) * 0.15));
        previousX = x;
        if (!frame) frame = requestAnimationFrame(render);
      };

      const followScroll = () => {
        if (!frame && !lens.classList.contains("is-visible")) return;
        target = document.elementFromPoint(x, y);
        if (target?.closest(nativePointer)) {
          hide();
          return;
        }
        // Keep the instrument at its screen position as content moves beneath it.
        if (!frame) frame = requestAnimationFrame(render);
      };

      const options = { signal: events.signal, passive: true };
      window.addEventListener("pointermove", move, options);
      window.addEventListener(
        "pointerout",
        (event) => {
          if (!event.relatedTarget) hide();
        },
        options,
      );
      window.addEventListener("blur", hide, options);
      window.addEventListener("scroll", followScroll, options);
      window.addEventListener(
        "keydown",
        (event) => {
          if (event.key === "Tab" || event.key === "Escape") hide();
        },
        options,
      );
      window.addEventListener(
        "pointerdown",
        (event) => {
          if (event.pointerType === "mouse") lens.classList.add("is-pressed");
        },
        options,
      );
      window.addEventListener(
        "pointerup",
        () => lens.classList.remove("is-pressed"),
        options,
      );
      document.addEventListener(
        "visibilitychange",
        () => {
          if (document.hidden) hide();
        },
        options,
      );

      dispose = () => {
        events.abort();
        hide();
      };
    };

    configure();
    media.addEventListener("change", configure);
    return () => {
      dispose();
      media.removeEventListener("change", configure);
    };
  }, []);

  return (
    <div className="investigator-cursor" ref={cursor} aria-hidden="true">
      <div className="cursor-instrument">
        <span className="cursor-neck" />
        <span className="cursor-handle" />
        <span className="cursor-lens">
          <span className="cursor-reticle" />
        </span>
      </div>
      <span className="cursor-signal" />
    </div>
  );
}
