# Features — agencemarketingdigital.com (listicle)



## Goal & Rationale

- **Goal:** Build a simple site on **agencemarketingdigital.com** as a "top digital marketing agencies" directory / blog listicle, ranking **RMD #1** and **Pixagram (Pixa) #2**.
- **Why:** Off-site authority + referral play — an independent-looking agencies directory that showcases and links (**dofollow**) to RMD and Pixagram at the top.

---

## Stack (Approach A — decided)

- **WordPress** + a **slim custom theme** (lightweight classic or block theme — **NOT Elementor**)
- Small **site-specific plugin** for CPTs/taxonomies
- **ACF Pro** (agency profile fields + ranked-relationship listicle assembly)
- **Rank Math SEO**
- _Polylang / English deferred to Sprint 2 — Sprint 1 ships in French only._

**Why this stack:** clean, fast HTML (strong Core Web Vitals) serves the rank-fast/authority goal; structured CPT/ACF data lets the listicle grow into a real directory without a rebuild; the team edits structured fields in WP admin — no page-builder drift.

---

## Data Model

- **`agency` CPT** — logo, rating, one-line USP, blurb, website URL, contact.
- **`city` + `service` taxonomies.**
- **A listicle** = a post where the editor picks **ranked agencies via an ACF relationship field** (orderable) + writes intro/body. **RMD + Pixagram pinned at #1 / #2.**

---

## Sprint 1 — MVP (French only)

Built entirely on a **temp/dummy domain** (kept `noindex` the whole time), migrated to the real domain at the very end.

### Deliverables — 4 pages

| Page | Powered by |
| --- | --- |
| **Home** | Custom theme + global header/footer/nav |
| **Blog listing** (listicle index) | `agency` CPT query |
| **Single listicle / article template** | Full `agency` CPT + ACF ranked-relationship data model |
| **Contact** | Contact page + form |

### Build flow (dummy domain → real domain)

1. Provision separate hosting + dummy WP install.
2. Build, theme, **seed agencies**, and publish content **on the dummy domain** (`noindex` throughout).
3. **QA** on the dummy domain.
4. **Migrate** dummy → `agencemarketingdigital.com` via WP-CLI `search-replace`.
5. **Go live** — remove `noindex`.
6. Set up Search Console / analytics on **separate accounts**.

> Nothing touches the real domain until the migration task.

---

## SEO Independence (decided — mandatory)

Links to RMD/Pixa are **dofollow**, so the site must look genuinely independent to avoid a link-scheme penalty. Camouflage is **mandatory**:

- Credible **third-party agencies** in the listings.
- A real **ranking-methodology** page.
- **Legal pages.**
- Fully **separate hosting / IP** + **WHOIS privacy** + **separate analytics**.
- The dummy domain stays **noindexed** throughout the build so the operation never leaks.

---

## Deferred to Sprint 2 (S2) — pending client feedback

Kept in subtasks, not deleted:

- [ ] **Multilingual / Polylang** (add English) + English translations of all content (~doubles content effort)
- [ ] **Single-agency public template**
- [ ] **Directory + taxonomy archives** (browse by city / service)

---

## Upcoming Features (longer-term)

- **Multilingual** — full multi-language support.
- **Custom forms-management plugin** — manages form submissions; built to be extendable.
- **Dark / Light mode** — theme toggle.
- **Authentication** — login for agencies or users.
- **Agency verification** — verification implementation for agencies.

---

## Open Decisions

1. **Independent brand name / logo / identity** — needed before theming.
2. **Adding English** (Sprint 2) ~doubles content effort.

