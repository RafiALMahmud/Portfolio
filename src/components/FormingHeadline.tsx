import { useEffect, useState, type CSSProperties } from "react";

const headline = "Everybody’s code lies. I read the logs.";

function Word({
  text,
  start,
  line,
}: {
  text: string;
  start: number;
  line: number;
}) {
  return (
    <span className="forming-word">
      {Array.from(text).map((letter, index) => {
        const seed = start + index;
        const style = {
          "--letter-delay": `${180 + line * 470 + index * 28 + start * 18}ms`,
          "--letter-x": `${((seed * 17 + 9) % 43) - 21}px`,
          "--letter-y": `${((seed * 23 + 5) % 49) - 28}px`,
          "--letter-turn": `${((seed * 11 + 7) % 31) - 15}deg`,
        } as CSSProperties;
        return (
          <span className="forming-letter" key={index} style={style}>
            {letter}
          </span>
        );
      })}
    </span>
  );
}

/** Stable, prerendered text; finite CSS animation runs only when motion is allowed. */
export default function FormingHeadline() {
  const [forming, setForming] = useState(false);
  useEffect(() => {
    const preference = window.matchMedia("(prefers-reduced-motion: reduce)");
    let timer: number | undefined;
    const play = () => {
      window.clearTimeout(timer);
      setForming(!preference.matches);
      if (!preference.matches)
        timer = window.setTimeout(() => setForming(false), 2600);
    };
    play();
    preference.addEventListener("change", play);
    return () => {
      window.clearTimeout(timer);
      preference.removeEventListener("change", play);
    };
  }, []);

  return (
    <h1
      className="forming-headline"
      data-forming={forming}
      aria-label={headline}
    >
      <span aria-hidden="true">
        <Word text="Everybody’s" start={0} line={0} />
        <br />
        <Word text="code" start={10} line={1} />{" "}
        <em>
          <Word text="lies." start={14} line={1} />
        </em>
        <br />
        <Word text="I" start={19} line={2} />{" "}
        <Word text="read" start={20} line={2} />{" "}
        <Word text="the" start={24} line={2} />{" "}
        <Word text="logs" start={27} line={2} />
        <span className="period">
          <Word text="." start={31} line={2} />
        </span>
      </span>
    </h1>
  );
}
