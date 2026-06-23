/**
 * Theme Interactivity & High-Speed Dynamic Filter Scripts
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

function injectMotionStyles() {
    if (document.getElementById("motion-enhancement-styles")) return;
    const style = document.createElement("style");
    style.id = "motion-enhancement-styles";
    style.textContent = `
        .motion-enhanced .nav-link {
            will-change: color;
        }
        .hero-focus-word,
        .hero-location-word {
            position: relative;
            display: inline-block;
            white-space: nowrap;
        }
        .hero-focus-word {
            color: #2563eb;
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
        gsap.set(heroTitle, { autoAlpha: 1 });
        tl.from(heroTitle, { y: 12, autoAlpha: 0, scale: 0.985, duration: 0.42, ease: "back.out(1.5)" }, "-=0.05");
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

function enhanceDynamicContent(root = document) {
    if (!canUseMotion()) return;
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
                handleFilterControlChange(select);
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

        select.addEventListener('change', () => {
            const activeOpt = select.options[select.selectedIndex];
            if (activeOpt) {
                label.textContent = activeOpt.textContent;
                menu.querySelectorAll('.custom-select-option').forEach(item => {
                    const idxStr = item.dataset.index;
                    const isSel = idxStr === String(select.selectedIndex);
                    item.className = isSel
                        ? 'custom-select-option px-3.5 py-2 text-[13px] text-brand-700 bg-brand-50/50 font-semibold cursor-pointer flex items-center justify-between transition-all rounded-md mx-1 my-0.5'
                        : 'custom-select-option px-3.5 py-2 text-[13px] text-slate-600 hover:bg-slate-50 hover:text-slate-900 cursor-pointer flex items-center justify-between transition-all rounded-md mx-1 my-0.5';
                    const check = item.querySelector('.text-brand-600');
                    if (check) check.className = `text-brand-600 flex items-center flex-shrink-0 ${isSel ? 'block' : 'hidden'}`;
                });
            }
        });
    });

    if (!motionState.customSelectClickBound) {
        motionState.customSelectClickBound = true;
        document.addEventListener('click', () => {
            document.querySelectorAll('.custom-select-options').forEach(menu => {
                closeCustomSelectMenu(menu, menu.previousElementSibling);
            });
        });
    }
}

function openCustomSelectMenu(menu, trigger) {
    const wrapper = menu.closest(".custom-select-wrapper");
    if (wrapper) wrapper.style.zIndex = "80";
    menu.classList.remove("hidden");
    trigger.classList.add("open");
    if (canUseMotion()) {
        gsap.fromTo(menu,
            { y: -8, opacity: 0, scale: 0.98 },
            { y: 0, opacity: 1, scale: 1, duration: 0.2, ease: "power2.out" }
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
        gsap.to(menu, { y: -4, opacity: 0, duration: 0.16, ease: "power2.in", onComplete: complete });
    } else {
        complete();
    }
}


/* ==========================================================================
   3. Search Command Palette & Language Switching
   ========================================================================== */

function setLanguage(lang) {
    currentLang = lang;
    localStorage.setItem("indexDigitalLang", lang);

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

    if (typeof runFiltering === "function") runFiltering();
}

function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    if (menu) menu.classList.toggle('hidden');
}

function toggleFaq(button) {
    const content = button.nextElementSibling;
    const arrow = button.querySelector('[data-lucide="chevron-down"]');
    if (content) {
        content.classList.toggle('open');
        if (arrow) {
            arrow.style.transform = content.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0deg)';
        }
    }
}

function openSearchPalette() {
    const modal = document.getElementById("search-modal");
    const input = document.getElementById("search-input");
    if (!modal) return;

    if (input) input.value = "";
    runPaletteSearch("");
    modal.showModal();
    if (input) input.focus();
}

function closeSearchPalette() {
    const modal = document.getElementById("search-modal");
    if (modal) modal.close();
}

function runPaletteSearch(val) {
    const list = document.getElementById("search-results");
    if (!list || typeof AGENCY_DATA === "undefined") return;
    list.innerHTML = "";
    const query = val.toLowerCase().trim();

    // Query filters across pages and localized agencies
    const filtered = AGENCY_DATA.filter(item => item.name.toLowerCase().includes(query));

    if (filtered.length === 0) {
        list.innerHTML = `<li class="p-4 text-slate-400 text-center">${currentLang === 'en' ? 'no results found' : 'aucun résultat'}</li>`;
        return;
    }

    filtered.forEach(item => {
        const li = document.createElement("li");
        li.className = "flex justify-between items-center p-3 hover:bg-slate-50 cursor-pointer rounded-lg transition-colors";
        li.innerHTML = `
            <span class="font-semibold text-slate-800">${item.name}</span>
            <span class="text-[10px] uppercase font-bold text-slate-400 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded font-mono">${item.city}</span>
        `;
        li.onclick = () => {
            closeSearchPalette();
            window.location.href = item.link;
        };
        list.appendChild(li);
    });
}


