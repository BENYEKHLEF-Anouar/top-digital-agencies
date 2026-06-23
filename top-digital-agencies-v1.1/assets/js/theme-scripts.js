/**
 * Theme Interactivity & GSAP Scroll animations
 * Text Domain: top-digital-agencies
 */

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

function initMotionSystem() {
    motionState.reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

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
    enhanceDynamicContent(document);
    observeDynamicContent();
}

function animatePageEntrance() {
    const header = document.querySelector("header");
    const heroTitle = document.querySelector(".hero-title");
    const heroItems = document.querySelectorAll("main .section-label, main h1 + p, main .hero-title + p, main .flex-col, main .max-w-3xl");
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
    if (!targets.length) {
        gsap.set(heroTitle, { autoAlpha: 1 });
        return;
    }

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

    const title = root.querySelector("main h2");
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

    const title = root.querySelector("main h2");
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
        if (onComplete) onComplete();
        return;
    }

    gsap.to(modal, {
        y: 12,
        scale: 0.985,
        opacity: 0,
        duration: 0.22,
        ease: "power2.in",
        onComplete: onComplete
    });
}


/* ==========================================================================
   2. Select custom dropdown components
   ========================================================================== */

function initCustomSelects() {
    // Revert any previously initialized custom selects to avoid duplicates
    document.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
        const select = wrapper.querySelector('select');
        if (select) {
            select.style.display = '';
            wrapper.replaceWith(select);
        }
    });

    const selectElements = document.querySelectorAll('select');
    selectElements.forEach(select => {
        const wrapper = document.createElement('div');
        wrapper.className = 'relative custom-select-wrapper w-full';

        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);
        select.style.display = 'none';

        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'custom-select-trigger flex items-center justify-between w-full bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 rounded-lg text-[13px] text-slate-700 shadow-sm focus:border-brand-600 focus:ring-2 focus:ring-brand-500/10 outline-none transition-all duration-200 cursor-pointer pl-3.5 pr-4 py-2';

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

            item.onclick = (e) => {
                e.stopPropagation();
                select.selectedIndex = idx;
                select.dispatchEvent(new Event('change', { bubbles: true }));
                label.textContent = opt.textContent;
                
                // Highlight choice in wrapper
                menu.querySelectorAll('.custom-select-option').forEach(el => {
                    el.className = 'custom-select-option px-3.5 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer flex items-center justify-between transition-all rounded-md mx-1 my-0.5';
                    const icon = el.querySelector('span:last-child');
                    if (icon) icon.className = 'text-brand-600 flex items-center flex-shrink-0 hidden';
                });
                item.className = 'custom-select-option px-3.5 py-2 text-[13px] text-brand-700 bg-brand-50/50 font-semibold cursor-pointer flex items-center justify-between transition-all rounded-md mx-1 my-0.5';
                checkIcon.className = 'text-brand-600 flex items-center flex-shrink-0 block';

                closeCustomSelectMenu(menu, trigger);
            };
        });

        wrapper.appendChild(menu);

        trigger.onclick = (e) => {
            e.stopPropagation();
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
    });

    if (!motionState.customSelectClickBound) {
        motionState.customSelectClickBound = true;
        document.addEventListener('click', () => {
            document.querySelectorAll('.custom-select-options').forEach(menu => {
                closeCustomSelectMenu(menu, menu.previousElementSibling);
            });
        });
    }
    if (typeof lucide !== "undefined") lucide.createIcons();
}

function openCustomSelectMenu(menu, trigger) {
    menu.classList.remove('hidden');
    trigger.classList.add('open');
    if (canUseMotion()) {
        gsap.fromTo(menu,
            { y: -6, scale: 0.985, opacity: 0 },
            { y: 0, scale: 1, opacity: 1, duration: 0.2, ease: "power3.out" }
        );
    }
}

