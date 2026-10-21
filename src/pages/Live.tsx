import { FileHeader, LiveCard, ContactCTA } from "../components/UI";
import { liveSites } from "../data/liveSites";
export default function Live() {
  return (
    <>
      <FileHeader
        number="L—INDEX"
        title="Live cases."
        label="LIVE"
        intro="Running in production, with real URLs."
      />
      <h2 className="sr-only">Live platforms</h2>
      <div className="grid gap-7 md:grid-cols-2 page-section">
        {liveSites.map((site) => (
          <LiveCard key={site.slug} site={site} />
        ))}
      </div>
      <div className="archive-note mono">
        BUILT, DEPLOYED, AND DOCUMENTED. EXAMINE EACH FILE FOR THE DETAILS.
      </div>
      <ContactCTA />
    </>
  );
}
