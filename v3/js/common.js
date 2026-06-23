let currentLang = "en";

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
    if (typeof renderDirectoryCards === "function") renderDirectoryCards();
    if (typeof renderRankings === "function") renderRankings();
    
    // If dynamic profile page rendering function exists
    if (typeof populateProfileFromURL === "function") populateProfileFromURL();
    
    // Re-initialize custom select dropdowns to match translated option lists
    initCustomSelects();
    
    // Reload Lucide icons
    if (typeof lucide !== "undefined") {
        lucide.createIcons();
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
    if (modal) {
        document.getElementById("search-input").value = "";
        runPaletteSearch("");
        modal.showModal();
    }
}

function closeSearchPalette() {
    const modal = document.getElementById("search-modal");
    if (modal) {
        modal.close();
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
                
                // Close menu
                menu.classList.add('hidden');
                trigger.classList.remove('open');
            };
        });
        
        wrapper.appendChild(menu);

        // Bind trigger click
        trigger.onclick = (e) => {
            e.stopPropagation();
            
            // Close all other dropdowns
            document.querySelectorAll('.custom-select-options').forEach(otherMenu => {
                if (otherMenu !== menu) otherMenu.classList.add('hidden');
            });
            document.querySelectorAll('.custom-select-trigger').forEach(otherTrigger => {
                if (otherTrigger !== trigger) {
                    otherTrigger.classList.remove('open');
                }
            });

            const isOpen = !menu.classList.contains('hidden');
            if (isOpen) {
                menu.classList.add('hidden');
                trigger.classList.remove('open');
            } else {
                menu.classList.remove('hidden');
                trigger.classList.add('open');
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
    document.addEventListener('click', () => {
        document.querySelectorAll('.custom-select-options').forEach(menu => menu.classList.add('hidden'));
        document.querySelectorAll('.custom-select-trigger').forEach(trigger => trigger.classList.remove('open'));
    });
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
});
