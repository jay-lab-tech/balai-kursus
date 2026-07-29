# Design System Master File

> **LOGIC:** When building a specific page, first check `design-system/pages/[page-name].md`.
> If that file exists, its rules **override** this Master file.
> If not, strictly follow the rules below.

---

**Project:** Balai Kursus
**Updated:** 2026-07-29
**Category:** Education Management / Academic Operations
**Direction:** Institutional Editorial + Academic Workspace
**Design Dials:** Variance 4/10 (Structured) | Motion 2/10 (Quiet) | Density 6/10 (Focused)

---

## Global Rules

### Role Principles

- **Admin:** dense but calm operational workspace; prioritize records, filters, and next actions.
- **Instruktur:** course-centered workspace; every course page exposes local tabs for sessions, attendance, grades, and participants.
- **Peserta:** progress-centered portal; show current class, next session, registration status, and learning records before catalog browsing.
- **Public:** institutional information page; emphasize trust, schedule, announcements, and clear sign-in/register actions.

### Layout Principles

- Use one shared role shell per audience instead of styling legacy modules independently.
- Use breadcrumb and local navigation on every page below the role dashboard.
- Prefer editorial columns, ruled sections, and compact lists over decorative card grids.
- Use a card only when it groups a meaningful object; do not wrap every statistic or button in a card.
- Keep one primary action per page section and make destructive actions visually quiet until confirmation.

### Color Palette

| Role | Hex | CSS Variable |
|------|-----|--------------|
| Primary | `#173F5F` | `--color-primary` |
| On Primary | `#FFFFFF` | `--color-on-primary` |
| Secondary | `#0D9488` | `--color-secondary` |
| Accent/CTA | `#A84A2A` | `--color-accent` |
| Background | `#F5F2EA` | `--color-background` |
| Surface | `#FFFEFA` | `--color-surface` |
| Foreground | `#1E2D36` | `--color-foreground` |
| Muted | `#E5E0D6` | `--color-muted` |
| Border | `#CFC8BB` | `--color-border` |
| Destructive | `#DC2626` | `--color-destructive` |
| Ring | `#0D9488` | `--color-ring` |

**Color Notes:** Ink blue for structure, restrained teal for active states, terracotta for actions and academic emphasis. Avoid neon, purple, and gradient backgrounds.

### Typography

- **Heading Font:** Source Serif 4
- **Body Font:** IBM Plex Sans
- **Utility Font:** IBM Plex Mono
- **Mood:** institutional, editorial, calm, precise, academic
- **Google Fonts:** [Source Serif 4 + IBM Plex Sans + IBM Plex Mono](https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&family=Source+Serif+4:wght@500;600;700&display=swap)

**CSS Import:**
```css
@import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&family=Source+Serif+4:wght@500;600;700&display=swap');
```

### Spacing Variables

*Density: 7/10 — Standard*

| Token | Value | Usage |
|-------|-------|-------|
| `--space-xs` | `4px` / `0.25rem` | Tight gaps |
| `--space-sm` | `8px` / `0.5rem` | Icon gaps, inline spacing |
| `--space-md` | `16px` / `1rem` | Standard padding |
| `--space-lg` | `24px` / `1.5rem` | Section padding |
| `--space-xl` | `32px` / `2rem` | Large gaps |
| `--space-2xl` | `48px` / `3rem` | Section margins |
| `--space-3xl` | `64px` / `4rem` | Hero padding |

### Shadow Depths

| Level | Value | Usage |
|-------|-------|-------|
| `--shadow-sm` | `0 1px 2px rgba(0,0,0,0.05)` | Subtle lift |
| `--shadow-md` | `0 4px 6px rgba(0,0,0,0.1)` | Cards, buttons |
| `--shadow-lg` | `0 10px 15px rgba(0,0,0,0.1)` | Modals, dropdowns |
| `--shadow-xl` | `0 20px 25px rgba(0,0,0,0.15)` | Hero images, featured cards |

---

## Component Specs

### Buttons

