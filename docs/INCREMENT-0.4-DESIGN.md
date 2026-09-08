# Increment 0.4 — Visual System and Homepage Architecture

Status: implementation candidate for sanitised NIU staging only  
Production: prohibited

## Design direction

Increment 0.4 turns the accepted structural theme into a calm, contemporary Persian music identity. Warm ivory carries long-form reading; deep turquoise gives the institution its structure; pomegranate marks decisions and emphasis; saffron is used sparingly for orientation. Fine rules, editorial spacing and typographic contrast replace repeated rounded-card layouts.

The hero is static. Its theme-owned abstract string composition suggests the physicality of an Iranian instrument without claiming to depict a teacher, student or specific historical object. The page remains usable if that decorative asset does not load.

## Homepage information architecture

1. Editorial Header
2. Static Hero
3. Essential Facts
4. Why Delnavazan
5. Courses and Instruments
6. Pricing and Course Structure
7. How Delnavazan Works
8. Teachers and Human Trust
9. Selected Educational Articles
10. FAQ
11. Final Enrolment CTA
12. Institutional Footer

## Gutenberg ownership

`patterns/homepage-editorial.php` is a complete core-block pattern. It uses Group, Heading, Paragraph, Buttons, Image, List, Details and Query blocks only. Editors can insert and revise it without Otter, Amelia or Delnavazan Platform. `front-page.php` remains a small `the_content()` shell so page ID, permalink, revisions, block editing and SEO metadata remain owned by WordPress and Rank Math.

The pattern does not overwrite page 8 automatically. On NIU, the approved migration procedure is to preserve a page-content revision, replace only the sanitised staging homepage block tree, inspect the result, and restore the revision if required. Production content is not changed by this increment.

## Factual and link boundaries

- The displayed A$250 price is the currently observed value and remains a Product Owner release-readiness check.
- The 15-item course list is provisional pending confirmation that every listed course remains active.
- Only established `/enrol/` and `/articles/` destinations are linked. Course and teacher destinations are not invented.
- The theme does not add payment, booking, matching, scheduling, notification or calendar behaviour.
- Google Calendar disclosure removal is a Privacy/Terms release-readiness question; this pattern does not rewrite the legal pages.

## Responsive model

- Small mobile: one-column reading order, full-width actions, one-column course index below 390px, compact header.
- 375–390px: two-column fact and course rhythm where space permits, with full-width pricing rows.
- Tablet: two-column editorial transitions and process grid.
- Desktop: asymmetric hero, three-column principles/articles, four-step process and a two-column pricing ledger.
- No section depends on horizontal scrolling. Long Latin references use the existing isolation utilities.

## Accessibility

- One authored H1 in the homepage pattern; section headings descend to H2/H3.
- The decorative hero image has empty alternative text; no synthetic description is announced.
- Navigation retains the labelled toggle, Escape handling and visible focus.
- Controls meet the 44px minimum target and remain usable at 320px.
- Motion is limited to a small article-image hover treatment and is disabled by `prefers-reduced-motion`.
- FAQ uses native `details`/`summary` elements.

## Media debt

The theme artwork does not mask or rewrite missing Media Library files. Three previously observed homepage resources remain content/media migration debt. Empty alt metadata in authored content remains a content-owner audit; the theme does not fabricate text at runtime.
