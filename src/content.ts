// Personal facts: latest Google Drive CV, retrieved 21 October 2026.
// Location wording is the owner's explicit correction in this conversation.
export const person = {
  name: "Rafi Al Mahmud",
  location: "Dhaka / Chattogram, Bangladesh",
  shortLocation: "DHAKA / CHATTOGRAM, BD",
  email: "rafi.almahmud.007@gmail.com",
  phone: "+880 1974-616402",
  phoneHref: "tel:+8801974616402",
  github: "https://github.com/RafiALMahmud",
  linkedin: "https://linkedin.com/in/rafi-al-mahmud-498011271",
  siteUrl: "https://portfolio-9jtk.vercel.app",
  bio: "I’m a Computer Science and Engineering graduate from BRAC University. I build backend services, relational databases and web applications with Python and PHP. I’ve shipped a student magazine and a sports facility booking platform, and my thesis explores how object detectors can stay reliable in fog.",
  teaching:
    "I have tutored Programming Language 1 and Object-Oriented Programming and helped junior students with assignments and projects.",
  philosophy:
    "Programming is learned by writing programs. I work through small examples, change one thing, and ask what will happen. Real software gives those concepts a purpose: a booking overlap check for conditionals, or a magazine’s database for foreign keys.",
};

export const home = {
  headline: "Everybody’s code lies. I read the logs.",
  subline:
    "Backend developer. I build booking systems and web platforms, and I research object detection that still works in fog.",
};

export const skills: Record<string, string[]> = {
  Languages: ["Python", "PHP", "Java", "JavaScript / TypeScript", "SQL"],
  Backend: [
    "FastAPI",
    "Laravel",
    "REST APIs",
    "JWT authentication",
    "Role-based access control",
  ],
  Frontend: ["React", "HTML", "CSS", "Responsive interfaces"],
  "Data & ML": ["PyTorch", "TensorFlow", "YOLOv8-Nano", "MobileNetV3", "ONNX"],
  Databases: [
    "PostgreSQL",
    "MySQL",
    "Relational schema design",
    "Alembic migrations",
  ],
  Tooling: ["Docker", "Git / GitHub", "GitHub Actions", "Postman"],
};

export const research = {
  title:
    "WASE: Weather-Aware Surgical Evaluation for Fog-Robust Object Detection",
  subtitle:
    "A Zero-Annotation Pseudo-Labeling Framework with Physics-Based Quality Gating",
  supervisor: "Dr. Md. Khalilur Rahman", // Supplied website brief.
  status: "IN PREPARATION · ICCV",
  result:
    "8–12% gain in mAP@50 over the baseline, with inference around 17 ms in the reported test setup.",
  problem:
    "Object detection becomes less reliable in fog. A confident prediction can still be wrong, and collecting new manual annotations for every weather condition is expensive.",
  approach:
    "WASE adapts YOLOv8-Nano through a teacher–student self-training pipeline with an exponential moving average teacher. Successive generations of pseudo-labels are filtered using confidence and physics-based quality gating before retraining. The target-domain adaptation needs no additional manual annotation.",
  related:
    "I also explored AURA, a reinforcement-learning approach to image restoration, using detector confidence as the reward signal.",
  datasets:
    "Training uses 13,257 source images: 4,322 real foggy images from RTTS and 8,935 synthetic foggy images from Foggy Cityscapes. Surgical-win oversampling expands each generation to approximately 17,000 images. ACDC is held out for evaluation, using sequences GOPR0476, GP010476 and GP020475.",
  protocol:
    "The three-generation YOLOv8n pipeline completed in 9 hours 13 minutes on one NVIDIA RTX 5090. All compared models use the same IoU-based association tracker. Comparisons include the COCO-pretrained detector, a manually retrained baseline, full-image DCP and naive pseudo-labeling.",
  method: [
    [
      "Confidence triage",
      "Retain high-confidence predictions, discard sub-threshold noise, and route uncertain detections to localized enhancement.",
    ],
    [
      "Surgical restoration",
      "Apply Dark Channel Prior inversion to selected regions of interest, then map detections back and apply non-maximum suppression.",
    ],
    [
      "Quality gating",
      "A Detection Quality Score compares competing label layouts and retains the more complete set for student training.",
    ],
    [
      "Iterative adaptation",
      "Train a student on the pseudo-labels and conservatively update the teacher with an exponential moving average across three generations.",
    ],
  ],
  figures: [
    {
      src: "/images/wase/pipeline.webp",
      width: 1450,
      height: 642,
      alt: "WASE pipeline showing confidence triage, selective DCP enhancement, DQS selection, pseudo-label training and the EMA feedback loop.",
      caption:
        "Figure 4.1 · WASE pipeline. Extracted from CSE400.pdf, PDF page 41 (printed page 28).",
    },
    {
      src: "/images/wase/confidence-triage.webp",
      width: 1450,
      height: 816,
      alt: "A foggy driving scene with high-confidence, uncertain and sub-threshold detection boxes.",
      caption:
        "Figure 4.2 · Three-zone confidence triage. CSE400.pdf, PDF page 49 (printed page 36).",
    },
    {
      src: "/images/wase/teacher-predictions.webp",
      width: 1450,
      height: 408,
      alt: "Side-by-side raw teacher and ROI-DCP predictions: additional pedestrian detections appear after selective enhancement.",
      caption:
        "Figure 4.3, first example · Raw predictions versus surgical DCP. CSE400.pdf, PDF page 50 (printed page 37).",
    },
    {
      src: "/images/wase/surgical-detections.webp",
      width: 1450,
      height: 408,
      alt: "A second side-by-side comparison of raw and ROI-enhanced detections in a foggy street scene.",
      caption:
        "Figure 4.3, second example · Raw predictions versus surgical DCP. CSE400.pdf, PDF page 50 (printed page 37).",
    },
  ],
  temporalResults: [
    {
      condition: "WASE",
      ttfd: "0.069",
      tss: "0.551",
      first: "97.70%",
      missed: "157",
    },
    {
      condition: "Retrained GT",
      ttfd: "0.384",
      tss: "0.315",
      first: "89.65%",
      missed: "268",
    },
    {
      condition: "Base COCO",
      ttfd: "0.359",
      tss: "0.333",
      first: "89.08%",
      missed: "254",
    },
    {
      condition: "Global DCP",
      ttfd: "0.140",
      tss: "0.523",
      first: "95.88%",
      missed: "171",
    },
    {
      condition: "Naive",
      ttfd: "0.138",
      tss: "0.548",
      first: "97.10%",
      missed: "159",
    },
  ],
  limitations:
    "These results cover three ACDC fog sequences. Time-to-first-detection is measured on tracks eventually detected; never-detected tracks are reported separately. WASE’s mean flicker rate is 0.051, compared with 0.045 for the manually retrained baseline. Naive pseudo-labeling is competitive in lighter fog, and the strongest WASE latency gains appear in the heavier-fog sequences. These experiments do not establish deployment safety.",
};

export const results = [
  {
    value: "3.57",
    label: "CGPA",
    note: "B.Sc. CSE · BRAC University · Sep 2026",
  },
  {
    value: "116",
    label: "PASSING TESTS",
    note: "SportEvo backend · as of 14 Oct 2026",
  },
  {
    value: "2",
    label: "LIVE PLATFORMS",
    note: "mangostudent.com · sportevo.games",
  },
];
