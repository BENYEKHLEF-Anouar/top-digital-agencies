/**
 * index digital - Directory & Blog listicle engine
 * Custom JS supporting bilingual translations (Polylang preview),
 * dynamic routing views, interactive matching wizard (Sortlist style),
 * command palette (⌘K), and animated interactive elements.
 */

document.addEventListener("DOMContentLoaded", () => {
  initRouting();
  initFiltering();
  initTranslations();
  initContactForm();
  initCommandPalette();
  initMatchingWizard();
  initBlogPosts();
  initAgencyCards();
  initQueryParamLoading();
  initReveals();
});

function initQueryParamLoading() {
  const params = new URLSearchParams(window.location.search);
  
  // If we are on agency.html page
  if (window.location.pathname.includes("agency.html")) {
    const agencyId = params.get("id") || "rmd";
    renderAgencyProfile(agencyId);
  }

  // If we are on article.html page
  if (window.location.pathname.includes("article.html")) {
    const postId = params.get("id") || "1";
    if (window.loadPostContent) {
      window.loadPostContent(postId);
      const articleSection = document.getElementById("article");
      if (articleSection) {
        articleSection.setAttribute("data-active-post-id", postId);
      }
    }
  }
}

// ========================================
// AGENCY DATA STORE
// ========================================
const AGENCY_DATA = {
  rmd: {
    id: "rmd",
    rank: "01",
    name: "RMD Marketing",
    logo: "rmd",
    logoStyle: "background-color: oklch(50% 0.20 250 / 0.1); color: var(--color-accent);",
    rating: 4.9,
    stars: 5,
    reviewCount: 18,
    usp: "high-performance seo & digital growth for b2b & enterprise.",
    description: "rmd is a data-driven marketing agency specializing in SEO, paid search (PPC), and analytics. they help B2B companies and enterprise brands grow organic traffic and run high-converting ad campaigns backed by proper tracking.",
    services: ["seo", "ppc", "tech"],
    cities: ["paris", "casablanca"],
    tags: ["featured"],
    budget: "5 000 €+",
    employees: "10 – 49",
    location: "paris, fr",
    audit: "200 OK",
    website: "https://rmd-agency.example.com",
    telemetry: { speed: 98, vitals: "pass", code: "99%" },
    portfolio: [
      { title: "saas seo drive", tag: "seo audit" },
      { title: "analytics tracking setup", tag: "data infra" },
      { title: "google ads scale", tag: "ppc search" }
    ]
  },
  pixa: {
    id: "pixa",
    rank: "02",
    name: "Pixagram",
    logo: "pixa",
    logoStyle: "background-color: oklch(62% 0.18 20 / 0.1); color: var(--color-accent-2);",
    rating: 4.8,
    stars: 5,
    reviewCount: 14,
    usp: "social-first branding, creator campaigns & modern visual strategy.",
    description: "pixagram (pixa) is an innovative digital agency built for visual impact. they specialize in brand design, social media campaigns, content execution, and custom influencer programs that capture market attention and build long-term brand equity.",
    services: ["social", "branding"],
    cities: ["paris", "lyon"],
    tags: ["featured"],
    budget: "2 000 €+",
    employees: "10 – 49",
    location: "paris, fr",
    audit: "200 OK",
    website: "https://pixagram-brand.example.com",
    telemetry: { speed: 95, vitals: "pass", code: "96%" },
    portfolio: [
      { title: "creator campaigns", tag: "social growth" },
      { title: "visual brand refresh", tag: "brand design" },
      { title: "ecommerce media buys", tag: "social ads" }
    ]
  },
  cybercite: {
    id: "cybercite",
    rank: "03",
    name: "CyberCité",
    logo: "cc",
    logoStyle: "",
    rating: 4.7,
    stars: 4,
    reviewCount: 28,
    usp: "historical search engine marketing agency in france.",
    description: "cybercité operates as a long-standing seo and search engine advertising agency with multiple regional hubs. they provide search audits, link acquisition campaigns, and web analytical setups to mid-market french companies.",
    services: ["seo", "ppc"],
    cities: ["paris", "lyon"],
    tags: [],
    budget: "5 000 €+",
    employees: "50 – 249",
    location: "lyon, fr",
    audit: "200 OK",
    website: "https://www.cybercite.fr.example.com",
    telemetry: { speed: 88, vitals: "pass", code: "90%" },
    portfolio: [
      { title: "local content strategy", tag: "seo optimization" },
      { title: "google analytics audit", tag: "web analytics" },
      { title: "search ads campaign", tag: "sea search" }
    ]
  },
  junto: {
    id: "junto",
    rank: "04",
    name: "Junto",
    logo: "jn",
    logoStyle: "",
    rating: 4.6,
    stars: 4,
    reviewCount: 21,
    usp: "growth-oriented media buying & paid acquisition.",
    description: "junto specializes in high-scale performance advertising campaigns across google search, facebook, and platforms. they focus on landing page setups, attribution auditing, and optimizing target user acquisition cost metrics.",
    services: ["ppc", "social"],
    cities: ["paris"],
    tags: [],
    budget: "2 000 €+",
    employees: "10 – 49",
    location: "paris, fr",
    audit: "200 OK",
    website: "https://junto.fr.example.com",
    telemetry: { speed: 91, vitals: "pass", code: "94%" },
    portfolio: [
      { title: "media buying setups", tag: "facebook ads" },
      { title: "cro audit and landings", tag: "conversion rate" },
      { title: "attribution tracking", tag: "analytics setup" }
    ]
  },
  adsup: {
    id: "adsup",
    rank: "05",
    name: "Ad's Up",
    logo: "au",
    logoStyle: "",
    rating: 4.5,
    stars: 4,
    reviewCount: 32,
    usp: "digital acquisition consulting and google ads management.",
    description: "ad's up is an independent consulting agency that drives sea, display advertising, and product listing ads (pla). they combine conversion rate optimization (cro) audits with search marketing channels.",
    services: ["ppc", "seo"],
    cities: ["paris", "lyon"],
    tags: [],
    budget: "5 000 €+",
    employees: "50 – 249",
    location: "paris, fr",
    audit: "200 OK",
    website: "https://ads-up.fr.example.com",
    telemetry: { speed: 92, vitals: "pass", code: "93%" },
    portfolio: [
      { title: "google shopping campaign", tag: "sea shopping" },
      { title: "conversion flow audit", tag: "cro consulting" },
      { title: "search branding ads", tag: "google ads" }
    ]
  }
};