/* ==========================================================================
   4. High-Speed Real-time Client-Side Directory Filtering
   ========================================================================== */

function handleFilterControlChange(control) {
    if (!control || !control.id) return;
    if (control.id.startsWith("dir-") && typeof runFiltering === "function") {
        runFiltering();
    }
}

function initSearchAndFilterControls() {
    if (motionState.filterControlsBound) return;
    motionState.filterControlsBound = true;

    const dirSearch = document.getElementById("dir-search-input");
    if (dirSearch) {
        dirSearch.addEventListener("input", runFiltering);
    }
    document.querySelectorAll("[id^='dir-filter-']").forEach(select => {
        select.addEventListener("change", () => handleFilterControlChange(select));
    });
}

function runFiltering() {
    const searchInput = document.getElementById("dir-search-input");
    if (!searchInput || typeof AGENCY_DATA === "undefined") return;

    const query = searchInput.value.toLowerCase().trim();
    const city = document.getElementById("dir-filter-city").value;
    const service = document.getElementById("dir-filter-service").value;
    const rating = document.getElementById("dir-filter-rating").value;
    const sort = document.getElementById("dir-filter-sort").value;

    let filtered = [...AGENCY_DATA];

    if (query !== "") {
        filtered = filtered.filter(a => 
            a.name.toLowerCase().includes(query) || 
            a[currentLang].usp.toLowerCase().includes(query)
        );
    }

    if (city !== "all") {
        filtered = filtered.filter(a => a.city.toLowerCase() === city.toLowerCase());
    }

    if (service !== "all") {
        filtered = filtered.filter(a => a.services.some(s => s.toLowerCase() === service.toLowerCase()));
    }

    if (rating !== "any") {
        const min = parseFloat(rating);
        filtered = filtered.filter(a => a.rating >= min);
    }

    // Sort matching logic
    if (sort === "rank") {
        filtered.sort((a, b) => a.rank - b.rank);
    } else if (sort === "rating") {
        filtered.sort((a, b) => b.rating - a.rating);
    } else if (sort === "reviews") {
        filtered.sort((a, b) => b.reviews - a.reviews);
    }

    renderDirectoryCards(filtered);
}

