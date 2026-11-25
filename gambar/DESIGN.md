---
name: EduTask Academic Precision
colors:
  surface: '#f8f9ff'
  surface-dim: '#ccdbf3'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4ff'
  surface-container: '#e6eeff'
  surface-container-high: '#dce9ff'
  surface-container-highest: '#d5e3fc'
  on-surface: '#0d1c2e'
  on-surface-variant: '#434655'
  inverse-surface: '#233144'
  inverse-on-surface: '#eaf1ff'
  outline: '#737686'
  outline-variant: '#c3c6d7'
  surface-tint: '#0053db'
  primary: '#004ac6'
  on-primary: '#ffffff'
  primary-container: '#2563eb'
  on-primary-container: '#eeefff'
  inverse-primary: '#b4c5ff'
  secondary: '#006a61'
  on-secondary: '#ffffff'
  secondary-container: '#86f2e4'
  on-secondary-container: '#006f66'
  tertiary: '#006243'
  on-tertiary: '#ffffff'
  tertiary-container: '#007d57'
  on-tertiary-container: '#bdffdc'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe1ff'
  primary-fixed-dim: '#b4c5ff'
  on-primary-fixed: '#00174b'
  on-primary-fixed-variant: '#003ea8'
  secondary-fixed: '#89f5e7'
  secondary-fixed-dim: '#6bd8cb'
  on-secondary-fixed: '#00201d'
  on-secondary-fixed-variant: '#005049'
  tertiary-fixed: '#85f8c4'
  tertiary-fixed-dim: '#68dba9'
  on-tertiary-fixed: '#002114'
  on-tertiary-fixed-variant: '#005137'
  background: '#f8f9ff'
  on-background: '#0d1c2e'
  surface-variant: '#d5e3fc'
typography:
  display-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 48px
    letterSpacing: -0.02em
  display-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 30px
    fontWeight: '700'
    lineHeight: 38px
    letterSpacing: -0.015em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 36px
    letterSpacing: -0.015em
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 22px
    fontWeight: '600'
    lineHeight: 30px
    letterSpacing: -0.01em
  headline-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '600'
    lineHeight: 26px
    letterSpacing: -0.005em
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  body-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 18px
  label-lg:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.01em
  label-md:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.02em
  label-sm:
    fontFamily: Inter
    fontSize: 11px
    fontWeight: '600'
    lineHeight: 14px
    letterSpacing: 0.03em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1.5rem
  gutter-mobile: 1rem
  margin: 2rem
  margin-mobile: 1rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2rem
---

## Brand & Style

This design system targets modern learners, university students, and educators who navigate complex course structures, recurring deadlines, and collaborative submissions. The emotional baseline is structured clarity, academic momentum, and dependable utility. Visual noise is minimized to foster cognitive endurance during prolonged study sessions.

The design movement blends **Modern Corporate Clarity** with **Refined Educational Functionalism**. Surfaces are disciplined, structured, and legible, utilizing crisp information-density metrics rather than ornamental decor. Hierarchy is reinforced through deliberate tonal depth, razor-thin framing, and intuitive status signifiers, balancing administrative utility with an inviting, modern digital campus feel.

## Colors

The palette leverages a sharp Royal Blue (`#2563EB`) as the primary driver for key interactions, focal navigation states, and primary task drivers. Deep slate (`#475569`) governs neutral typography and structural scaffolding, providing balanced contrast without the harshness of pure black.

Status mechanics rely on semantic assignments:
- **Completed / Graded:** Emerald (`#059669`) provides affirmative completion metrics and scoring callouts.
- **Pending / In Progress:** Amber (`#D97706`) signals approaching deadlines and tasks awaiting submission.
- **Urgent / Overdue:** Rose (`#E11D48`) dictates critical interventions and late milestones.
- **Kelompok (Group Tasks):** Violet (`#7C3AED`) acts as a dedicated grouping accent to distinguish collective work from solo submissions.
- **Individu (Solo Tasks):** Slate Teal (`#0D9488`) anchors autonomous, personal coursework.

Surfaces default to clean, high-luminance canvas tones (`#F8FAFC`), elevated by crisp pure white card containers (`#FFFFFF`) with balanced surface borders (`#E2E8F0`).

## Typography

The typographic hierarchy matches high-speed scannability with formal educational posture. Headings utilize **Plus Jakarta Sans** for modern warmth, structural geometry, and clear letterform distinctiveness. Body text and granular status metrics rely on **Inter** to maximize reading comfort in dense assignment briefs, grading rubrics, and submission logs.

Letter tracking on uppercase metadata tags and badges is expanded slightly (`0.02em` to `0.03em`) to enhance legibility at small sizes. Long-form reading materials, such as syllabus text and assignment descriptions, must adhere to a strict 65–75 character maximum measure per line.

## Layout & Spacing

