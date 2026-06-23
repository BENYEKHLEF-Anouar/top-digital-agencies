let currentLang = "en";

const motionState = {
    initialized: false,
    reduced: false,
    observer: null,
    mutationFrame: null,
    customSelectClickBound: false,
    filterControlsBound: false
};

function canUseMotion() {
    return typeof gsap !== "undefined" && !motionState.reduced;
}

function injectMotionStyles() {
    if (document.getElementById("motion-enhancement-styles")) return;
    const style = document.createElement("style");
    style.id = "motion-enhancement-styles";
    style.textContent = `
        .motion-enhanced .nav-link {
            will-change: color;
        }
        .hero-focus-word,
        .hero-location-word,
        .challenge-focus-word,
        .challenge-time-word,
        .approach-focus-word,
        .approach-action-word {
            position: relative;
            display: inline-block;
            white-space: nowrap;
        }
        .hero-focus-word {
            color: #2563eb;
        }
        .challenge-focus-word {
            color: #dc2626;
        }
        .challenge-time-word {
            color: #0f172a;
        }
        .approach-focus-word {
            color: #2563eb;
        }
        .approach-action-word {
            color: #0f172a;
        }
        .hero-location-word::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0.02em;
            height: 0.12em;
            border-radius: 999px;
            background: #f59e0b;
            transform: scaleX(var(--hero-location-scale, 0));
            transform-origin: left center;
            z-index: -1;
        }
        .challenge-time-word::after {
            content: "";
            position: absolute;
            left: -0.04em;
            right: -0.04em;
            bottom: 0.04em;
            height: 0.16em;
            border-radius: 999px;
            background: rgba(220, 38, 38, 0.18);
            transform: scaleX(var(--challenge-time-scale, 0));
            transform-origin: left center;
            z-index: -1;
        }
        .approach-action-word::after {
            content: "";
            position: absolute;
            left: -0.04em;
            right: -0.04em;
            bottom: 0.04em;
            height: 0.16em;
            border-radius: 999px;
            background: rgba(37, 99, 235, 0.16);
            transform: scaleX(var(--approach-action-scale, 0));
            transform-origin: left center;
            z-index: -1;
        }
    `;
    document.head.appendChild(style);
}

function initMotionSystem() {
    motionState.reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    injectMotionStyles();

    if (!canUseMotion()) return;
    if (motionState.initialized) {
        enhanceDynamicContent(document);
        return;
    }

    motionState.initialized = true;
    document.body.classList.add("motion-enhanced");
    gsap.defaults({ ease: "power2.out", duration: 0.45 });

    if (typeof ScrollTrigger !== "undefined") {
        gsap.registerPlugin(ScrollTrigger);
    }

    animatePageEntrance();
    initChallengeTitleAnimation();
    initApproachTitleAnimation();
    enhanceDynamicContent(document);
    observeDynamicContent();
}

function animatePageEntrance() {
    const header = document.querySelector("header");
    const heroTitle = document.querySelector(".hero-title");
    const heroItems = document.querySelectorAll("main .section-label, main h1 + p, main .hero-title + p");
    const tl = gsap.timeline();

    if (header) {
        tl.from(header, { y: -12, autoAlpha: 0, duration: 0.32 });
    }

    if (heroTitle) {
        animateHeroTitle(heroTitle, tl);
    }

    if (heroItems.length) {
        tl.from(Array.from(heroItems).slice(0, 8), {
            y: 12,
            autoAlpha: 0,
            stagger: 0.035,
            duration: 0.42
        }, "-=0.15");
    }
}

