/** An original magnifier, built from CSS surfaces rather than a downloaded model. */
export default function DeskLoupe() {
  return (
    <div className="desk-loupe" aria-hidden="true">
      <div className="desk-loupe-object">
        <div className="desk-loupe-neck" />
        <div className="desk-loupe-handle">
          <span />
        </div>
        <div className="desk-loupe-rim">
          <div className="desk-loupe-glass">
            <svg viewBox="0 0 80 80" fill="none">
              <circle
                cx="40"
                cy="40"
                r="27"
                stroke="currentColor"
                strokeWidth=".5"
                strokeDasharray="2 4"
              />
              <path
                d="M20 40h11m18 0h11M40 20v11m0 18v11"
                stroke="currentColor"
                strokeWidth=".7"
              />
              <circle cx="40" cy="40" r="12" stroke="#c1121f" strokeWidth="1" />
              <circle cx="40" cy="40" r="2" fill="#ede6da" />
              <path
                d="M18 24c8-12 25-16 39-6"
                stroke="#ede6da"
                strokeWidth="3"
                strokeLinecap="round"
                opacity=".25"
              />
            </svg>
          </div>
        </div>
      </div>
      <span className="loupe-caption">EXAMINE CLOSELY.</span>
    </div>
  );
}