function closeCustomSelectMenu(menu, trigger) {
    if (menu.classList.contains('hidden')) return;
    trigger.classList.remove('open');
    if (canUseMotion()) {
        gsap.to(menu, {
            y: -4,
            scale: 0.985,
            opacity: 0,
            duration: 0.16,
            ease: "power2.in",
            onComplete: () => menu.classList.add('hidden')
        });
    } else {
        menu.classList.add('hidden');
    }
}


/* ==========================================================================
   3. Matchmaking Wizard Logic & Overlay
   ========================================================================== */

let wizardSelections = { service: null, budget: null };

function openWizardModal() {
    const modal = document.getElementById("matching-wizard-modal");
    if (!modal) return;
    wizardSelections = { service: null, budget: null };

    // Reset Steps
    document.getElementById("wiz-step-1").classList.remove("hidden");
    document.getElementById("wiz-step-2").classList.add("hidden");
    document.getElementById("wiz-step-success").classList.add("hidden");

    document.querySelectorAll(".wiz-opt").forEach(b => b.className = "wiz-opt border border-slate-200 hover:border-brand-600 hover:bg-brand-50/50 rounded-lg p-3 text-center transition-all text-[13px] font-semibold text-slate-700");
    document.querySelectorAll(".wiz-opt-budget").forEach(b => b.className = "wiz-opt-budget w-full border border-slate-200 hover:border-brand-600 hover:bg-brand-50/50 rounded-lg p-3 text-left transition-all text-[13px] font-semibold text-slate-700 flex justify-between items-center");

    document.getElementById("wiz-next-btn").disabled = true;
    document.getElementById("wiz-submit-btn").disabled = true;

    modal.showModal();
    animateModalOpen(modal);
    if (typeof lucide !== "undefined") lucide.createIcons();
}

function closeWizardModal() {
    const modal = document.getElementById("matching-wizard-modal");
    if (!modal) return;
    animateModalClose(modal, () => modal.close());
}

function selectWizardService(btn, service) {
    document.querySelectorAll(".wiz-opt").forEach(b => {
        b.classList.remove("border-brand-600", "bg-brand-50/10", "ring-1", "ring-brand-600");
    });
    btn.classList.add("border-brand-600", "bg-brand-50/10", "ring-1", "ring-brand-600");
    wizardSelections.service = service;
    document.getElementById("wiz-next-btn").disabled = false;
}

function selectWizardBudget(btn, budget) {
    document.querySelectorAll(".wiz-opt-budget").forEach(b => {
        b.classList.remove("border-brand-600", "bg-brand-50/10", "ring-1", "ring-brand-600");
        const icon = b.querySelector("[data-lucide]");
        if (icon) {
            icon.setAttribute("data-lucide", "circle");
        }
    });
    btn.classList.add("border-brand-600", "bg-brand-50/10", "ring-1", "ring-brand-600");
    const icon = btn.querySelector("[data-lucide]");
    if (icon) {
        icon.setAttribute("data-lucide", "check-circle-2");
    }
    wizardSelections.budget = budget;
    document.getElementById("wiz-submit-btn").disabled = false;
    if (typeof lucide !== "undefined") lucide.createIcons();
}

function goWizardStep(stepNum) {
    if (stepNum === 1) {
        document.getElementById("wiz-step-1").classList.remove("hidden");
        document.getElementById("wiz-step-2").classList.add("hidden");
    } else if (stepNum === 2) {
        document.getElementById("wiz-step-1").classList.add("hidden");
        document.getElementById("wiz-step-2").classList.remove("hidden");
    }
    if (typeof lucide !== "undefined") lucide.createIcons();
}

function submitWizard(e) {
    e.preventDefault();
    const btn = document.getElementById("wiz-submit-btn");
    if (btn) {
        const rect = btn.getBoundingClientRect();
        triggerStarBurst(rect.left + rect.width / 2, rect.top + rect.height / 2);
    }

    document.getElementById("wiz-step-2").classList.add("hidden");
    document.getElementById("wiz-step-success").classList.remove("hidden");
    if (typeof lucide !== "undefined") lucide.createIcons();

    setTimeout(() => {
        closeWizardModal();
        window.location.href = `${window.location.origin}/directory/?service=${encodeURIComponent(wizardSelections.service)}&budget=${encodeURIComponent(wizardSelections.budget)}&wizardMatched=true`;
    }, 1800);
}

