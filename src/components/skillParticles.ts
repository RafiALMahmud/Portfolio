/** Bounded CSS 3D grains converge from a back plane onto the actual glyph contours. */
export function createSkillParticles(
  word: HTMLDivElement,
  paths: SVGPathElement[],
  stagger: number,
): Animation[] {
  const field = word.querySelector<HTMLDivElement>(".skill-particles");
  if (!field || !paths.length) return [];
  const bounds = field.getBoundingClientRect();
  const limit = window.matchMedia("(max-width: 700px)").matches ? 48 : 84;
  const perLetter = Math.max(1, Math.min(8, Math.floor(limit / paths.length)));
  const targets: {
    x: number;
    y: number;
    dx: number;
    dy: number;
    delay: number;
    seed: number;
  }[] = [];

  // Finish geometry reads before inserting or animating any particle nodes.
  paths.forEach((path, letter) => {
    const matrix = path.getScreenCTM();
    if (!matrix) return;
    const length = path.getTotalLength();
    for (let grain = 0; grain < perLetter && targets.length < limit; grain++) {
      const point = path
        .getPointAtLength((length * (grain + 0.35)) / perLetter)
        .matrixTransform(matrix);
      const seed = letter * 31 + grain * 17;
      targets.push({
        x: point.x - bounds.left,
        y: point.y - bounds.top,
        dx: ((seed * 23 + 11) % 241) - 120,
        dy: ((seed * 37 + 19) % 171) - 85,
        delay: letter * stagger + grain * 45,
        seed,
      });
    }
  });

  const fragment = document.createDocumentFragment();
  const grains = targets.map((target) => {
    const grain = document.createElement("span");
    grain.className = "skill-grain";
    grain.style.left = `${target.x}px`;
    grain.style.top = `${target.y}px`;
    grain.style.width =
      grain.style.height = `${1.5 + (target.seed % 3) * 0.65}px`;
    grain.style.setProperty(
      "--grain-angle",
      `${(Math.atan2(target.dy, target.dx) * 180) / Math.PI + 90}deg`,
    );
    if (target.seed % 5 === 0) grain.classList.add("skill-grain-red");
    fragment.append(grain);
    return grain;
  });
  field.replaceChildren(fragment);

  return grains.map((grain, index) => {
    const { dx, dy, delay } = targets[index];
    const effect = grain.animate(
      [
        {
          opacity: 0,
          transform: `translate3d(${dx}px, ${dy}px, -700px) scale(.15)`,
          filter: "blur(2px)",
          offset: 0,
        },
        {
          opacity: 0.65,
          transform: `translate3d(${dx * 0.55}px, ${dy * 0.55}px, -360px) scale(.55)`,
          filter: "blur(1px)",
          offset: 0.3,
        },
        {
          opacity: 0.95,
          transform: `translate3d(${dx * 0.12}px, ${dy * 0.12}px, -65px) scale(1)`,
          filter: "blur(0)",
          offset: 0.72,
        },
        {
          opacity: 0,
          transform: "translate3d(0, 0, 0) scale(.35)",
          filter: "blur(0)",
          offset: 1,
        },
      ],
      { duration: 1650, delay, fill: "both", easing: "ease-in-out" },
    );
    effect.pause();
    return effect;
  });
}
