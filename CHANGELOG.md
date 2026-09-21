# Changelog

## 0.7.0 — correction round 1 (candidate, unmerged) — 2026-09-21

- Independent review of `8e0af6253ff9c0243ff25000bf15a681efcf376b` failed on six findings; all six are corrected additively.
- Heading parsing is quote-aware and fails safe on implausible tags, so a legal `>` or `<` inside a `title`/`data` attribute (or WordPress texturising it into `&#8221;`) can no longer corrupt an anchor or the outline text; malformed headings are left exactly as authored.
- Anchors are assigned against every id already present in the rendered document plus the ids this feature reserves, so no heading can duplicate a non-heading element, a theme-owned id, another heading or a generated anchor; the outline links and `aria-labelledby` always target the final unique ids.
- Paginated documents now render `wp_link_pages()` with a localised accessible label and a marked current page, keeping the no-generated-anchor carve-out.
- Anchoring no longer runs on the global `the_content` pipeline: it is scoped to document rendering, so the front page, archives, widgets, plugin-style secondary calls, feeds and REST responses stay untouched.
- Document tables inherit RTL direction and alignment (LTR only through an explicit opt-in), and every print rule is scoped to `body.dzn-document-body` so Portal and homepage print output is unchanged.
- Validation re-run locally: static theme validation, portal dialog tests, `node --check`, PHP lint (62 files), all three render suites, the real-WordPress render check with the new pagination/isolation/print/table assertions, package build and a fresh-clone rerun.

## 0.7.0 — 2026-09-21 — Single Content Page V1 (candidate, unmerged)

- Added one reusable Persian RTL document system for Article, Policy and General/Help content pages, sharing the existing header, footer, palette, typography and prose rules.
- Added deterministic H2/H3 anchors on the canonical `the_content` pipeline: authored ids are preserved verbatim, repeated ids and repeated headings resolve in document order with `-2`, `-3`, … and empty headings fall back to a positional `section-N`. Anchoring is idempotent.
- Added a JavaScript-free table of contents rendered only when the outline has at least three sections: a sticky, labelled desktop navigation plus a native `<details>`/`<summary>` disclosure for small screens. The hidden variant is `display: none` and therefore out of the accessibility tree at that breakpoint.
- Added Article mode (categories, publication metadata, reading time, featured image, related articles from existing categories, previous/next) and restrained Policy mode (publication and last-revision metadata, no categories, no featured image, no related content, no reading time, print support) selected through a standard page template.
- Added General/Help mode as the neutral default for pages, and introduced the 43rem document measure by scoping the shared `--dzn-content` token.
- Added `@media print` rules that remove site chrome, the outline, related content and hint text.
- Student Portal and Teacher Portal templates, assets and fixtures were not modified; `assets/css/portal.css` stays independent of every document rule.
- Validation: static theme validation (now including the document contract), portal dialog tests, `node --check` on every theme script, PHP lint over all theme PHP files and the dependency-free content-page render suite all pass locally. WordPress runtime, browser accessibility/responsive/RTL and print inspection remain staging gates.
- Candidate only: not merged, not deployed. See [Single Content Page V1](docs/CONTENT-PAGE-V1.md).

## 0.6.0 — 2026-09-20 — Teacher Portal V1

- Fast-forwarded independently approved implementation candidate `6ce6718c3038faf892542d033b73d021800d28ee` (tree `8c27144abd3a84d9e8037f3cf92abb7f962742f0`) to `main` and closed Teacher Portal V1 repository work. No deployment occurred; live WordPress, browser, responsive and RTL verification remain staging gates.
- Student Portal behavior and Platform remained unchanged during merge and closeout.
- Recorded the round-2 independent re-review failure: a non-empty malformed timezone identifier passed Account validation, and navigation did not bind its current entry to the requested screen. Round 3 validates timezone values against PHP's canonical IANA identifier set and gives each navigation item a stable screen key whose sole current entry must match the requested screen.
- Added behavioral coverage for valid Brisbane/Tehran timezones, invalid/empty/wrong-type timezones, zero/multiple/wrong-screen navigation currentness and missing navigation screen identity. The candidate remains unmerged and undeployed.
- Recorded the round-1 independent re-review failure: Account validation was still shallow, Home collection members were outside the top-level contract and the class absence fixture used a mismatched identifier. Round 2 now validates the complete Account presentation shape, every Home attention/class/calendar member and the bounded Onboarding envelope.
- Corrected the Student-absence presentation identifier to `student_absence` and added positive absence rendering plus adversarial Home, Account and Onboarding behavioral tests. Malformed Account data cannot expose Connected, Paid, availability, statistics or edit presentation. The candidate remains unmerged and undeployed.
- Recorded independent-review failure TP-1/TP-2/TP-3 and correction round 1: explicitly unavailable, wrong-screen, empty and malformed top-level models now remain unavailable; recognized states alone no longer expose trusted actions; and bounded attention/class contracts require complete context plus explicit capability markers.
- Added behavioral PHP rendering coverage for unavailable and malformed models, malformed recognized replacement/intro/Term/class states, suppressed privileged controls and valid-fixture positive rendering. The candidate remains unmerged and undeployed.
- Added isolated Teacher Portal Home, Account, onboarding and development-preview templates against synthetic display-ready fixtures.
- Added attention, daily class, class-expansion, calendar, availability, account, finance-placeholder, statistics and troubleshooting presentations without domain mutations or provider calls.
- Added fail-closed state rendering, structurally separate private notes and Student-facing practice, accessible progressive-enhancement dialogs and Teacher-Portal-only assets.
- Added executable static, fixture-safety and dialog contract checks. Runtime WordPress and browser visual verification remain staging gates; this candidate is not merged or deployed.

## 0.5.0 — 2026-09-19 — Student Portal V1

- Fast-forwarded independently approved correction candidate `bc891ae401aed0a58c1dea2c5d7587b3046b6773` to `main`; no production deployment occurred.
- WordPress runtime, browser accessibility and responsive/RTL visual verification remain staging gates. Platform integration, Google/provider operations and attendance authority remain outside the Theme.

- Recorded the independent-review failure and correction round: unknown Lesson states now render an explicit unavailable state and suppress all Lesson actions rather than falling open to `upcoming`.
- Replaced live-looking development-preview contacts with an explicit `.invalid` allowlist without changing adapter-supplied production contact behaviour.
- Made the no-`showModal()` path an honest inline, non-modal disclosure with predictable focus and exact opener restoration; native close also restores its opener.
- Added executable render, fixture-isolation and dialog-path regression tests.

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
