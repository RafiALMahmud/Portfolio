import { createRoot, hydrateRoot } from "react-dom/client";
import { BrowserRouter } from "react-router-dom";
import App from "./App";
import { loadPage, routes } from "./routing";
import "./styles.css";
import "./spatial.css";
import "./skills.css";

const path = window.location.pathname.replace(/\/$/, "") || "/";
loadPage(path).then((Page) => {
  const app = (
    <BrowserRouter>
      <App initialPage={Page} initialPath={path} />
    </BrowserRouter>
  );
  const root = document.getElementById("root")!;
  if (root.querySelector("main") && (routes.includes(path) || path === "/404"))
    hydrateRoot(root, app);
  else createRoot(root).render(app);
});
