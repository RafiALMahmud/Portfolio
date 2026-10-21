import { renderToString } from "react-dom/server";
import { StaticRouter } from "react-router-dom";
import App from "./App";
import { loadPage } from "./routing";
export { routes, metadata } from "./routing";
export { person } from "./content";
export async function render(path: string) {
  const Page = await loadPage(path);
  return renderToString(
    <StaticRouter location={path}>
      <App initialPage={Page} initialPath={path} />
    </StaticRouter>,
  );
}