function renderDirectoryCards(filteredData) {
    const container = document.getElementById("directory-cards-container");
    if (!container) return;
    container.innerHTML = "";

    document.getElementById("dir-results-count").textContent = filteredData.length;

    if (filteredData.length === 0) {
        container.innerHTML = `
            <div class="text-center py-20 bg-white border border-slate-200 rounded-xl shadow-sm">
                <p class="text-slate-400 font-mono text-[12px]">${currentLang === 'en' ? 'No agencies match your criteria.' : 'Aucune agence ne correspond.'}</p>
            </div>
        `;
        return;
    }

    filteredData.forEach(a => {
        const card = document.createElement("div");
        card.className = "agency-card card-hover bg-white rounded-xl border border-slate-200 p-5 md:p-6 flex flex-col sm:flex-row gap-5 shadow-sm cursor-pointer";
        card.onclick = () => window.location.href = a.link;

        const starsHTML = getStarsHTML(a.rating);
        const tagsHTML = a.services.map(s => `
            <span class="tag-pill bg-slate-50 text-slate-600 px-2 py-0.5 rounded border border-slate-200/60 font-mono text-[9px] uppercase font-bold">${s}</span>
        `).join("");

        // Build rank badge elements
        const badgeColor = (a.rank == 1) ? 'bg-amber-700' : ((a.rank == 2) ? 'bg-teal-700' : 'bg-brand-600');
        const badgeLabel = (a.rank == 1) ? 'Top Ranked' : ((a.rank == 2) ? 'Featured' : 'Top Pick');

        card.innerHTML = `
            <div class="flex-shrink-0 flex items-start gap-4">
                <span class="font-mono text-[24px] font-extrabold text-slate-300 leading-none">#${a.rank}</span>
                ${a.logo ? `<img src="${a.logo}" class="w-14 h-14 rounded-lg object-cover border border-slate-100 bg-white">` : `<div class="w-14 h-14 rounded-lg flex items-center justify-center bg-brand-600 text-white font-bold text-sm uppercase">${a.logoText}</div>`}
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-1.5">
                    <h3 class="font-extrabold text-[16px] text-slate-900 font-display">${a.name}</h3>
                    <div class="flex items-center gap-1">${starsHTML} <span class="font-bold text-slate-700 text-[12px] font-mono ml-1">${a.rating}</span><span class="text-slate-400 text-[11px] font-mono">(${a.reviews})</span></div>
                </div>
                <p class="text-[13.5px] text-slate-500 leading-relaxed mb-4">${a[currentLang].description}</p>
                <div class="flex flex-wrap gap-1.5 mb-4">${tagsHTML}</div>
                <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100 text-[11px] font-mono text-slate-400">
                    <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> ${a.city}</span>
                    <div class="flex gap-2">
                        <button onclick="event.stopPropagation(); window.location.href='${a.link}'" class="btn-spring px-3 py-1 bg-brand-50 border border-brand-200 text-brand-600 hover:bg-brand-100 font-semibold rounded text-[11px]">${currentLang === 'en' ? 'View Profile' : 'Profil'}</button>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(card);
    });

    if (typeof lucide !== "undefined") lucide.createIcons();
}

function getStarsHTML(rating) {
    let html = "";
    const floor = Math.floor(rating);
    for (let i = 1; i <= 5; i++) {
        if (i <= floor) {
            html += `<i data-lucide="star" class="w-3 h-3 text-amber-400 fill-amber-400 inline"></i>`;
        } else if (i - rating < 1) {
            html += `<i data-lucide="star-half" class="w-3 h-3 text-amber-400 fill-amber-400 inline"></i>`;
        } else {
            html += `<i data-lucide="star" class="w-3 h-3 text-slate-200 inline"></i>`;
        }
    }
    return html;
}


/* ==========================================================================
   5. Global Matchmaking Wizard Modal Logic
   ========================================================================== */

let wizardSelection = { service: "", budget: "" };

function openWizardModal() {
    const modal = document.getElementById("matching-wizard-modal");
    if (!modal) return;

    wizardSelection = { service: "", budget: "" };
    document.querySelectorAll(".wiz-opt, .wiz-opt-budget").forEach(btn => {
        btn.classList.remove("border-brand-600", "bg-brand-50/50");
        const icon = btn.querySelector('.lucide-circle');
        if (icon) {
            icon.classList.remove("text-brand-600", "fill-brand-600");
            icon.classList.add("text-slate-300");
        }
    });

    document.getElementById("wiz-next-btn").disabled = true;
    document.getElementById("wiz-submit-btn").disabled = true;
    goWizardStep(1);
    modal.showModal();
}

function closeWizardModal() {
    const modal = document.getElementById("matching-wizard-modal");
    if (modal) modal.close();
}

function goWizardStep(step) {
    document.getElementById("wiz-step-1").classList.add("hidden");
    document.getElementById("wiz-step-2").classList.add("hidden");
    document.getElementById("wiz-step-success").classList.add("hidden");

    document.getElementById(`wiz-step-${step}`).classList.remove("hidden");
}

function selectWizardService(btn, service) {
    document.querySelectorAll(".wiz-opt").forEach(b => b.classList.remove("border-brand-600", "bg-brand-50/50"));
    btn.classList.add("border-brand-600", "bg-brand-50/50");
    wizardSelection.service = service;
    document.getElementById("wiz-next-btn").disabled = false;
}

function selectWizardBudget(btn, budget) {
    document.querySelectorAll(".wiz-opt-budget").forEach(b => {
        b.classList.remove("border-brand-600", "bg-brand-50/50");
        const icon = b.querySelector('.lucide');
        if (icon) {
            icon.setAttribute("data-lucide", "circle");
            icon.classList.add("text-slate-300");
            icon.classList.remove("text-brand-600", "fill-brand-600");
        }
    });

    btn.classList.add("border-brand-600", "bg-brand-50/50");
    const icon = btn.querySelector('.lucide');
    if (icon) {
        icon.setAttribute("data-lucide", "check-circle");
        icon.classList.remove("text-slate-300");
        icon.classList.add("text-brand-600", "fill-brand-600");
    }

    if (typeof lucide !== "undefined") lucide.createIcons();

    wizardSelection.budget = budget;
    document.getElementById("wiz-submit-btn").disabled = false;
}

function submitWizard(e) {
    e.preventDefault();
    goWizardStep("success");

    setTimeout(() => {
        closeWizardModal();
        let url = '<?php echo esc_url( home_url( "/directory/" ) ); ?>?';
        url += 'service=' + encodeURIComponent(wizardSelection.service) + '&';
        url += 'budget=' + encodeURIComponent(wizardSelection.budget) + '&';
        url += 'wizardMatched=true';
        window.location.href = url;
    }, 1500);
}


/* ==========================================================================
   6. Document Initialization
   ========================================================================== */

document.addEventListener("DOMContentLoaded", () => {
    initCustomSelects();

    // Check search params on directory page
    if (document.getElementById("page-directory")) {
        const params = new URLSearchParams(window.location.search);
        const service = params.get("service");
        const city = params.get("city");
        const rating = params.get("rating");

        const dirService = document.getElementById("dir-filter-service");
        const dirCity = document.getElementById("dir-filter-city");
        const dirRating = document.getElementById("dir-filter-rating");

        if (service && dirService) {
            dirService.value = service;
            dirService.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (city && dirCity) {
            dirCity.value = city;
            dirCity.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (rating && dirRating) {
            dirRating.value = rating;
            dirRating.dispatchEvent(new Event('change', { bubbles: true }));
        }

        initSearchAndFilterControls();
        runFiltering();
    }

    const savedLang = localStorage.getItem("indexDigitalLang") || "en";
    setLanguage(savedLang);

    // Initialize GSAP Motion Mechanics
    requestAnimationFrame(initMotionSystem);
});