function animateHeroTitle(heroTitle = document.querySelector(".hero-title"), timeline = null) {
    if (!canUseMotion() || !heroTitle) return;

    const focusWord = heroTitle.querySelector(".hero-focus-word");
    const locationWord = heroTitle.querySelector(".hero-location-word");
    const targets = [focusWord, locationWord].filter(Boolean);
    if (!targets.length) return;

    gsap.set(heroTitle, { autoAlpha: 1 });

    const run = timeline || gsap.timeline();
    run.fromTo(targets, {
        y: 12,
        autoAlpha: 0,
        scale: 0.985
    }, {
        y: 0,
        autoAlpha: 1,
        scale: 1,
        stagger: 0.08,
        duration: 0.42,
        ease: "back.out(1.5)",
        clearProps: "transform,scale"
    }, timeline ? "-=0.05" : 0);

    if (focusWord) {
        run.to(focusWord, {
            color: "#1d4ed8",
            textShadow: "0 8px 24px rgba(37, 99, 235, 0.14)",
            repeat: 1,
            yoyo: true,
            duration: 0.4,
            ease: "power2.inOut"
        }, timeline ? "-=0.12" : 0.15);
    }

    if (locationWord) {
        locationWord.style.setProperty("--hero-location-scale", 0);
        run.to(locationWord, {
            duration: 0.45,
            ease: "power2.out",
            onUpdate() {
                locationWord.style.setProperty("--hero-location-scale", this.progress());
            },
            onComplete() {
                locationWord.style.setProperty("--hero-location-scale", 1);
            }
        }, timeline ? "-=0.25" : 0.25);
    }
}

function enhanceDynamicContent(root = document) {
    if (!canUseMotion()) return;
    initChallengeTitleAnimation(root);
    initApproachTitleAnimation(root);
}

function initApproachTitleAnimation(root = document) {
    if (!canUseMotion() || typeof ScrollTrigger === "undefined") return;

    const title = root.querySelector("[data-translate='approachTitle']");
    if (!title || title.dataset.motionReady === "true") return;

    const focusWord = title.querySelector(".approach-focus-word");
    const actionWord = title.querySelector(".approach-action-word");
    const targets = [focusWord, actionWord].filter(Boolean);
    if (!targets.length) return;

    title.dataset.motionReady = "true";
    if (actionWord) actionWord.style.setProperty("--approach-action-scale", 0);
    gsap.set(targets, { autoAlpha: 1, y: 0, scale: 1, transformOrigin: "center bottom" });

    const tl = gsap.timeline({
        scrollTrigger: {
            trigger: title,
            start: "top 90%",
            end: "+=400",
            scrub: 0.4,
            once: false
        }
    });

    tl.fromTo(targets, {
        y: 8,
        scale: 0.99
    }, {
        y: 0,
        scale: 1.02,
        stagger: 0.05,
        duration: 0.32,
        ease: "power2.out",
    }, 0).to(targets, {
        scale: 1,
        duration: 0.25,
        ease: "power2.out"
    }, 0.35);

    if (actionWord) {
        tl.to(actionWord, {
            "--approach-action-scale": 1,
            duration: 0.45,
            ease: "power2.out"
        }, 0.12);
    }

    if (focusWord) {
        tl.fromTo(focusWord, {
            color: "#0f172a"
        }, {
            color: "#2563eb",
            duration: 0.38,
            ease: "power2.out"
        }, 0.05);
    }
}

function initChallengeTitleAnimation(root = document) {
    if (!canUseMotion() || typeof ScrollTrigger === "undefined") return;

    const title = root.querySelector("[data-translate='challengeTitle']");
    if (!title || title.dataset.motionReady === "true") return;

    const focusWord = title.querySelector(".challenge-focus-word");
    const timeWord = title.querySelector(".challenge-time-word");
    const targets = [focusWord, timeWord].filter(Boolean);
    if (!targets.length) return;

    title.dataset.motionReady = "true";
    if (timeWord) timeWord.style.setProperty("--challenge-time-scale", 0);
    gsap.set(targets, { autoAlpha: 1, y: 0, scale: 1, transformOrigin: "center bottom" });

    const tl = gsap.timeline({
        scrollTrigger: {
            trigger: title,
            start: "top 90%",
            end: "+=400",
            scrub: 0.4,
            once: false
        }
    });

    tl.fromTo(targets, {
        y: 8,
        scale: 0.99
    }, {
        y: 0,
        scale: 1.02,
        stagger: 0.05,
        duration: 0.32,
        ease: "power2.out",
    }, 0).to(targets, {
        scale: 1,
        duration: 0.25,
        ease: "power2.out"
    }, 0.35);

    if (timeWord) {
        tl.to(timeWord, {
            "--challenge-time-scale": 1,
            duration: 0.45,
            ease: "power2.out"
        }, 0.12);
    }

    if (focusWord) {
        tl.fromTo(focusWord, {
            color: "#0f172a"
        }, {
            color: "#dc2626",
            duration: 0.38,
            ease: "power2.out"
        }, 0.05);
    }
}