The layout model enforces a 12-column responsive fluid grid optimized for high-information-density desktop workflows, stepping down cleanly to 8 columns on tablets and 4 columns on mobile viewports.

- **Desktop (1024px and above):** 12-column grid with a fixed or dynamic side navigation pane (260px), `1.5rem` gutters, and `2rem` outer page margins. Maximum page content area constrains to `1440px`.
- **Tablet (768px – 1023px):** 8-column grid with collapsible off-canvas navigation, `1.25rem` gutters, and `1.5rem` outer margins.
- **Mobile (Below 768px):** 4-column single-stream stack, `1rem` gutters, and `1rem` outer screen margins.

Spatial rhythm is governed by an 8px base rhythm (`0.5rem`). Dense analytical tables and task checklists utilize tighter inner vertical padding (`space-xs` and `space-sm`), while assignment overview cards and panel splits utilize `space-lg` and `space-xl` to prevent layout fatigue.

## Elevation & Depth

Visual hierarchy uses **tonal layer separation combined with delicate, low-contrast ghost borders** rather than aggressive dropshadows.

1. **Base Layer (Canvas):** Set to `#F8FAFC`.
2. **Surface Layer (Cards, Tables, Panels):** Pure `#FFFFFF` surface accompanied by a hairline border (`1px solid #E2E8F0`).
3. **Elevated Surface (Flyouts, Dropdowns, Date Pickers):** Pure `#FFFFFF` resting on a diffused ambient shadow: `0 4px 20px -2px rgba(15, 23, 42, 0.08)`, paired with a `1px solid #CBD5E1` boundary.
4. **Modal / Overlay Layer:** Crisp white card suspended over a slate-tinted backdrop scrim (`rgba(15, 23, 42, 0.45)` with a `4px` blur). Shadow structure expands to `0 20px 25px -5px rgba(15, 23, 42, 0.12)`.

## Shapes

The interface implements consistent `0.5rem` (`8px`) corner radiuses for input elements, buttons, and individual list cells, conveying modern balance without losing structural discipline.

- **Cards and Containers:** Standardized at `rounded-lg` (`1rem` / `16px`) to define distinct content zones.
- **Modals and Large Panels:** Standardized at `rounded-xl` (`1.5rem` / `24px`).
- **Badges, Tags, and Chips:** Retain a full pill curvature (`9999px`) to immediately set metadata, tags, and classification pills apart from rectilinear text blocks and rectangular structural cards.

## Components

### Buttons
- **Primary:** High-fill `#2563EB` background with `#FFFFFF` text. Solid state features a subtle bottom boundary tone for depth. Hover state transitions to `#1D4ED8`. Focus ring creates a 2px offset in `#93C5FD`.
- **Secondary / Outline:** `#FFFFFF` background, `1px solid #E2E8F0`, `#334155` text. Hover shifts to `#F8FAFC` surface with `#1E293B` text.
- **Destructive:** Soft wash tint (`#FEE2E2`) with `#B91C1C` text, transitioning on hover to full `#DC2626` with `#FFFFFF` text.

### Badges & Category Indicators
- **Assignment Type Badges (Pill Shape):**
  - **Individu:** Subtle teal wash (`#CCFBF1` background, `#0F766E` text, `1px solid #99F6E4`). Icon: Single user silhouette.
  - **Kelompok:** Vibrant lavender wash (`#F3E8FF` background, `#6B21A8` text, `1px solid #E9D5FF`). Icon: Multi-user grouping outline.
- **Status Pills:**
  - **Graded / Selesai:** `#DCFCE7` background, `#15803D` text.
  - **Menunggu Evaluasi (Pending):** `#FEF3C7` background, `#B45309` text.
  - **Terlambat (Overdue):** `#FFE4E6` background, `#BE123C` text.

### Cards
- **Assignment Card:** Pure white surface, `1px solid #E2E8F0`, padding of `1.25rem`. Top metadata row isolates Course Title and Assignment Type Badge (`Individu` vs `Kelompok`). Middle tier contains Task Title (`headline-sm`) and brief summary. Bottom row contains submission deadline with micro-calendar icon, weighted progress indicators, and student submission counters. Hover elevates with a soft stroke highlight (`#CBD5E1`) and a 2px vertical lift.

### Input Fields & Controls
- **Text Inputs:** Height 42px, radius 8px, `1px solid #CBD5E1` border, `#FFFFFF` surface. Active focus highlights with `#2563EB` border and matching 3px diffused outer halo (`rgba(37, 99, 235, 0.15)`).
- **Checkboxes & Radios:** Custom 18px rounded controls. Unchecked: `1.5px solid #94A3B8`. Checked: solid `#2563EB` with pure white check mark icon.
- **Lists & Data Rows:** Alternating rows or clean dividing line (`1px solid #F1F5F9`). Active task hover renders a `#F8FAFC` background tint with a 3px vertical accent bar on the left edge denoting current state.