// Star SVG template
function starSVG(filled) {
  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="star-icon${filled ? '' : ' star-icon--empty'}"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>`;
}

function renderStars(filled, total = 5) {
  let html = '';
  for (let i = 0; i < total; i++) {
    html += starSVG(i < filled);
  }
  return html;
}

// SERVICE LABEL MAP
const SERVICE_LABELS = {
  seo: "SEO",
  ppc: "PPC",
  social: "Social",
  branding: "Branding",
  tech: "Tech"
};

// ========================================
// AGENCY PROFILE RENDERER
// ========================================
function renderAgencyProfile(agencyId) {
  const a = AGENCY_DATA[agencyId];
  if (!a) return;

  const container = document.getElementById("agency-profile-content");
  if (!container) return;

  const serviceTags = a.services.map(s => `<span class="profile-tag">${SERVICE_LABELS[s] || s}</span>`).join("");
  const featuredTag = a.tags.includes("featured") ? `<span class="profile-tag profile-tag--accent">featured</span>` : "";

  const portfolioCards = a.portfolio.map(p => `
    <div class="profile-portfolio-card">
      <div class="terminal-header">
        <div class="terminal-dots">
          <span class="terminal-dot red"></span>
          <span class="terminal-dot yellow"></span>
          <span class="terminal-dot green"></span>
        </div>
        <span class="terminal-title">highlight_case_study.sh</span>
      </div>
      <div class="profile-portfolio-card__inner">
        <span class="profile-portfolio-card__title">${p.title}</span>
        <span class="profile-portfolio-card__tag">${p.tag}</span>
      </div>
    </div>
  `).join("");

  container.innerHTML = `
    <!-- Back button -->
    <button class="profile-back-btn" id="profile-back-btn">
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
      back to directory
    </button>

    <!-- Profile Header with blueprint background and accents -->
    <div class="profile-header">
      <div class="profile-header__grid-overlay"></div>
      <div class="profile-header__top">
        <div class="profile-logo" style="${a.logoStyle}">${a.logo}</div>
        <div class="profile-header__info">
          <div class="profile-header__title-row">
            <h1 class="profile-name">${a.name}</h1>
            <span class="profile-rank">#${a.rank}</span>
          </div>
          <div class="profile-rating-row">
            <span class="profile-rating-badge">${a.rating} ★</span>
            <span class="profile-reviews-count">${a.reviewCount} verified reviews</span>
          </div>
          <p class="profile-usp">${a.usp}</p>
          <div class="profile-tags">
            ${featuredTag}
            ${serviceTags}
          </div>
        </div>
      </div>
    </div>

    <!-- Main split layout: content vs sticky sidebar -->
    <div class="profile-layout-split">
      
      <!-- Main Content Column -->
      <div class="profile-main-column">
        <!-- Telemetry Audit Dashboard -->
        <div class="profile-telemetry-dashboard">
          <h3 class="profile-column-subtitle">technical audit telemetry</h3>
          <div class="telemetry-bar-grid">
            <div class="telemetry-bar-row">
              <span class="bar-label">google pagespeed score</span>
              <div class="bar-track">
                <div class="bar-fill speed" style="width: ${a.telemetry.speed}%;"></div>
              </div>
              <span class="bar-value speed-val">${a.telemetry.speed}/100</span>
            </div>
            <div class="telemetry-bar-row">
              <span class="bar-label">core web vitals</span>
              <span class="vitals-badge ${a.telemetry.vitals}">${a.telemetry.vitals}</span>
            </div>
            <div class="telemetry-bar-row">
              <span class="bar-label">code cleanliness index</span>
              <div class="bar-track">
                <div class="bar-fill code" style="width: ${a.telemetry.code};"></div>
              </div>
              <span class="bar-value">${a.telemetry.code}</span>
            </div>
          </div>
        </div>

        <!-- About block -->
        <div class="profile-about-block">
          <h3 class="profile-column-subtitle">agency profile</h3>
          <p class="profile-description">${a.description}</p>
        </div>

        <!-- Case study block -->
        <div class="profile-portfolio-block">
          <h3 class="profile-column-subtitle">portfolio highlights</h3>
          <div class="profile-portfolio-grid">
            ${portfolioCards}
          </div>
        </div>
      </div>

      <!-- Sticky metrics & CTAs sidebar -->
      <aside class="profile-sidebar-column">
        <div class="profile-sidebar-card">
          <div class="sidebar-audit-header">
            <span class="audit-dot"></span>
            <span class="audit-status-label">audited & verified</span>
          </div>
          
          <div class="sidebar-metrics-list">
            <div class="sidebar-metric-row">
              <span class="row-label">min. budget</span>
              <span class="row-val font-mono">${a.budget}</span>
            </div>
            <div class="sidebar-metric-row">
              <span class="row-label">employees</span>
              <span class="row-val font-mono">${a.employees}</span>
            </div>
            <div class="sidebar-metric-row">
              <span class="row-label">location</span>
              <span class="row-val font-mono">${a.location}</span>
            </div>
            <div class="sidebar-metric-row">
              <span class="row-label">site audit status</span>
              <span class="row-val font-mono status-pass">${a.audit}</span>
            </div>
          </div>

          <div class="sidebar-cta-group">
            <a href="${a.website}" rel="dofollow" target="_blank" class="btn btn--primary sidebar-cta-btn font-mono">visit website →</a>
            <button class="btn btn--soft sidebar-cta-btn" data-trigger-view="contact">request proposal</button>
          </div>
        </div>
      </aside>
    </div>
  `;

  // Bind back button
  const backBtn = document.getElementById("profile-back-btn");
  if (backBtn) {
    backBtn.addEventListener("click", () => {
      window.location.href = "directory.html";
    });
  }

  // Bind the contact trigger on the profile
  container.querySelectorAll("[data-trigger-view]").forEach(trigger => {
    trigger.addEventListener("click", (e) => {
      e.preventDefault();
      const target = trigger.getAttribute("data-trigger-view");
      window.location.href = `${target}.html`;
    });
  });
}

// ========================================
// AGENCY CARDS CLICK HANDLER
// ========================================
function initAgencyCards() {
  const cards = document.querySelectorAll(".agency-card[data-agency-id]");
  cards.forEach(card => {
    card.addEventListener("click", () => {
      const agencyId = card.getAttribute("data-agency-id");
      window.location.href = `agency.html?id=${agencyId}`;
    });
  });
}


// Translation Database (FR/EN)
const translations = {
  en: {
    heroTitle: "the independent index of digital marketing agencies, <em>ranked</em>.",
    heroLede: "a verified directory tracking performant agencies in france. no page builder drift, no sponsored rankings. just clean, search-first results.",
    filterService: "service",
    filterCity: "city",
    filterAll: "all",
    methodologyBoxTitle: "how we audit and rank agencies",
    methodologyBoxLede: "to protect index integrity, our rankings are driven by technical seo performance, client review logs, and organic tracking methodologies.",
    readMethodology: "read our methodology",
    visitWebsite: "visit website",
    requestBrief: "request proposal",
    sendRequest: "send request",
    contactTitle: "request agency matching or updates.",
    contactLede: "are you an agency seeking verification or a client looking for a matched marketing partner? reach out below.",
    contactName: "your name",
    contactEmail: "email address",
    contactWebsite: "agency website",
    contactMessage: "details or brief",
    successHeading: "request submitted.",
    successBody: "our team will verify the details and reply within 48 hours.",
    blogTitle: "marketing insights & listicles.",
    blogLede: "practical breakdowns on seo, branding, and local positioning, written by our lead editors.",
    methodologyPageTitle: "our evaluation standards.",
    methodologyPageLede: "independence matters. we evaluate every agency across three key areas to make sure our rankings stay honest and useful.",
    m1Title: "technical audit",
    m1Desc: "we run automated site audits to check page speed, metadata, and Core Web Vitals — the metrics Google actually uses to rank websites.",
    m2Title: "verified client reviews",
    m2Desc: "every review goes through an identity check. we filter out self-reviews, fake testimonials, and competitor sabotage.",
    m3Title: "organic growth tracking",
    m3Desc: "we track how well agencies rank for their own keywords. if they can't get results for themselves, why would they get results for you?",
    readTime: "min read",
    authorLabel: "by",
    postedLabel: "on"
  },
  fr: {
    heroTitle: "l’index indépendant des agences de marketing digital, <em>classées</em>.",
    heroLede: "un annuaire vérifié répertoriant les agences performantes en france. pas de classement sponsorisé, pas de triche. que du seo et des résultats réels.",
    filterService: "service",
    filterCity: "ville",
    filterAll: "tous",
    methodologyBoxTitle: "notre méthode de classement",
    methodologyBoxLede: "pour protéger l’intégrité de l’index, nos classements dépendent des performances seo réelles, des retours clients et d’audits indépendants.",
    readMethodology: "découvrir la méthodologie",
    visitWebsite: "voir le site",
    requestBrief: "demander un devis",
    sendRequest: "envoyer la demande",
    contactTitle: "demander un audit ou un partenariat.",
    contactLede: "vous êtes une agence souhaitant être listée ou un client cherchant le bon partenaire ? contactez-nous ci-dessous.",
    contactName: "votre nom",
    contactEmail: "adresse email",
    contactWebsite: "site internet de l’agence",
    contactMessage: "détails du projet ou brief",
    successHeading: "demande soumise avec succès.",
    successBody: "notre équipe analysera les informations et vous répondra sous 48 heures.",
    blogTitle: "analyses marketing & sélections.",
    blogLede: "décryptages pratiques sur le seo, le branding et le positionnement local rédigés par nos éditeurs.",
    methodologyPageTitle: "nos standards d’évaluation.",
    methodologyPageLede: "l'indépendance, c'est essentiel. nous évaluons chaque agence sur trois critères pour garantir des classements honnêtes et utiles.",
    m1Title: "audit technique",
    m1Desc: "nous effectuons des audits automatisés pour vérifier la vitesse, les balises et les Core Web Vitals — les métriques que Google utilise vraiment.",
    m2Title: "avis clients vérifiés",
    m2Desc: "chaque avis passe par une vérification d'identité. nous filtrons les faux témoignages et le spam des concurrents.",
    m3Title: "suivi de la croissance organique",
    m3Desc: "nous suivons le positionnement des agences sur leurs propres mots-clés. si elles n'obtiennent pas de résultats pour elles-mêmes, pourquoi le feraient-elles pour vous ?",
    readTime: "min de lecture",
    authorLabel: "par",
    postedLabel: "le"
  }
};

let currentLang = "en";

// Dynamic view switcher (SPA mockup routing)
function initRouting() {
  const pathname = window.location.pathname;
  let activeTarget = "home";
  if (pathname.includes("directory.html") || pathname.includes("agency.html")) {
    activeTarget = "directory";
  } else if (pathname.includes("blog.html") || pathname.includes("article.html")) {
    activeTarget = "blog";
  } else if (pathname.includes("methodology.html")) {
    activeTarget = "methodology";
  } else if (pathname.includes("contact.html")) {
    activeTarget = "contact";
  }

  const navLinks = document.querySelectorAll(".nav-link");
  navLinks.forEach(link => {
    const href = link.getAttribute("href");
    if (href && href.includes(activeTarget + ".html")) {
      link.classList.add("active");
    } else if (activeTarget === "home" && href && (href.includes("index.html") || href === "./" || href === "")) {
      link.classList.add("active");
    } else {
      link.classList.remove("active");
    }
  });

  // Dynamic view switcher fallback for compatibility
  window.appRouter = {
    switchView: function(targetId) {
      if (!targetId) return;
      if (targetId === "home") {
        window.location.href = "index.html";
      } else if (targetId === "agency-profile") {
        window.location.href = "directory.html";
      } else {
        window.location.href = targetId + ".html";
      }
    }
  };

  // Wordmark logo and footer logo click routing
  const logo = document.getElementById("wordmark-logo");
  if (logo) {
    logo.addEventListener("click", () => {
      window.location.href = "index.html";
    });
  }

  const footerLogo = document.getElementById("footer-logo");
  if (footerLogo) {
    footerLogo.addEventListener("click", () => {
      window.location.href = "index.html";
    });
  }

  // Direct triggering targets from cards
  document.querySelectorAll("[data-trigger-view]").forEach(trigger => {
    trigger.addEventListener("click", (e) => {
      e.preventDefault();
      const target = trigger.getAttribute("data-trigger-view");
      window.appRouter.switchView(target);
    });
  });

  // Connect homepage CTAs to directory view
  const browseBtn = document.getElementById("home-browse-btn");
  if (browseBtn) {
    browseBtn.addEventListener("click", () => {
      window.location.href = "directory.html";
    });
  }

  const featureSearchTrigger = document.getElementById("feature-search-trigger");
  if (featureSearchTrigger) {
    featureSearchTrigger.addEventListener("click", () => {
      window.location.href = "directory.html";
    });
  }

  const viewAllBtn = document.getElementById("view-all-specialties-btn");
  if (viewAllBtn) {
    viewAllBtn.addEventListener("click", () => {
      window.location.href = "directory.html";
    });
  }
}
function initFiltering() {
  // Connect homepage direct shortcuts to filters & route to directory
  document.querySelectorAll("[data-direct-category]").forEach(shortcut => {
    shortcut.addEventListener("click", () => {
      const category = shortcut.getAttribute("data-direct-category");
      window.location.href = `directory.html?service=${category}`;
    });
  });

  document.querySelectorAll("[data-direct-city]").forEach(shortcut => {
    shortcut.addEventListener("click", () => {
      const city = shortcut.getAttribute("data-direct-city");
      window.location.href = `directory.html?city=${city}`;
    });
  });

  // Exit if not on directory page
  const serviceTrigger = document.getElementById("service-filter-trigger");
  if (!serviceTrigger) return;

  const serviceItems = document.querySelectorAll(".filter-dropdown-item[data-service]");
  const cityItems = document.querySelectorAll(".filter-dropdown-item[data-city]");
  const agencyCards = document.querySelectorAll(".agency-card");
  const cityTrigger = document.getElementById("city-filter-trigger");
  const serviceMenu = document.getElementById("service-dropdown-menu");
  const cityMenu = document.getElementById("city-dropdown-menu");

  let activeFilters = {
    service: "all",
    city: "all"
  };

  // Toggle Dropdowns
  serviceTrigger.addEventListener("click", (e) => {
    e.stopPropagation();
    serviceTrigger.classList.toggle("open");
    serviceMenu.classList.toggle("show");
    
    // Close other dropdown
    if (cityTrigger) {
      cityTrigger.classList.remove("open");
      cityMenu.classList.remove("show");
    }
  });

  if (cityTrigger && cityMenu) {
    cityTrigger.addEventListener("click", (e) => {
      e.stopPropagation();
      cityTrigger.classList.toggle("open");
      cityMenu.classList.toggle("show");
      
      // Close other dropdown
      serviceTrigger.classList.remove("open");
      serviceMenu.classList.remove("show");
    });
  }

  // Close dropdowns on outside click
  document.addEventListener("click", () => {
    serviceTrigger.classList.remove("open");
    serviceMenu.classList.remove("show");
    if (cityTrigger) {
      cityTrigger.classList.remove("open");
      cityMenu.classList.remove("show");
    }
  });

  function applyFilters() {
    agencyCards.forEach(card => {
      const services = card.getAttribute("data-services").split(" ");
      const cities = card.getAttribute("data-cities").split(" ");

      const serviceMatch = activeFilters.service === "all" || services.includes(activeFilters.service);
      const cityMatch = activeFilters.city === "all" || cities.includes(activeFilters.city);

      if (serviceMatch && cityMatch) {
        card.style.display = "flex";
      } else {
        card.style.display = "none";
      }
    });
  }

  serviceItems.forEach(item => {
    item.addEventListener("click", () => {
      serviceItems.forEach(b => b.classList.remove("active"));
      item.classList.add("active");
      const service = item.getAttribute("data-service");
      activeFilters.service = service;
      
      // Update label
      const label = document.getElementById("current-service-val");
      if (label) label.textContent = item.textContent.trim();
      
      applyFilters();
    });
  });

  cityItems.forEach(item => {
    item.addEventListener("click", () => {
      cityItems.forEach(b => b.classList.remove("active"));
      item.classList.add("active");
      const city = item.getAttribute("data-city");
      activeFilters.city = city;
      
      // Update label
      const label = document.getElementById("current-city-val");
      if (label) label.textContent = item.textContent.trim();
      
      applyFilters();
    });
  });

  // Parse query parameters on load of directory.html
  const params = new URLSearchParams(window.location.search);
  const urlService = params.get("service");
  const urlCity = params.get("city");
  const urlMatching = params.get("matching");

  if (urlService) {
    activeFilters.service = urlService;
    serviceItems.forEach(item => {
      if (item.getAttribute("data-service") === urlService) {
        item.classList.add("active");
        const serviceLabel = document.getElementById("current-service-val");
        if (serviceLabel) serviceLabel.textContent = item.textContent.trim();
      } else {
        item.classList.remove("active");
      }
    });
  }

  if (urlCity) {
    activeFilters.city = urlCity;
    cityItems.forEach(item => {
      if (item.getAttribute("data-city") === urlCity) {
        item.classList.add("active");
        const cityLabel = document.getElementById("current-city-val");
        if (cityLabel) cityLabel.textContent = item.textContent.trim();
      } else {
        item.classList.remove("active");
      }
    });
  }

  applyFilters();

  // Handle Matchmaking wizard highlighting and scroll on load
  if (urlMatching === "true" && urlService) {
    let firstMatch = null;
    agencyCards.forEach(card => {
      const services = card.getAttribute("data-services").split(" ");
      if (services.includes(urlService)) {
        if (!firstMatch) firstMatch = card;
        card.style.outline = "2px solid var(--color-accent)";
        card.style.boxShadow = "0 0 24px var(--color-focus)";
        setTimeout(() => {
          card.style.outline = "none";
          card.style.boxShadow = "none";
        }, 3000);
      }
    });

    if (firstMatch) {
      setTimeout(() => {
        firstMatch.scrollIntoView({ behavior: "smooth", block: "center" });
      }, 300);
    }
  }
}

// Polylang Translator
function initTranslations() {
  const langBtns = document.querySelectorAll(".lang-btn");

  function translatePage(lang) {
    currentLang = lang;
    localStorage.setItem("selectedLanguage", lang);
    
    langBtns.forEach(btn => {
      if (btn.getAttribute("data-lang") === lang) {
        btn.classList.add("active");
      } else {
        btn.classList.remove("active");
      }
    });

    document.querySelectorAll("[data-translate]").forEach(el => {
      const key = el.getAttribute("data-translate");
      if (translations[lang][key]) {
        el.innerHTML = translations[lang][key];
      }
    });

    document.querySelectorAll("[data-translate-placeholder]").forEach(el => {
      const key = el.getAttribute("data-translate-placeholder");
      if (translations[lang][key]) {
        el.setAttribute("placeholder", translations[lang][key]);
      }
    });

    document.querySelectorAll("[data-lang-block]").forEach(block => {
      if (block.getAttribute("data-lang-block") === lang) {
        block.style.display = "block";
      } else {
        block.style.display = "none";
      }
    });

    // Update active blog post translations if present
    const articleSection = document.getElementById("article");
    if (articleSection) {
      const activePostId = articleSection.getAttribute("data-active-post-id");
      if (activePostId && window.loadPostContent) {
        window.loadPostContent(activePostId);
      }
    }
  }

  langBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      const lang = btn.getAttribute("data-lang");
      translatePage(lang);
    });
  });

  const savedLang = localStorage.getItem("selectedLanguage") || "en";
  translatePage(savedLang);
}

// Contact form trigger + celebration
function initContactForm() {
  const form = document.querySelector(".contact-form");
  const overlay = document.querySelector(".form-success-overlay");
  
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const submitBtn = form.querySelector('button[type="submit"]');
    const rect = submitBtn.getBoundingClientRect();
    const x = rect.left + rect.width / 2;
    const y = rect.top + rect.height / 2;
    
    createStarBurst(x, y);

    setTimeout(() => {
      overlay.classList.add("show");
      form.reset();
    }, 450);
  });

  overlay.addEventListener("click", () => {
    overlay.classList.remove("show");
  });
}

function createStarBurst(x, y) {
  const container = document.createElement("div");
  container.className = "star-burst-container";
  container.style.left = `${x - 12 + window.scrollX}px`;
  container.style.top = `${y - 12 + window.scrollY}px`;

  const star = document.createElement("div");
  star.className = "star-burst";

  container.appendChild(star);
  document.body.appendChild(container);

  setTimeout(() => {
    container.remove();
  }, 450);
}

// Interactive Brief Matching Wizard (Sortlist matching flow)
function initMatchingWizard() {
  const wizard = document.getElementById("matching-wizard");
  if (!wizard) return;

  const modal = document.getElementById("matching-wizard-modal");
  const openBtns = [
    document.getElementById("home-start-brief-btn"),
    document.getElementById("footer-cta-start-btn")
  ];
  const closeBtn = document.getElementById("close-wizard-btn");

  const step1 = wizard.querySelector('[data-step="1"]');
  const step2 = wizard.querySelector('[data-step="2"]');
  const successStep = wizard.querySelector('[data-step="success"]');

  const nextBtn = step1.querySelector(".next-step-btn");
  const submitBtn = step2.querySelector(".submit-wizard-btn");

  let selections = {
    service: null,
    budget: null
  };

  // Step 1 buttons
  const step1Btns = step1.querySelectorAll(".step-option-btn");
  step1Btns.forEach(btn => {
    btn.addEventListener("click", () => {
      step1Btns.forEach(b => b.classList.remove("selected"));
      btn.classList.add("selected");
      selections.service = btn.getAttribute("data-value");
      nextBtn.disabled = false;
    });
  });

  // Step 2 buttons
  const step2Btns = step2.querySelectorAll(".step-option-btn");
  step2Btns.forEach(btn => {
    btn.addEventListener("click", () => {
      step2Btns.forEach(b => b.classList.remove("selected"));
      btn.classList.add("selected");
      selections.budget = btn.getAttribute("data-value");
      submitBtn.disabled = false;
    });
  });

  if (modal) {
    openBtns.forEach(btn => {
      if (btn) {
        btn.addEventListener("click", () => {
          modal.showModal();
          // Reset steps
          step1.classList.add("active");
          step2.classList.remove("active");
          successStep.classList.remove("active");
          nextBtn.disabled = true;
          submitBtn.disabled = true;
          step1Btns.forEach(b => b.classList.remove("selected"));
          step2Btns.forEach(b => b.classList.remove("selected"));
          selections = { service: null, budget: null };
        });
      }
    });

    if (closeBtn) {
      closeBtn.addEventListener("click", () => {
        modal.close();
      });
    }

    modal.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.close();
      }
    });
  }

  // Next step click
  nextBtn.addEventListener("click", () => {
    step1.classList.remove("active");
    step2.classList.add("active");
  });

  // Submit matching click
  submitBtn.addEventListener("click", () => {
    const rect = submitBtn.getBoundingClientRect();
    const x = rect.left + rect.width / 2;
    const y = rect.top + rect.height / 2;
    
    createStarBurst(x, y);

    setTimeout(() => {
      step2.classList.remove("active");
      successStep.classList.add("active");
      
      // Redirect to directory page with filter parameters
      setTimeout(() => {
        window.location.href = `directory.html?service=${selections.service}&matching=true`;
        if (modal) {
          modal.close();
        }
      }, 1500);

    }, 450);
  });
}

// Command Palette (⌘K) matching Cobalt tokens
function initCommandPalette() {
  const modal = document.getElementById("search-modal");
  const searchBtn = document.querySelector(".nav-search-btn");
  const searchInput = document.getElementById("search-input");
  const resultsContainer = document.getElementById("search-results");

  if (!modal || !searchBtn || !searchInput || !resultsContainer) return;

  const searchData = [
    { title: "RMD Marketing", type: "agency", url: "agency.html?id=rmd" },
    { title: "Pixagram", type: "agency", url: "agency.html?id=pixa" },
    { title: "CyberCité", type: "agency", url: "agency.html?id=cybercite" },
    { title: "Junto", type: "agency", url: "agency.html?id=junto" },
    { title: "Ad’s Up", type: "agency", url: "agency.html?id=adsup" },
    { title: "ranking methodology", type: "page", url: "methodology.html" },
    { title: "submit agency request", type: "page", url: "contact.html" },
    { title: "marketing blog listicle", type: "article", url: "article.html?id=1" }
  ];

  function openPalette() {
    modal.classList.add("open");
    searchInput.value = "";
    renderResults("");
    setTimeout(() => searchInput.focus(), 50);
  }

  function closePalette() {
    modal.classList.remove("open");
  }

  function renderResults(query) {
    resultsContainer.innerHTML = "";
    const filtered = searchData.filter(item => 
      item.title.toLowerCase().includes(query.toLowerCase())
    );

    if (filtered.length === 0) {
      resultsContainer.innerHTML = `<li class="cmd-palette__item" style="color: var(--color-ink-2); font-size: 12px; pointer-events: none;">no results found</li>`;
      return;
    }

    filtered.forEach((item, index) => {
      const li = document.createElement("li");
      li.className = `cmd-palette__item ${index === 0 ? 'selected' : ''}`;
      li.innerHTML = `
        <span class="cmd-palette__item-title">${item.title}</span>
        <span class="cmd-palette__item-meta">${item.type}</span>
      `;
      
      li.addEventListener("click", () => {
        closePalette();
        window.location.href = item.url;
      });

      resultsContainer.appendChild(li);
    });
  }

  window.addEventListener("keydown", (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key === "k") {
      e.preventDefault();
      openPalette();
    }
    if (e.key === "Escape") {
      closePalette();
    }
  });

  searchBtn.addEventListener("click", openPalette);

  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      closePalette();
    }
  });

  searchInput.addEventListener("input", (e) => {
    renderResults(e.target.value);
  });
}

// Bilingual Blog Post Database
const blogDatabase = {
  "1": {
    meta: {
      en: { date: "12 Jun 2026", readTime: "6 min read" },
      fr: { date: "12 Juin 2026", readTime: "6 min de lecture" }
    },
    title: {
      en: "the best seo agencies in paris in 2026",
      fr: "les meilleures agences seo à paris en 2026"
    },
    intro: {
      en: `
        <p>Selecting the right digital marketing agency in Paris is a crucial strategic step for growing your organic visibility. With hundreds of self-proclaimed SEO specialists in the capital, we have crawled and audited the technical setups of major agencies to bring you a factual ranking.</p>
        <p>Our audit methodology focuses heavily on raw performance parameters (PageSpeed, correct indexing, and Core Web Vitals) alongside client retention history. Below is our verified list for 2026, showcasing the absolute top choices for performance-led and branding-centric marketing.</p>
      `,
      fr: `
        <p>Choisir la bonne agence de marketing digital à Paris est une étape stratégique déterminante pour votre visibilité organique. Avec plusieurs centaines de prestataires sur le marché parisien, nous avons audité techniquement et passé au crible les portefeuilles d’agences afin de vous livrer un comparatif factuel.</p>
        <p>Notre méthodologie se concentre sur les performances réelles (Core Web Vitals, conformité d’indexation, audits de vitesse) et l’analyse de l’acquisition client. Voici notre classement des meilleures agences en 2026.</p>
      `
    },
    outro: {
      en: `
        <h2>making the final decision</h2>
        <p>If your goals are purely transactional, technical search optimization, and scaling PPC pipelines, we highly recommend working with <strong>RMD</strong>. If you are looking to run social-first campaigns and establish creative brand positioning, <strong>Pixagram</strong> is the ideal choice. Make sure to consult our ranking methodology details to verify how we compile these comparison cards.</p>
      `,
      fr: `
        <h2>prendre la décision finale</h2>
        <p>Si vos objectifs sont orientés sur le trafic organique pur, l’optimisation technique ou les campagnes payantes (SEA), l’agence <strong>RMD</strong> l’emporte de loin. Si vous souhaitez axer votre stratégie sur la notoriété, le design et les réseaux sociaux, <strong>Pixagram</strong> saura répondre à vos besoins créatifs.</p>
      `
    }
  },
  "2": {
    meta: {
      en: { date: "08 Jun 2026", readTime: "4 min read" },
      fr: { date: "08 Juin 2026", readTime: "4 min de lecture" }
    },
    title: {
      en: "choosing an agency: the traps of elementor drift",
      fr: "choisir une agence : les pièges de l’elementor drift"
    },
    intro: {
      en: `
        <p>Page speed and clean code are the pillars of modern Core Web Vitals. Yet, many agencies build client sites using bloated page builders like Elementor. This visual builder overhead introduces substantial CSS/JS drift that slows down your rankings.</p>
        <p>When selecting a marketing partner, it is critical to evaluate if they write clean, custom templates or simply drag-and-drop elements on bloated frameworks. Below is our list of vetted agencies that excel in delivering high-speed, builder-free architectures.</p>
      `,
      fr: `
        <p>La vitesse de chargement et le code propre sont les piliers des Core Web Vitals modernes. Pourtant, de nombreuses agences continuent de concevoir des sites clients avec des constructeurs de pages lourds comme Elementor. Cet overhead visuel crée des dérives techniques majeures.</p>
        <p>Lors de la sélection d'un partenaire, il est crucial d'analyser s'il produit du code sur-mesure ou s'il se contente d'assembler des briques surchargées. Voici notre sélection d'agences reconnues pour leurs architectures performantes, légères et sans builder.</p>
      `
    },
    outro: {
      en: `
        <h2>avoiding performance pitfalls</h2>
        <p>To secure top positions, prioritize agencies like <strong>RMD</strong> that enforce lightweight templates and custom styling over visual builders. For social execution that doesn't compromise on visual layout, <strong>Pixagram</strong> is highly recommended. Make sure to audit your potential agency's own site performance before signing.</p>
      `,
      fr: `
        <h2>éviter les pièges de performance</h2>
        <p>Pour sécuriser vos positions, privilégiez des agences comme <strong>RMD</strong> qui privilégient des thèmes épurés et du code sur-mesure aux builders lourds. Pour des campagnes créatives et fluides, <strong>Pixagram</strong> s'impose comme une référence. Pensez à auditer la vitesse du propre site de votre agence avant de signer !</p>
      `
    }
  },
  "3": {
    meta: {
      en: { date: "02 Jun 2026", readTime: "5 min read" },
      fr: { date: "02 Juin 2026", readTime: "5 min de lecture" }
    },
    title: {
      en: "how generative search (sge) redefines local seo",
      fr: "comment la recherche générative (sge) redéfinit le seo local"
    },
    intro: {
      en: `
        <p>AI-driven search engines are completely shifting how users discover local services. With Search Generative Experiences (SGE) directly answering queries, standard local listings are no longer enough. Brands need schema structure and off-site authority signals.</p>
        <p>To survive SGE, businesses must partner with agencies that understand structured data markup, semantic keywords, and brand mention authority. Below is our comparison list of agencies positioned to navigate this AI search revolution.</p>
      `,
      fr: `
        <p>Les moteurs de recherche guidés par l'IA modifient profondément la découverte des services de proximité. Avec la SGE résumant directement les intentions locales, le référencement local classique ne suffit plus. Les marques ont besoin de structures de données robustes et de signaux d'autorité.</p>
        <p>Pour surmonter cette révolution, s'associer à une agence maîtrisant la sémantique IA et le maillage d'autorité est indispensable. Voici notre sélection d'agences prêtes pour le SEO de nouvelle génération.</p>
      `
    },
    outro: {
      en: `
        <h2>adapting to the ai search landscape</h2>
        <p>If you need schema engineering and advanced search crawlablity setup, <strong>RMD</strong> is the gold standard for SGE optimization. For creator campaigns and brand mention velocity that fuels AI references, <strong>Pixagram</strong> provides the perfect leverage. Read our full methodology to see how we track these metrics.</p>
      `,
      fr: `
        <h2>s’adapter au paysage de recherche ia</h2>
        <p>Si vos objectifs sont orientés sur le trafic organique pur, l’optimisation technique ou les campagnes payantes (SEA), l’agence <strong>RMD</strong> l’emporte de loin. Si vous souhaitez axer votre stratégie sur la notoriété, le design et les réseaux sociaux, <strong>Pixagram</strong> saura répondre à vos besoins créatifs.</p>
      `
    }
  }
};

// Dynamic Blog Posts switching
function initBlogPosts() {
  const blogCards = document.querySelectorAll(".blog-card[data-post-id]");
  const articleSection = document.getElementById("article");
  
  if (!blogCards.length && !articleSection) return;

  function loadPost(postId) {
    const post = blogDatabase[postId];
    if (!post || !articleSection) return;

    // Update title
    const titleEl = articleSection.querySelector(".article-title");
    if (titleEl) {
      titleEl.innerHTML = currentLang === "fr" ? post.title.fr : post.title.en;
      titleEl.setAttribute("data-post-title-en", post.title.en);
      titleEl.setAttribute("data-post-title-fr", post.title.fr);
    }

    // Update date and read time
    const dateEl = articleSection.querySelector(".article-date");
    if (dateEl) {
      dateEl.innerHTML = currentLang === "fr" ? post.meta.fr.date : post.meta.en.date;
      dateEl.setAttribute("data-post-date-en", post.meta.en.date);
      dateEl.setAttribute("data-post-date-fr", post.meta.fr.date);
    }

    const readTimeEl = articleSection.querySelector(".article-read-time");
    if (readTimeEl) {
      readTimeEl.innerHTML = currentLang === "fr" ? post.meta.fr.readTime : post.meta.en.readTime;
      readTimeEl.setAttribute("data-post-readtime-en", post.meta.en.readTime);
      readTimeEl.setAttribute("data-post-readtime-fr", post.meta.fr.readTime);
    }

    // Update intros & outros
    const introEn = articleSection.querySelector(".article-prose-en");
    if (introEn) introEn.innerHTML = post.intro.en;
    
    const introFr = articleSection.querySelector(".article-prose-fr");
    if (introFr) introFr.innerHTML = post.intro.fr;

    const outroEn = articleSection.querySelector(".article-outro-en");
    if (outroEn) outroEn.innerHTML = post.outro.en;

    const outroFr = articleSection.querySelector(".article-outro-fr");
    if (outroFr) outroFr.innerHTML = post.outro.fr;

    // Trigger translation visibility
    updateActiveLanguageBlocks();
  }

  // Bind clicks
  if (blogCards.length) {
    blogCards.forEach(card => {
      card.addEventListener("click", () => {
        const postId = card.getAttribute("data-post-id");
        window.location.href = `article.html?id=${postId}`;
      });
    });
  }

  // Bind back button
  const articleBackBtn = document.getElementById("article-back-btn");
  if (articleBackBtn) {
    articleBackBtn.addEventListener("click", () => {
      window.location.href = "blog.html";
    });
  }

  // Export load function for translation hook
  window.loadPostContent = loadPost;
}

function updateActiveLanguageBlocks() {
  document.querySelectorAll("[data-lang-block]").forEach(block => {
    if (block.getAttribute("data-lang-block") === currentLang) {
      block.style.display = "block";
    } else {
      block.style.display = "none";
    }
  });
}

// Scroll Entrance Reveal animations
function initReveals() {
  const revealElements = document.querySelectorAll(".reveal");
  if (!revealElements.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-in");
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.05,
    rootMargin: "0px 0px -30px 0px"
  });

  revealElements.forEach((el) => {
    observer.observe(el);
  });
}