function observeDynamicContent() {
    if (motionState.observer) return;

    motionState.observer = new MutationObserver(mutations => {
        const hasAddedNodes = mutations.some(mutation => mutation.addedNodes.length > 0);
        if (!hasAddedNodes || motionState.mutationFrame) return;

        motionState.mutationFrame = requestAnimationFrame(() => {
            motionState.mutationFrame = null;
            enhanceDynamicContent(document);
        });
    });

    motionState.observer.observe(document.body, { childList: true, subtree: true });
}

function animateModalOpen(modal) {
    if (!canUseMotion() || !modal) return;
    gsap.fromTo(modal,
        { y: 18, scale: 0.985, opacity: 0 },
        { y: 0, scale: 1, opacity: 1, duration: 0.32, ease: "power3.out" }
    );
}

function animateModalClose(modal, onComplete) {
    if (!canUseMotion() || !modal) {
        onComplete();
        return;
    }

    gsap.to(modal, {
        y: 12,
        scale: 0.985,
        opacity: 0,
        duration: 0.22,
        ease: "power2.in",
        onComplete
    });
}

function openCustomSelectMenu(menu, trigger) {
    const wrapper = menu.closest(".custom-select-wrapper");
    if (wrapper) wrapper.style.zIndex = "80";
    menu.classList.remove("hidden");
    trigger.classList.add("open");
    if (canUseMotion()) {
        gsap.fromTo(menu,
            { y: -8, autoAlpha: 0, scale: 0.98 },
            { y: 0, autoAlpha: 1, scale: 1, duration: 0.2, ease: "power2.out" }
        );
    }
}

function closeCustomSelectMenu(menu, trigger) {
    const complete = () => {
        const wrapper = menu.closest(".custom-select-wrapper");
        if (wrapper) wrapper.style.zIndex = "";
        menu.classList.add("hidden");
        if (trigger) trigger.classList.remove("open");
    };

    if (canUseMotion() && !menu.classList.contains("hidden")) {
        gsap.to(menu, { y: -4, autoAlpha: 0, duration: 0.16, ease: "power2.in", onComplete: complete });
    } else {
        complete();
    }
}

function handleFilterControlChange(control) {
    if (!control || !control.id) return;

    if (control.id.startsWith("dir-filter-") && typeof runFiltering === "function") {
        runFiltering();
    }
}

function initSearchAndFilterControls() {
    if (motionState.filterControlsBound) return;
    motionState.filterControlsBound = true;

    const dirSearch = document.getElementById("dir-search-input");
    if (dirSearch && typeof runFiltering === "function") {
        dirSearch.addEventListener("input", runFiltering);
    }

    document.querySelectorAll("[id^='dir-filter-']").forEach(select => {
        select.addEventListener("change", () => handleFilterControlChange(select));
    });
}