```css
/* Primary Button */
.btn-primary {
  background: #D97706;
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 200ms ease;
  cursor: pointer;
}

.btn-primary:hover {
  opacity: 0.9;
  transform: translateY(-1px);
}

/* Secondary Button */
.btn-secondary {
  background: transparent;
  color: #0D9488;
  border: 2px solid #0D9488;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 200ms ease;
  cursor: pointer;
}
```

### Cards

```css
.card {
  background: #F0FDFA;
  border-radius: 12px;
  padding: 24px;
  box-shadow: var(--shadow-md);
  transition: all 200ms ease;
  cursor: pointer;
}

.card:hover {
  box-shadow: var(--shadow-lg);
  transform: translateY(-2px);
}
```

### Inputs

```css
.input {
  padding: 12px 16px;
  border: 1px solid #E2E8F0;
  border-radius: 8px;
  font-size: 16px;
  transition: border-color 200ms ease;
}

.input:focus {
  border-color: #0D9488;
  outline: none;
  box-shadow: 0 0 0 3px #0D948820;
}
```

### Modals

```css
.modal-overlay {
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(4px);
}

.modal {
  background: white;
  border-radius: 16px;
  padding: 32px;
  box-shadow: var(--shadow-xl);
  max-width: 500px;
  width: 90%;
}
```

---

## Style Guidelines

**Style:** Institutional Editorial Workspace

**Keywords:** academic operations, course management, class sessions, attendance, assessment, participant progress, institutional records

**Best For:** education management, training centers, course administration, instructor workspaces, participant portals

**Key Effects:** quiet hover states, clear active navigation, restrained status changes, no decorative motion on dense data

### Page Pattern

**Pattern Name:** Contextual Workspace

- **Navigation:** role shell + contextual breadcrumb + local tabs for a course or record.
- **Primary action:** one clear action near the page title; secondary actions remain quiet.
- **Section order:** 1. page context, 2. operational summary, 3. primary records/list, 4. related actions.
- **Data presentation:** use tables for administrative records, lists for activity, and cards only for meaningful grouped content.

---

## Motion

**Stagger List** (Standard) — Trigger: load or scroll | Duration: 300-450ms | Easing: `back.out(1.4)`

```js
gsap.from('.grid-item', { opacity: 0, scale: 0.92, y: 16, duration: 0.4, stagger: { each: 0.06, from: 'start', grid: 'auto' }, ease: 'back.out(1.4)' });
```

**Framework notes:** grid: 'auto' lets GSAP infer rows/columns from a CSS grid layout for a natural wave stagger

- ✅ Combine with from: 'center' for a bento-grid layout to draw the eye inward first
- ❌ Don't use back.out on dense data tables; the overshoot reads as sloppy on informational UI
- ⚡ Group DOM writes; avoid interleaving layout reads (getBoundingClientRect) between staggered tweens

---

## Anti-Patterns (Do NOT Use)

- ❌ Dark modes
- ❌ Complex jargon

### Additional Forbidden Patterns

- ❌ **Emojis as icons** — Use SVG icons (Heroicons, Lucide, Simple Icons)
- ❌ **Missing cursor:pointer** — All clickable elements must have cursor:pointer
- ❌ **Layout-shifting hovers** — Avoid scale transforms that shift layout
- ❌ **Low contrast text** — Maintain 4.5:1 minimum contrast ratio
- ❌ **Instant state changes** — Always use transitions (150-300ms)
- ❌ **Invisible focus states** — Focus states must be visible for a11y

---

## Pre-Delivery Checklist

Before delivering any UI code, verify:

- [ ] No emojis used as icons (use SVG instead)
- [ ] All icons from consistent icon set (Heroicons/Lucide)
- [ ] `cursor-pointer` on all clickable elements
- [ ] Hover states with smooth transitions (150-300ms)
- [ ] Light mode: text contrast 4.5:1 minimum
- [ ] Focus states visible for keyboard navigation
- [ ] `prefers-reduced-motion` respected
- [ ] Responsive: 375px, 768px, 1024px, 1440px
- [ ] No content hidden behind fixed navbars
- [ ] No horizontal scroll on mobile
