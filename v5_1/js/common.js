let currentLang = "fr";

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
    gsap.defaults({ ease: "power3.out", duration: 0.45 });

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
        tl.from(header, { y: -12, autoAlpha: 0, duration: 0.28 });
    }

    if (heroTitle) {
        animateHeroTitle(heroTitle, tl);
    }

    if (heroItems.length) {
        tl.from(Array.from(heroItems).slice(0, 8), {
            y: 12,
            autoAlpha: 0,
            stagger: 0.025,
            duration: 0.32
        }, "-=0.1");
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
        scale: 0.97
    }, {
        y: 0,
        autoAlpha: 1,
        scale: 1,
        stagger: 0.07,
        duration: 0.3,
        ease: "back.out(1.4)"
    }, timeline ? "-=0.05" : 0);

    if (focusWord) {
        run.to(focusWord, {
            color: "#1d4ed8",
            textShadow: "0 8px 20px rgba(37, 99, 235, 0.15)",
            duration: 0.25,
            ease: "power2.out"
        }, timeline ? "-=0.1" : 0.15);
    }

    if (locationWord) {
        locationWord.style.setProperty("--hero-location-scale", 0);
        run.to(locationWord, {
            duration: 0.35,
            ease: "expo.out",
            onUpdate() {
                locationWord.style.setProperty("--hero-location-scale", this.progress());
            },
            onComplete() {
                locationWord.style.setProperty("--hero-location-scale", 1);
            }
        }, timeline ? "-=0.2" : 0.2);
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
            end: "+=280",
            scrub: 0.5,
            once: false
        }
    });

    tl.fromTo(targets, {
        y: 8,
        scale: 0.98
    }, {
        y: 0,
        scale: 1.03,
        stagger: 0.05,
        duration: 0.3,
        ease: "power2.out",
    }, 0).to(targets, {
        scale: 1,
        duration: 0.2,
        ease: "power2.out"
    }, 0.32);

    if (actionWord) {
        tl.to(actionWord, {
            "--approach-action-scale": 1,
            duration: 0.45,
            ease: "power2.out"
        }, 0.1);
    }

    if (focusWord) {
        tl.fromTo(focusWord, {
            color: "#0f172a"
        }, {
            color: "#2563eb",
            duration: 0.35,
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
            end: "+=280",
            scrub: 0.5,
            once: false
        }
    });

    tl.fromTo(targets, {
        y: 8,
        scale: 0.98
    }, {
        y: 0,
        scale: 1.03,
        stagger: 0.05,
        duration: 0.3,
        ease: "power2.out",
    }, 0).to(targets, {
        scale: 1,
        duration: 0.2,
        ease: "power2.out"
    }, 0.32);

    if (timeWord) {
        tl.to(timeWord, {
            "--challenge-time-scale": 1,
            duration: 0.45,
            ease: "power2.out"
        }, 0.1);
    }

    if (focusWord) {
        tl.fromTo(focusWord, {
            color: "#0f172a"
        }, {
            color: "#dc2626",
            duration: 0.35,
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
        { y: 18, scale: 0.985, autoAlpha: 0 },
        { y: 0, scale: 1, autoAlpha: 1, duration: 0.32, ease: "power3.out" }
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
        autoAlpha: 0,
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

    // Keep the document language attribute in sync for SEO & accessibility
    if (document.documentElement) {
        document.documentElement.lang = lang;
    }
    
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
    { title: "home", type: "page", action: () => window.location.href = "index.html" },
    { title: "about", type: "page", action: () => window.location.href = "about.html" },
    { title: "methodology & audits", type: "page", action: () => window.location.href = "methodology.html" },
    { title: "contact info", type: "page", action: () => window.location.href = "contact.html" },
    { title: "blog & insights", type: "page", action: () => window.location.href = "blog.html" },
    { title: "Top Digital Marketing Agencies in Morocco (Article)", type: "article", action: () => window.location.href = "article.html?id=top-agencies" },
    { title: "Best SEO Agencies in Casablanca (Article)", type: "article", action: () => window.location.href = "article.html?id=seo-casablanca" },
    { title: "Social Media Agencies Compared (Article)", type: "article", action: () => window.location.href = "article.html?id=social-media-compared" }
];

function openSearchPalette() {
    const modal = document.getElementById("search-modal");
    if (modal) {
        document.getElementById("search-input").value = "";
        runPaletteSearch("");
        modal.showModal();
        animateModalOpen(modal);
    }
}

function closeSearchPalette() {
    const modal = document.getElementById("search-modal");
    if (modal && modal.open) {
        animateModalClose(modal, () => {
            modal.close();
            if (typeof gsap !== "undefined") {
                gsap.set(modal, { clearProps: "all" });
            }
        });
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

// Keyboard listener for Command Palette (Ctrl+K / Cmd+K)
window.addEventListener("keydown", (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key === "k") {
        e.preventDefault();
        openSearchPalette();
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

// ==================== MATCHMAKER LEAD WIZARD ====================
// Injects the matchmaker modal HTML if not already present, then opens it.
(function buildMatchmakerModal() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', _injectMatchmakerHTML);
    } else {
        _injectMatchmakerHTML();
    }
})();

function _injectMatchmakerHTML() {
    if (document.getElementById('matchmaker-modal')) return;
    const tpl = document.createElement('div');
    tpl.innerHTML = `
    <dialog id="matchmaker-modal" class="backdrop:bg-slate-900/60 backdrop:backdrop-blur-sm rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full p-0 bg-white overflow-hidden outline-none">
        <div class="bg-gradient-to-br from-brand-900 to-brand-700 px-6 py-5 text-white flex items-start justify-between">
            <div>
                <p class="text-[10px] font-mono font-bold uppercase tracking-widest text-blue-300 mb-1">Matchmaker</p>
                <h2 class="text-[1.2rem] font-extrabold tracking-tight font-display" id="mm-step-title">Trouvez votre agence idéale</h2>
                <p class="text-[12px] text-blue-200 mt-0.5" id="mm-step-subtitle">4 questions · 60 secondes</p>
            </div>
            <button onclick="closeMatchmaker()" class="text-blue-300 hover:text-white transition-colors mt-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Progress bar -->
        <div class="h-1 bg-slate-100">
            <div id="mm-progress" class="h-full bg-brand-500 transition-all duration-400" style="width:25%"></div>
        </div>

        <div class="px-6 py-6" id="mm-body">
            <!-- Injected per step -->
        </div>

        <div class="px-6 pb-6 flex items-center justify-between gap-3 border-t border-slate-100 pt-4">
            <button id="mm-back-btn" onclick="mmBack()" class="text-[13px] text-slate-500 hover:text-slate-800 font-semibold flex items-center gap-1 transition-colors hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                Retour
            </button>
            <div class="flex-1"></div>
            <span class="text-[11px] font-mono text-slate-400" id="mm-step-counter">Étape 1 / 4</span>
            <button id="mm-next-btn" onclick="mmNext()" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-5 py-2.5 rounded-xl text-[13px] transition-colors flex items-center gap-1.5">
                Suivant
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
    </dialog>`;
    document.body.appendChild(tpl.firstElementChild);
}

let _mmStep = 0;
const _mmAnswers = { service: '', city: '', budget: '', note: '' };

const _mmSteps = [
    {
        title: () => currentLang === 'fr' ? 'Quel service recherchez-vous ?' : 'What service do you need?',
        subtitle: () => currentLang === 'fr' ? 'Étape 1 / 4 · Sélectionnez une option' : 'Step 1 / 4 · Pick one',
        render: () => {
            const options = [
                { value: 'SEO', label: currentLang === 'fr' ? '🔍 Référencement (SEO)' : '🔍 Search Engine Optimisation (SEO)' },
                { value: 'Paid Ads', label: currentLang === 'fr' ? '📣 Publicité payante (Google / Meta)' : '📣 Paid Advertising (Google / Meta)' },
                { value: 'Social Media', label: currentLang === 'fr' ? '📱 Réseaux Sociaux' : '📱 Social Media Management' },
                { value: 'Web Design', label: currentLang === 'fr' ? '🖥️ Création de site web' : '🖥️ Web Design & Development' },
                { value: 'Branding', label: currentLang === 'fr' ? '🎨 Identité de marque' : '🎨 Branding & Identity' },
                { value: 'Content Marketing', label: currentLang === 'fr' ? '✍️ Contenu & Rédaction' : '✍️ Content & Copywriting' }
            ];
            return `<div class="grid grid-cols-2 gap-2" id="mm-service-grid">
                ${options.map(o => `
                    <button type="button" class="mm-chip border-2 border-slate-200 hover:border-brand-400 bg-white rounded-xl px-4 py-3 text-[12.5px] font-semibold text-slate-700 text-left transition-all hover:bg-brand-50 ${_mmAnswers.service === o.value ? 'border-brand-600 bg-brand-50 text-brand-700' : ''}" onclick="mmSelectChip(this, 'service', '${o.value}')">
                        ${o.label}
                    </button>`).join('')}
            </div>`;
        }
    },
    {
        title: () => currentLang === 'fr' ? 'Dans quelle ville êtes-vous ?' : 'Which city are you in?',
        subtitle: () => currentLang === 'fr' ? 'Étape 2 / 4 · Choisissez votre ville' : 'Step 2 / 4 · Choose your city',
        render: () => {
            const cities = ['Casablanca', 'Rabat', currentLang === 'fr' ? 'Tanger' : 'Tangier', 'Marrakech', 'Agadir', currentLang === 'fr' ? 'Autre ville' : 'Other city'];
            const cityValues = ['Casablanca', 'Rabat', 'Tangier', 'Marrakech', 'Agadir', ''];
            return `<div class="grid grid-cols-3 gap-2" id="mm-city-grid">
                ${cities.map((c, i) => `
                    <button type="button" class="mm-chip border-2 border-slate-200 hover:border-brand-400 bg-white rounded-xl px-3 py-3 text-[12.5px] font-semibold text-slate-700 text-center transition-all hover:bg-brand-50 ${_mmAnswers.city === cityValues[i] ? 'border-brand-600 bg-brand-50 text-brand-700' : ''}" onclick="mmSelectChip(this, 'city', '${cityValues[i]}')">
                        ${c}
                    </button>`).join('')}
            </div>`;
        }
    },
    {
        title: () => currentLang === 'fr' ? 'Quel est votre budget mensuel ?' : 'What is your monthly budget?',
        subtitle: () => currentLang === 'fr' ? 'Étape 3 / 4 · Approximatif suffit' : 'Step 3 / 4 · Approximate is fine',
        render: () => {
            const opts = [
                { v: '<5000', l: currentLang === 'fr' ? 'Moins de 5 000 MAD' : 'Under 5,000 MAD' },
                { v: '5000-15000', l: '5 000 – 15 000 MAD' },
                { v: '15000-50000', l: '15 000 – 50 000 MAD' },
                { v: '>50000', l: currentLang === 'fr' ? 'Plus de 50 000 MAD' : 'Over 50,000 MAD' }
            ];
            return `<div class="space-y-2" id="mm-budget-grid">
                ${opts.map(o => `
                    <button type="button" class="mm-chip w-full border-2 border-slate-200 hover:border-brand-400 bg-white rounded-xl px-4 py-3 text-[13px] font-semibold text-slate-700 text-left transition-all hover:bg-brand-50 ${_mmAnswers.budget === o.v ? 'border-brand-600 bg-brand-50 text-brand-700' : ''}" onclick="mmSelectChip(this, 'budget', '${o.v}')">
                        ${o.l}
                    </button>`).join('')}
            </div>`;
        }
    },
    {
        title: () => currentLang === 'fr' ? 'Décrivez votre projet' : 'Tell us about your project',
        subtitle: () => currentLang === 'fr' ? 'Étape 4 / 4 · Optionnel mais utile' : 'Step 4 / 4 · Optional but helpful',
        render: () => `
            <div class="space-y-3">
                <textarea id="mm-note-input" rows="4" placeholder="${currentLang === 'fr' ? 'Décrivez votre objectif, votre marché cible, ou toute contrainte spécifique...' : 'Describe your goal, target market, or any specific constraints...'}"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 text-[13px] text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 resize-none font-sans" oninput="_mmAnswers.note = this.value">${_mmAnswers.note}</textarea>
                <p class="text-[11.5px] text-slate-400">${currentLang === 'fr' ? 'Vos informations restent confidentielles et ne seront jamais revendues.' : 'Your information stays private and is never resold.'}</p>
            </div>`
    }
];

function _mmRender() {
    const step = _mmSteps[_mmStep];
    const title = document.getElementById('mm-step-title');
    const subtitle = document.getElementById('mm-step-subtitle');
    const body = document.getElementById('mm-body');
    const progress = document.getElementById('mm-progress');
    const counter = document.getElementById('mm-step-counter');
    const backBtn = document.getElementById('mm-back-btn');
    const nextBtn = document.getElementById('mm-next-btn');

    if (!title || !body) return;
    title.textContent = step.title();
    subtitle.textContent = step.subtitle();
    body.innerHTML = step.render();
    progress.style.width = ((_mmStep + 1) / _mmSteps.length * 100) + '%';
    counter.textContent = (currentLang === 'fr' ? 'Étape ' : 'Step ') + (_mmStep + 1) + ' / ' + _mmSteps.length;

    backBtn.classList.toggle('hidden', _mmStep === 0);
    nextBtn.textContent = _mmStep === _mmSteps.length - 1 ? (currentLang === 'fr' ? 'Voir les résultats →' : 'See Results →') : (currentLang === 'fr' ? 'Suivant' : 'Next');

    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function mmSelectChip(btn, field, value) {
    _mmAnswers[field] = value;
    const grid = btn.closest('[id^="mm-"]');
    if (grid) {
        grid.querySelectorAll('.mm-chip').forEach(b => b.classList.remove('border-brand-600', 'bg-brand-50', 'text-brand-700'));
    }
    btn.classList.add('border-brand-600', 'bg-brand-50', 'text-brand-700');
}

function mmNext() {
    if (_mmStep < _mmSteps.length - 1) {
        _mmStep++;
        _mmRender();
    } else {
        _mmFinish();
    }
}

function mmBack() {
    if (_mmStep > 0) {
        _mmStep--;
        _mmRender();
    }
}

function _mmFinish() {
    const modal = document.getElementById('matchmaker-modal');
    const body = document.getElementById('mm-body');
    const nextBtn = document.getElementById('mm-next-btn');
    const backBtn = document.getElementById('mm-back-btn');

    // Build URL to directory with filters
    const params = new URLSearchParams();
    if (_mmAnswers.service) params.set('service', _mmAnswers.service);
    if (_mmAnswers.city) params.set('city', _mmAnswers.city);
    const dirURL = 'directory.html?' + params.toString();

    const matches = (typeof AGENCY_DATA !== 'undefined') ? AGENCY_DATA.filter(a => {
        const svc = !_mmAnswers.service || (a.services || []).includes(_mmAnswers.service);
        const city = !_mmAnswers.city || a.city === _mmAnswers.city;
        return svc && city;
    }).slice(0, 3) : [];

    if (nextBtn) nextBtn.style.display = 'none';
    if (backBtn) backBtn.style.display = 'none';

    const fr = currentLang === 'fr';
    body.innerHTML = `
        <div class="text-center py-2">
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h3 class="font-extrabold text-[16px] text-slate-900 mb-1 font-display">${fr ? 'Votre sélection est prête !' : 'Your selection is ready!'}</h3>
            <p class="text-[13px] text-slate-500 mb-5">${fr ? `Nous avons trouvé <strong>${matches.length || '6+'}</strong> agences correspondant à votre besoin.` : `We found <strong>${matches.length || '6+'}</strong> agencies matching your criteria.`}</p>
            ${matches.length > 0 ? `<div class="text-left space-y-2 mb-5">
                ${matches.map(a => `<div class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl p-3 cursor-pointer hover:bg-brand-50 hover:border-brand-200 transition-colors" onclick="closeMatchmaker(); window.location.href='profile.html?id=${a.id}'">
                    <img src="${a.logo}" class="w-9 h-9 rounded-lg object-cover border border-slate-200">
                    <div class="flex-grow">
                        <p class="font-bold text-[13px] text-slate-900">${a.name}</p>
                        <p class="text-[11px] text-slate-400 font-mono">${a.city} · ★ ${a.rating}</p>
                    </div>
                    <span class="text-brand-600 text-[11px] font-bold font-mono">#${a.rank}</span>
                </div>`).join('')}
            </div>` : ''}
            <a href="${dirURL}" onclick="closeMatchmaker()" class="block w-full bg-brand-600 hover:bg-brand-700 text-white font-bold px-5 py-3 rounded-xl text-[13px] transition-colors text-center">
                ${fr ? 'Voir tous les résultats dans l\'annuaire →' : 'See all results in directory →'}
            </a>
        </div>`;
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function openMatchmaker() {
    _mmStep = 0;
    _mmAnswers.service = '';
    _mmAnswers.city = '';
    _mmAnswers.budget = '';
    _mmAnswers.note = '';

    const modal = document.getElementById('matchmaker-modal');
    if (!modal) { _injectMatchmakerHTML(); }

    const nextBtn = document.getElementById('mm-next-btn');
    const backBtn = document.getElementById('mm-back-btn');
    if (nextBtn) nextBtn.style.display = '';
    if (backBtn) backBtn.style.display = '';

    _mmRender();

    const m = document.getElementById('matchmaker-modal');
    if (m) {
        m.showModal();
        animateModalOpen(m);
    }
}

function closeMatchmaker() {
    const modal = document.getElementById('matchmaker-modal');
    if (modal && modal.open) {
        animateModalClose(modal, () => {
            modal.close();
            if (typeof gsap !== 'undefined') gsap.set(modal, { clearProps: 'all' });
        });
    }
}

// ==================== END MATCHMAKER ====================

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
    const savedLang = localStorage.getItem("indexDigitalLang") || "fr";
    setLanguage(savedLang);
    initSearchAndFilterControls();
    requestAnimationFrame(initMotionSystem);
});

window.enhanceDynamicContent = enhanceDynamicContent;
window.animateModalOpen = animateModalOpen;
window.animateModalClose = animateModalClose;
