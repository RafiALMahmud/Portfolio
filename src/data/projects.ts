export interface Project {
  slug: string;
  title: string;
  language: string;
  tags: string[];
  description?: string;
  body?: string;
  stack: string[];
  repository?: string;
  external?: string;
  sourceNote: string;
}
const github = "https://github.com/RafiALMahmud/";
// README summaries retrieved 21 October 2026. Stored locally; builds never fetch GitHub.
// Generic Laravel scaffold READMEs do not substantiate application-specific features.
export const projects: Project[] = [
  {
    slug: "wase",
    title: "WASE",
    language: "Python",
    tags: ["Research", "Python"],
    description:
      "Teaching object detectors to see through fog, without new manual annotations.",
    stack: ["PyTorch", "YOLOv8-Nano", "Self-training"],
    sourceNote:
      "Latest Drive CV and supplied website brief. No public WASE repository was found.",
  },
  {
    slug: "mindwell",
    title: "MindWell",
    language: "Python",
    tags: ["Python", "Web"],
    description:
      "An employee wellness platform with a role-gated API and invitation-only onboarding.",
    body: "I wrote REST APIs and database models in FastAPI and PostgreSQL, with JWT authentication, invitation-based onboarding, role-based dashboards and session expiry. Alembic keeps schema changes versioned. Docker provides consistent development and production environments; FastAPI serves the built React assets in production.",
    stack: [
      "FastAPI",
      "PostgreSQL",
      "React",
      "TypeScript",
      "Docker",
      "Alembic",
    ],
    repository: github + "Depression_detection",
    sourceNote: "Drive CV, SWE CV and project README.",
  },
  {
    slug: "weather-classifier",
    title: "Adverse Weather Classifier",
    language: "Python",
    tags: ["Research", "Python"],
    description:
      "A lightweight weather classifier, from training and evaluation to ONNX inference.",
    body: "A MobileNetV3-Small pipeline classifies rain, fog, sand and snow using DAWN and FoggyCityscape samples. It uses weighted loss for class imbalance and evaluates precision, recall, F1 and confusion matrices. The repository includes TorchScript and ONNX export, quantization, realtime inference, a Tkinter image-prediction interface and a GitHub Actions syntax-check workflow.",
    stack: ["PyTorch", "MobileNetV3", "ONNX", "Tkinter", "GitHub Actions"],
    repository: github + "Weather-classifier-enhancer",
    sourceNote:
      "Drive CV and project README. These dataset names belong to the classifier, not to WASE.",
  },
  {
    slug: "job-portal",
    title: "Job Portal",
    language: "PHP",
    tags: ["PHP", "Web"],
    description:
      "Job listings, company profiles and applications backed by a relational schema.",
    body: "I designed a normalized MySQL schema with foreign-key constraints for users, job postings, company profiles and applications. A single Laravel backend provides separate access for job seekers, employers and admins. API endpoints were documented in Postman.",
    stack: ["Laravel", "PHP", "MySQL", "REST APIs", "Postman"],
    repository: github + "Job_portal1",
    sourceNote:
      "CVs and ZIP source. The repository README is the Laravel scaffold, so the project description comes from the CV.",
  },
  {
    slug: "bucuc",
    title: "BUCuC Member Portal",
    language: "PHP",
    tags: ["PHP", "Web"],
    description:
      "The BRAC University Cultural Club website, membership portal and archive.",
    body: "I worked with club leadership on a database-driven website, replacing paper registration with an online member application and login flow. Administrative panels let the team update member records, event archives and leadership history without hardcoded content.",
    stack: ["PHP", "MySQL", "JavaScript", "HTML", "CSS"],
    external: "https://www.bucuc.org",
    sourceNote: "Drive CV and the CV PDF inside CV.zip.",
  },
  {
    slug: "hospital-management",
    title: "Hospital_Management",
    language: "PHP",
    tags: ["PHP", "Web"],
    description: "An early PHP and MySQL coursework project.",
    body: "The repository describes this as my first project. Its README documents a local XAMPP setup and the included login_db.sql database. More detailed application behavior is not documented.",
    stack: ["PHP", "MySQL", "XAMPP"],
    repository: github + "Hospital_Management",
    sourceNote: "Repository README and public repository metadata.",
  },
  {
    slug: "house-price-prediction",
    title: "422-Lab-Project",
    language: "Python",
    tags: ["Python", "Research"],
    description:
      "House price prediction coursework with the dataset and project report included.",
    body: "The README identifies this as a house price prediction project and points readers to the dataset and report in the repository. Further modeling details are not documented in the README.",
    stack: ["Python"],
    repository: github + "422-Lab-Project",
    sourceNote: "Repository README.",
  },
  {
    slug: "cse423-project",
    title: "Cse423-Project",
    language: "Python",
    tags: ["Python"],
    stack: ["Python"],
    repository: github + "Cse423-Project",
    sourceNote:
      "Public repository metadata. No README found; no undocumented features are claimed.",
  },
  {
    slug: "cse330",
    title: "Cse330",
    language: "Python",
    tags: ["Python"],
    stack: ["Python"],
    repository: github + "Cse330",
    sourceNote: "Public repository metadata. No README found.",
  },
  {
    slug: "bracu-labmid",
    title: "Bracu-Labmid",
    language: "Not specified",
    tags: [],
    stack: [],
    repository: github + "Bracu-Labmid",
    sourceNote: "No README or primary language is reported by GitHub.",
  },
  {
    slug: "cse",
    title: "CSE",
    language: "Not specified",
    tags: [],
    stack: [],
    repository: github + "CSE",
    sourceNote: "No README or primary language is reported by GitHub.",
  },
  {
    slug: "covid-radiography",
    title: "COVID-19 Radiography Research",
    language: "Python",
    tags: ["Research", "Python"],
    description:
      "An experimental radiography classification pipeline using synthetic augmentation.",
    body: "A TensorFlow and Keras experiment uses a lightweight CycleGAN to augment chest X-rays, then compares classifiers including MobileNetV2, SimpleCNN and ResNet50. It includes a teacher–student distillation workflow, confusion matrices and exported evaluation artifacts. This is an experimental research pipeline.",
    stack: ["Python", "TensorFlow", "Keras", "CycleGAN"],
    repository: github + "Corona-virus-detection-Using-Augmented-dataset",
    sourceNote: "CV in ZIP and repository README.",
  },
  {
    slug: "l-essence",
    title: "L’Essence",
    language: "PHP",
    tags: ["PHP", "Web"],
    description:
      "A fragrance brand website with an editor-managed product catalogue.",
    body: "I built a full-stack brand website with a Laravel admin panel so staff can update products and content themselves. Products render dynamically through the website’s REST endpoints.",
    stack: ["Laravel", "PHP", "MySQL", "HTML", "CSS", "JavaScript"],
    repository: github + "L-essence",
    sourceNote:
      "CV PDF inside CV.zip. The repository README is the Laravel scaffold.",
  },
];