// Global function to toggle/set language
function setLanguage(lang) {
    currentLang = lang;
    localStorage.setItem("indexDigitalLang", lang);

    // Toggle active classes on language selector buttons
    const btnEn = document.getElementById("lang-btn-en");
    const btnFr = document.getElementById("lang-btn-fr");
    if (btnEn && btnFr) {
        if (lang === "en") {
            btnEn.className = "px-2 py-1 rounded transition-colors bg-white text-slate-900 shadow-sm";
            btnFr.className = "px-2 py-1 rounded transition-colors text-slate-500 hover:text-slate-900";
        } else {
            btnFr.className = "px-2 py-1 rounded transition-colors bg-white text-slate-900 shadow-sm";
            btnEn.className = "px-2 py-1 rounded transition-colors text-slate-500 hover:text-slate-900";
        }
    }

    // Translate static elements
    document.querySelectorAll("[data-translate]").forEach(el => {
        const key = el.getAttribute("data-translate");
        if (TRANSLATIONS[lang] && TRANSLATIONS[lang][key]) {
            el.innerHTML = TRANSLATIONS[lang][key];
            if (key === "challengeTitle" || key === "approachTitle") {
                delete el.dataset.motionReady;
            }
        }
    });

    // Translate placeholders
    document.querySelectorAll("[data-translate-placeholder]").forEach(el => {
        const key = el.getAttribute("data-translate-placeholder");
        if (TRANSLATIONS[lang] && TRANSLATIONS[lang][key]) {
            el.setAttribute("placeholder", TRANSLATIONS[lang][key]);
        }
    });

    // Re-render dynamic blocks (if functions exist on the current page)
    if (typeof renderHomePicks === "function") renderHomePicks();
    if (typeof renderDirectoryCards === "function" && typeof runFiltering !== "function") renderDirectoryCards();
    if (typeof renderRankings === "function") renderRankings();

    // If dynamic profile page rendering function exists
    if (typeof populateProfileFromURL === "function") populateProfileFromURL();

    // Re-initialize custom select dropdowns to match translated option lists
    initCustomSelects();
    initSearchAndFilterControls();
    if (typeof runFiltering === "function") runFiltering();

    // Reload Lucide icons
    if (typeof lucide !== "undefined") {
        lucide.createIcons();
    }

    if (motionState.initialized) {
        requestAnimationFrame(() => {
            animateHeroTitle();
            initChallengeTitleAnimation();
            initApproachTitleAnimation();
            enhanceDynamicContent(document);
        });
    }
}

// Mobile menu toggle
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    if (menu) {
        menu.classList.toggle('hidden');
    }
}