function triggerStarBurst(x, y) {
    const container = document.createElement("div");
    container.className = "star-burst-container";
    container.style.left = x + "px";
    container.style.top = y + "px";
    
    for (let i = 0; i < 6; i++) {
        const star = document.createElement("div");
        star.className = "star-burst";
        const angle = (i * 60) * Math.PI / 180;
        const radius = 30 + Math.random() * 20;
        star.style.left = Math.cos(angle) * radius + "px";
        star.style.top = Math.sin(angle) * radius + "px";
        container.appendChild(star);
    }

    document.body.appendChild(container);
    setTimeout(() => container.remove(), 500);
}


/* ==========================================================================
   4. Command Palette Search Overlay Logic
   ========================================================================== */

function openSearchPalette() {
    const modal = document.getElementById("search-modal");
    if (!modal) return;
    modal.showModal();
    animateModalOpen(modal);
    document.getElementById("search-input").focus();
}

function closeSearchPalette() {
    const modal = document.getElementById("search-modal");
    if (!modal) return;
    animateModalClose(modal, () => modal.close());
}

document.addEventListener("keydown", (e) => {
    if ((e.metaKey || e.ctrlKey) && e.key === "k") {
        e.preventDefault();
        openSearchPalette();
    }
});

function runPaletteSearch(query) {
    const resultsContainer = document.getElementById("search-results");
    if (!resultsContainer) return;
    resultsContainer.innerHTML = "";

    if (!query.trim()) {
        resultsContainer.innerHTML = `<li class="p-3 text-[13px] text-slate-400 text-center">${currentLang === 'en' ? 'Type to search...' : 'Tapez pour rechercher...'}</li>`;
        return;
    }

    // Filter dynamic AGENCY_DATA if enqueued
    let results = [];
    if (typeof AGENCY_DATA !== "undefined") {
        results = AGENCY_DATA.filter(a => 
            a.name.toLowerCase().includes(query.toLowerCase()) || 
            a.city.toLowerCase().includes(query.toLowerCase()) ||
            a.services.some(s => s.toLowerCase().includes(query.toLowerCase()))
        ).slice(0, 5);
    }

    if (!results.length) {
        resultsContainer.innerHTML = `<li class="p-3 text-[13px] text-slate-400 text-center">${currentLang === 'en' ? 'No matches found.' : 'Aucun résultat trouvé.'}</li>`;
        return;
    }

    results.forEach(item => {
        const li = document.createElement("li");
        li.className = "flex items-center justify-between p-3 rounded-lg hover:bg-slate-50 cursor-pointer border border-transparent hover:border-slate-100 transition-all";
        li.onclick = () => {
            window.location.href = `${window.location.origin}/agency/${item.id}/`;
        };
        li.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center font-bold text-xs uppercase text-slate-650">${item.logo_text ? item.logo_text : item.name.substring(0, 2)}</div>
                <div>
                    <div class="text-[13.5px] font-semibold text-slate-900">${item.name}</div>
                    <div class="text-[11px] text-slate-450">${item.services.slice(0, 2).join(", ")} &middot; ${item.city}</div>
                </div>
            </div>
            <i data-lucide="arrow-right" class="w-3.5 h-3.5 text-slate-400"></i>
        `;
        resultsContainer.appendChild(li);
    });
    if (typeof lucide !== "undefined") lucide.createIcons();
}


/* ==========================================================================
   5. Initialization trigger on Document Load
   ========================================================================== */

document.addEventListener("DOMContentLoaded", () => {
    initMotionSystem();
    initCustomSelects();
});
