# Changelog

## 0.5.0 — 2026-09-19 — Student Portal V1 presentation candidate

- Added isolated, Persian-first Student Portal Home and Account templates on the accepted recovered 0.4.6 presentation baseline.
- Added reusable announcement, Upcoming Lesson, state, Term timeline, history, feedback, contact, profile, payment, future-order and one-way notification components.
- Added a filter-only display-model boundary: the Theme performs no protected Student reads, Platform writes, payment operations, provider actions or calendar integration.
- Added an authenticated, non-production development preview with in-memory synthetic `.invalid` fixtures and no persistence.
- Added Portal-only responsive RTL styles and dependency-free progressive enhancement; public homepage, header, footer, pricing and existing Theme source remain unmodified.
- Added static contract checks and documented the future integration seams and deferred authority.

## 0.4.6 — 2026-09-16 — recovery candidate

- Reconciled the authoritative repository `theme/` directory losslessly with the verified NIU staging 0.4.6 source capture.
- Added non-runtime Page 8 Gutenberg and WordPress-state references with capture provenance and hashes; no WordPress database state was made Theme source.
- Preserved the Theme/Media Library ownership boundary and exact captured Additional CSS reference.
- This is a review candidate only: no packaging, activation, deployment, or NIU change occurred.

## 0.2.0 — 2026-09-06

- Mapped representative production pages, content ownership and migration dependencies.
- Added documented mobile baselines and a disposable-staging runbook.
- Implemented the approved Option 2 semantic tokens from the preserved Persian Palette Style Guide.
- Added public-only `fa-IR` and RTL document semantics without changing the WordPress/admin locale.
- Iterated the reusable Editorial header and keyboard-accessible mobile navigation.
- Added LTR isolation utilities for email, phone, URLs, code and reference values.
- Added template-owned H1 fallback while preserving authored page H1s.
- Added an accessible name to the theme-owned custom-logo link.
- Kept Rank Math, existing content, current menus and temporary plugin output under their existing owners.
- Confirmed that no Platform, Amelia or business behaviour was added to the theme.

## 0.1.0 — 2026-09-06

- Added inactive hybrid-theme scaffold.
- Added `theme.json` token foundation and RTL-first CSS.
- Added semantic header, navigation, footer and content templates.
- Added passive teacher, instrument, course, notice and state components.
- Added accessibility and minimal-JavaScript baseline.
- Added reconnaissance, architecture, migration, rollback and product-decision documentation.
- Confirmed no Platform, database, Amelia, booking, payment, notification or calendar behaviour is included.