// FAQ accordion toggler
function toggleFaq(button) {
    const content = button.nextElementSibling;
    const arrow = button.querySelector('[data-lucide="chevron-down"]');
    if (content) {
        content.classList.toggle('open');
        if (arrow) {
            if (content.classList.contains('open')) {
                arrow.style.transform = 'rotate(180deg)';
            } else {
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    }
}

// Star Burst feedback helper
function triggerStarBurst(x, y) {
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

// Contact submission handler
function handleContactSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById("contact-submit-btn");
    if (btn) {
        const rect = btn.getBoundingClientRect();
        triggerStarBurst(rect.left + rect.width / 2, rect.top + rect.height / 2);
    }

    setTimeout(() => {
        const thanksMsg = currentLang === "en"
            ? "Thank you for your message. We will respond within 24 hours."
            : "Merci pour votre message. Nous vous répondrons sous 24 heures.";
        alert(thanksMsg);
        const form = document.getElementById("contact-form");
        if (form) form.reset();
    }, 300);
}

// ==================== PALETTE SEARCH LOGIC ====================
const SEARCH_INDEX = [
    { title: "RMD", type: "agency", action: () => window.location.href = "profile.html?id=rmd" },
    { title: "Pixagram", type: "agency", action: () => window.location.href = "profile.html?id=pixagram" },
    { title: "MediaBoost", type: "agency", action: () => window.location.href = "profile.html?id=mediaboost" },
    { title: "DigitalWave", type: "agency", action: () => window.location.href = "profile.html?id=digitalwave" },
    { title: "NexaMedia", type: "agency", action: () => window.location.href = "profile.html?id=nexamedia" },
    { title: "Sahara Digital", type: "agency", action: () => window.location.href = "profile.html?id=saharadigital" },
    { title: "directory", type: "page", action: () => window.location.href = "directory.html" },
    { title: "rankings & leaderboards", type: "page", action: () => window.location.href = "rankings.html" },
    { title: "methodology & audits", type: "page", action: () => window.location.href = "methodology.html" },
    { title: "about company", type: "page", action: () => window.location.href = "about.html" },
    { title: "contact info", type: "page", action: () => window.location.href = "contact.html" },
    { title: "graphic charter & design system", type: "page", action: () => window.location.href = "charte.html" },
    { title: "blog & insights", type: "page", action: () => window.location.href = "blog.html" },
    { title: "Top Digital Marketing Agencies in Morocco (Article)", type: "article", action: () => window.location.href = "article.html?id=top-agencies" },
    { title: "Best SEO Agencies in Casablanca (Article)", type: "article", action: () => window.location.href = "article.html?id=seo-casablanca" },
    { title: "Social Media Agencies Compared (Article)", type: "article", action: () => window.location.href = "article.html?id=social-media-compared" }
];

function openSearchPalette() {
    const modal = document.getElementById("search-modal");
    if (!modal) return;

    // Reset input
    const input = document.getElementById("search-input");
    if (input) input.value = "";
    runPaletteSearch("");

    // Create or reuse backdrop
    let backdrop = document.getElementById("search-overlay-bg");
    if (!backdrop) {
        backdrop = document.createElement("div");
        backdrop.id = "search-overlay-bg";
        document.body.appendChild(backdrop);
    }
    backdrop.style.cssText = [
        "position:fixed", "inset:0",
        "background:rgba(15,23,42,0.5)",
        "backdrop-filter:blur(4px)",
        "-webkit-backdrop-filter:blur(4px)",
        "z-index:9998",
        "display:block"
    ].join(";");
    backdrop.onclick = closeSearchPalette;

    // Force modal visible with fixed positioning
    modal.setAttribute("open", "");
    modal.style.cssText = [
        "display:block",
        "position:fixed",
        "top:15vh",
        "left:50%",
        "transform:translateX(-50%)",
        "width:calc(100% - 2rem)",
        "max-width:36rem",
        "z-index:9999",
        "margin:0",
        "background:#ffffff",
        "border:1px solid #e2e8f0",
        "border-radius:0.75rem",
        "box-shadow:0 20px 60px -12px rgba(0,0,0,0.18),0 8px 24px -8px rgba(0,0,0,0.1)",
        "overflow:hidden",
        "padding:0",
        "outline:none"
    ].join(";");

    // Animate in
    if (canUseMotion()) {
        gsap.fromTo(backdrop, { opacity: 0 }, { opacity: 1, duration: 0.18 });
        gsap.fromTo(modal, { opacity: 0, y: 12 }, { opacity: 1, y: 0, duration: 0.25, ease: "power2.out" });
    }

    // Focus input
    setTimeout(() => { if (input) input.focus(); }, 60);
}

function closeSearchPalette() {
    const modal = document.getElementById("search-modal");
    const backdrop = document.getElementById("search-overlay-bg");

    const hide = () => {
        if (modal) {
            modal.removeAttribute("open");
            modal.style.cssText = "";
        }
        if (backdrop) backdrop.style.display = "none";
    };

    if (canUseMotion() && modal) {
        gsap.to(modal, { opacity: 0, y: 8, duration: 0.18, ease: "power2.in", onComplete: hide });
        if (backdrop) gsap.to(backdrop, { opacity: 0, duration: 0.18 });
    } else {
        hide();
    }
}

function runPaletteSearch(val) {
    const list = document.getElementById("search-results");
    if (!list) return;
    list.innerHTML = "";
    const query = val.toLowerCase().trim();

    const filtered = SEARCH_INDEX.filter(item => item.title.toLowerCase().includes(query));

    if (filtered.length === 0) {
        list.innerHTML = `<li class="p-4 text-slate-400 text-center" data-translate="paletteNoResults">no results found</li>`;
        return;
    }

    filtered.forEach(item => {
        const li = document.createElement("li");
        li.className = "flex justify-between items-center p-3 hover:bg-slate-50 cursor-pointer rounded-lg transition-colors";
        li.innerHTML = `
            <span class="font-semibold text-slate-800">${item.title}</span>
            <span class="text-[10px] uppercase font-bold text-slate-400 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded font-mono">${item.type}</span>
        `;
        li.onclick = () => {
            closeSearchPalette();
            item.action();
        };
        list.appendChild(li);
    });
}

// Keyboard listener for Command Palette (Ctrl+K / Cmd+K) + ESC to close
window.addEventListener("keydown", (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key === "k") {
        e.preventDefault();
        openSearchPalette();
    }
    if (e.key === "Escape") {
        const modal = document.getElementById("search-modal");
        if (modal && modal.hasAttribute("open")) {
            closeSearchPalette();
        }
    }
});

// Helper to render rating star SVGs
function getStarsHTML(rating) {
    let html = "";
    const floor = Math.floor(rating);
    for (let i = 1; i <= 5; i++) {
        if (i <= floor) {
            html += `<i data-lucide="star" class="w-3.5 h-3.5 text-amber-400 fill-amber-400 inline"></i>`;
        } else if (i - rating < 1) {
            html += `<i data-lucide="star-half" class="w-3.5 h-3.5 text-amber-400 fill-amber-400 inline"></i>`;
        } else {
            html += `<i data-lucide="star" class="w-3.5 h-3.5 text-slate-200 inline"></i>`;
        }
    }
    return html;
}

// ==================== RANK BADGE SYSTEM ====================
// Proper, professional rank badges — no raw "#", readable white text on color.
// Backgrounds are darkened so white text passes WCAG AA contrast.
const RANK_BADGE_TIERS = {
    1: { en: "Top Ranked", fr: "Mieux Classée", cls: "bg-amber-700", icon: "award" },
    2: { en: "Featured",   fr: "En Vedette",    cls: "bg-teal-700",  icon: "star" },
    3: { en: "Top Pick",   fr: "Recommandée",   cls: "bg-brand-600", icon: "sparkles" }
};

// Returns { label, cls, icon } for a given rank.
// Label always leads with the rank number (never a "#"), e.g. "Rank 1 · Top Ranked".
function getRankBadge(rank) {
    const rankWord = (currentLang === "fr" ? "Rang " : "Rank ") + rank;
    const tier = RANK_BADGE_TIERS[rank];
    if (tier) {
        return { label: `${rankWord} · ${tier[currentLang] || tier.en}`, cls: tier.cls, icon: tier.icon };
    }
    return { label: rankWord, cls: "bg-slate-700", icon: null };
}

// Returns ready-to-inject HTML for a rank badge pill (icon + label).
function rankBadgeHTML(rank, extraCls = "") {
    const b = getRankBadge(rank);
    const iconHTML = b.icon ? `<i data-lucide="${b.icon}" class="w-3 h-3"></i>` : "";
    return `<span class="${b.cls} ${extraCls} inline-flex items-center gap-1 text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-sm font-mono uppercase tracking-wide">${iconHTML}${b.label}</span>`;
}

// Custom Select Element Rebuilder
function initCustomSelects() {
    // Revert any previously initialized custom selects to avoid duplicates
    document.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
        const select = wrapper.querySelector('select');
        if (select) {
            select.style.display = '';
            wrapper.replaceWith(select);
        }
    });

    // Find all select elements on the page
    const selectElements = document.querySelectorAll('select');
    selectElements.forEach(select => {
        const wrapper = document.createElement('div');
        wrapper.className = 'relative custom-select-wrapper w-full';

        // Wrap native select
        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);
        select.style.display = 'none';

        // Create custom trigger button
        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.style.opacity = '';
        trigger.style.visibility = '';
        trigger.style.transform = '';

        // Set appropriate padding based on page section (contact form has larger select)
        const isContactSelect = select.closest('#contact-form') !== null;
        trigger.className = `custom-select-trigger flex items-center justify-between w-full bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 rounded-lg text-[13px] text-slate-700 shadow-sm focus:border-brand-600 focus:ring-2 focus:ring-brand-500/10 outline-none transition-all duration-200 cursor-pointer ${isContactSelect ? 'px-4 py-2.5' : 'pl-3.5 pr-4 py-2'}`;

        const label = document.createElement('span');
        label.className = 'custom-select-label truncate';

        const currentOpt = select.options[select.selectedIndex] || select.options[0];
        label.textContent = currentOpt ? currentOpt.textContent : "";

        const arrow = document.createElement('span');
        arrow.className = 'chevron-icon flex items-center flex-shrink-0 ml-2';
        arrow.innerHTML = '<i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>';

        trigger.appendChild(label);
        trigger.appendChild(arrow);
        wrapper.appendChild(trigger);

        // Create custom menu container
        const menu = document.createElement('div');
        menu.className = 'custom-select-options hidden absolute left-0 right-0 z-50 mt-1.5 bg-white/95 backdrop-blur-md border border-slate-200/80 rounded-xl shadow-[0_12px_32px_-8px_rgba(0,0,0,0.08)] max-h-60 overflow-y-auto p-1 font-sans';

        Array.from(select.options).forEach((opt, idx) => {
            const item = document.createElement('div');
            const isSelected = idx === select.selectedIndex;

            item.className = isSelected
                ? 'custom-select-option px-3.5 py-2 text-[13px] text-brand-700 bg-brand-50/50 font-semibold cursor-pointer flex items-center justify-between transition-all rounded-md mx-1 my-0.5'
                : 'custom-select-option px-3.5 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer flex items-center justify-between transition-all rounded-md mx-1 my-0.5';
            item.dataset.value = opt.value;
            item.dataset.index = idx;

            const itemText = document.createElement('span');
            itemText.textContent = opt.textContent;

            const checkIcon = document.createElement('span');
            checkIcon.className = `text-brand-600 flex items-center flex-shrink-0 ${isSelected ? 'block' : 'hidden'}`;
            checkIcon.innerHTML = '<i data-lucide="check" class="w-3.5 h-3.5"></i>';

            item.appendChild(itemText);
            item.appendChild(checkIcon);
            menu.appendChild(item);

            // Bind item click
            item.onclick = (e) => {
                e.stopPropagation();
                select.selectedIndex = idx;

                // Fire change event
                select.dispatchEvent(new Event('change', { bubbles: true }));
                handleFilterControlChange(select);

                // Close menu
                closeCustomSelectMenu(menu, trigger);
            };
        });

        wrapper.appendChild(menu);

        // Bind trigger click
        trigger.onclick = (e) => {
            e.stopPropagation();

            // Close all other dropdowns
            document.querySelectorAll('.custom-select-options').forEach(otherMenu => {
                if (otherMenu !== menu) {
                    closeCustomSelectMenu(otherMenu, otherMenu.previousElementSibling);
                }
            });
            document.querySelectorAll('.custom-select-trigger').forEach(otherTrigger => {
                if (otherTrigger !== trigger) {
                    otherTrigger.classList.remove('open');
                }
            });

            const isOpen = !menu.classList.contains('hidden');
            if (isOpen) {
                closeCustomSelectMenu(menu, trigger);
            } else {
                openCustomSelectMenu(menu, trigger);
            }
        };

        // Sync custom layout when native value changes (programmatically)
        if (select._changeHandler) {
            select.removeEventListener('change', select._changeHandler);
        }
        select._changeHandler = () => {
            const activeOpt = select.options[select.selectedIndex];
            if (activeOpt) {
                label.textContent = activeOpt.textContent;

                // Sync options lists checked status
                menu.querySelectorAll('.custom-select-option').forEach(item => {
                    const idxStr = item.dataset.index;
                    const isSel = idxStr === String(select.selectedIndex);

                    item.className = isSel
                        ? 'custom-select-option px-3.5 py-2 text-[13px] text-brand-700 bg-brand-50/50 font-semibold cursor-pointer flex items-center justify-between transition-all rounded-md mx-1 my-0.5'
                        : 'custom-select-option px-3.5 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer flex items-center justify-between transition-all rounded-md mx-1 my-0.5';

                    const check = item.querySelector('.text-brand-600');
                    if (check) {
                        check.className = `text-brand-600 flex items-center flex-shrink-0 ${isSel ? 'block' : 'hidden'}`;
                    }
                });
            }
        };
        select.addEventListener('change', select._changeHandler);
    });

    // Universal click-outside handler to close dropdowns
    if (!motionState.customSelectClickBound) {
        motionState.customSelectClickBound = true;
        document.addEventListener('click', () => {
            document.querySelectorAll('.custom-select-options').forEach(menu => {
                closeCustomSelectMenu(menu, menu.previousElementSibling);
            });
        });
    }
}

// Bind search palette click outside handler
document.addEventListener("DOMContentLoaded", () => {
    const searchModal = document.getElementById("search-modal");
    if (searchModal) {
        searchModal.addEventListener("click", (e) => {
            const rect = searchModal.getBoundingClientRect();
            if (e.clientX < rect.left || e.clientX > rect.right || e.clientY < rect.top || e.clientY > rect.bottom) {
                closeSearchPalette();
            }
        });
    }

    // Restore language preference
    const savedLang = localStorage.getItem("indexDigitalLang") || "en";
    setLanguage(savedLang);
    initSearchAndFilterControls();
    requestAnimationFrame(initMotionSystem);
});

window.enhanceDynamicContent = enhanceDynamicContent;
window.animateModalOpen = animateModalOpen;
window.animateModalClose = animateModalClose;
