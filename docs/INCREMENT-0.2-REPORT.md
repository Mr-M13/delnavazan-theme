# DELNAVAZAN THEME — INCREMENT 0.2 REPORT

Date: 6 September 2026
Status: completed local implementation and read-only reconnaissance; not installed, activated or deployed
Production impact: none

## Executive outcome

Increment 0.2 extends the accepted hybrid classic theme without touching production or Delnavazan Platform. It completes the representative dependency/preservation maps, establishes mobile migration baselines, implements the approved Option 2 semantic tokens and public Persian document semantics, advances the reusable Editorial header, and prepares a disposable-staging runbook.

The authoritative theme artifact is **not currently in a Git repository**. No branch or HEAD therefore exists. The available `Mr-M13/delnavazan-platform` repository is explicitly excluded and remains untouched. A dedicated theme repository decision is required before repository history or release work.

## 1. Authoritative repository and coordination

| Item | Result |
| --- | --- |
| Accepted theme source | Local `delnavazan-production-theme/`, now version 0.2.0 |
| Repository | None detected |
| Branch | Not applicable |
| HEAD | Not applicable |
| Dedicated theme repository | No |
| Platform repository | `Mr-M13/delnavazan-platform` is excluded from theme ownership |
| Secondary chat implementation | None; no files, commits, pushes, PR, staging or production changes |

Secondary observations were treated as supporting evidence and independently checked where access allowed. Direct evidence corroborated `lang="en-AU"`, absent document `dir`, `og:locale=en_US`, enrolment LTR/Amelia runtime, Neve/Gutenberg/Otter/Enhancements/Rank Math dependencies, missing H1s, and missing logo alt text. **No contradiction was found.** Direct inspection additionally located the production Additional CSS page-ID rules that cause the enrolment LTR boundary and quantified the current public Enhancements 2.8.0 asset coupling.

## 2. Completed dependency map

The mapped production stack is Neve 4.2.3, Gutenberg, Otter, Advanced Post Block, Delnavazan Enhancements 2.8.0 page-scoped assets, Additional CSS, WordPress menus/widgets, Rank Math, current Amelia enrolment output, payment icons, and analytics scripts.

Representative surfaces completed:

| Surface | Principal dependency | Migration treatment |
| --- | --- | --- |
| Homepage, page 8 | Core/Otter/carousel blocks, `dn-*` hooks, homepage CSS/JS and inline runtime | Render stored content unchanged first; keep providers; replace section-by-section later |
| `/articles/`, page 235 | APB/core Query plus JS-injected heading/intro | Preserve manual page/URL and APB; replace JS presentation only after regression tests |
| Long article, post 340 | Core prose, Neve single/sidebar, Enhancements promo | Preserve post/URL/Rank Math; theme supplies semantic article layout |
| `/enrol/`, page 91 | Stored Amelia shortcode, Amelia runtime, enrolment CSS, forced LTR | Keep current dependency temporarily; isolate and test in staging; add no new coupling |
| Teacher 1096 | Core content, hidden Neve page title | Preserve content/permalink; generic theme page title supplies missing H1 |
| Course 1088 | Core content, hidden Neve page title | Same generic semantic correction; passive course presentation only |
| Regional course 1147 | Core content, Persian permalink, hidden title | Preserve unchanged; generic page H1 and responsive prose |
| `/booking-received/`, page 1023 | Page content/shared chrome; no visible H1 | Generic page template supplies H1; no booking logic |

Full evidence and asset details are in `docs/DEPENDENCY-MAP.md`.

## 3. Content-preservation matrix

Stored WordPress content, IDs, slugs/permalinks, media relationships, menu items and Rank Math records remain authoritative and are not rewritten. Neve-owned chrome/title/sidebar layout needs theme equivalents. Otter/APB and current Enhancements/Amelia output remain temporary dependencies until each page migration is proven. Rank Math retains exclusive SEO-head authority.

The H1 correction is ownership-aware: ordinary pages receive a template title when raw content lacks an H1; authored H1 content such as enrolment is not duplicated. Theme-owned logo navigation receives an accessible name. Content/Media Library alt deficiencies are recorded for a separate editorial audit and are not silently rewritten.

Full matrix: `docs/CONTENT-PRESERVATION-MATRIX.md`.

## 4. Mobile baseline findings

- Direct 1,348 px rendering showed no horizontal document overflow on sampled pages.
- At 768 px, current header is mobile, homepage tablet/mobile rules apply, articles are two-column, and enrolment collapses to one column.
- At 390 px and below, homepage compact rules, single-column articles, stacked article promo, single-column enrolment and fixed mobile CTA/WhatsApp controls apply.
- At 320/375 px the same smallest branch applies; header fit, fixed-control overlap, long Latin references and button crowding are priority checks.
- Current global RTL CSS and page-ID LTR overrides are brittle; enrolment body and Amelia root directly compute LTR.
- Missing H1/alt semantics and numerous sub-44 px interactive-element candidates are migration accessibility findings, not defects to reproduce.

The Cloud Browser’s fixed desktop viewport prevented trustworthy pixel screenshots at mobile sizes. Exact production media rules were mapped as the baseline; 320/375/390/768 screenshots are a mandatory staging gate and are not claimed as completed visual passes.

Full baseline: `docs/MOBILE-BASELINES.md`.

## 5. Persian document-semantics design

The theme filters only front-end `language_attributes()` output to emit `lang="fa-IR" dir="rtl"`. It does not change the WordPress Site Language, locale, user locale, database, wp-admin or Ajax. English/LTR islands use semantic `lang`/`dir`, `bdi`, `.dzn-ltr` or `.dzn-reference`; email/phone/URL/code receive bidi isolation. Numerals are not automatically converted, avoiding corruption of references, URLs and plugin data.

Rank Math currently outputs `og:locale=en_US`. Because Rank Math owns social metadata, any correction must be made through its configuration/SEO ownership and validated in staging—not overridden by the theme.

Full design: `docs/PERSIAN-SEMANTICS.md`.

## 6. Option 2 token implementation

The preserved Persian Palette Style Guide was located and used as the authoritative source:

| Semantic role | Value |
| --- | --- |
| Background / surface / elevated | `#FBF8F2` / `#F2ECE1` / `#FFFFFF` |
| Primary / secondary / muted text | `#252726` / `#104A4D` / `#73736E` |
| Primary action / hover-focus | `#1E7B70` / `#104A4D` |
| Secondary action / information / focus | `#315C82` |
| Border | `#DDD6CA` |
| Pomegranate accent | `#A33E48` |
| Saffron highlight | `#D4AB4F` |
| Success / warning / error | `#2F6B4F` / `#945B16` / `#A33A3A` |

Tokens are defined in `theme.json` and the CSS token layer; components consume semantic variables rather than page-specific colours. Saffron is not used as the focus ring because its white contrast is insufficient; secondary blue is used instead.

## 7. Editorial header progress

The reusable header now provides a Persian-first RTL layout, decorative turquoise/saffron/pomegranate accent, compact brand/tagline treatment, unchanged WordPress menu URLs, restrained enrolment action treatment, peer portal links, labelled mobile toggle, Escape close and focus return. The text label hides below 480 px while the accessible button name remains. No Cultural Portal hierarchy is present.

The header uses the current `primary` menu location rather than hard-coding/reordering destinations. The theme-owned custom-logo home link receives an accessible name even if the existing Media Library alt is empty.

## 8. Disposable staging strategy

Recommended: an access-controlled disposable clone with separate hostname/database/uploads/cache, `WP_ENVIRONMENT_TYPE=staging`, authentication plus `noindex`, mail sink, outbound webhook/payment blocks, removed live keys, and published presentation content only or anonymised data. Neve 4.2.3 and existing dependencies remain installed for rollback. Baseline capture and environment-separation checks precede any staging activation.

No staging environment was created or changed in this increment. Provisioning must use CD-authorised infrastructure. Full runbook: `docs/STAGING-STRATEGY.md`.

## 9. Files/components created or changed

Changed:

- `style.css`, `theme.json`, `README.md`, `CHANGELOG.md`
- `inc/setup.php`, `inc/template-tags.php`
- `header.php`, `template-parts/content/content-page.php`
- `assets/css/theme.css`, `assets/css/editor.css`, `assets/js/navigation.js`
- `docs/ARCHITECTURE.md`, `docs/DECISIONS.md`, `docs/MIGRATION.md`, `docs/RECONNAISSANCE.md`

Created:

- `docs/DEPENDENCY-MAP.md`
- `docs/CONTENT-PRESERVATION-MATRIX.md`
- `docs/MOBILE-BASELINES.md`
- `docs/PERSIAN-SEMANTICS.md`
- `docs/STAGING-STRATEGY.md`
- `docs/REPOSITORY-BOUNDARY.md`
- `docs/VALIDATION.md`

Existing passive teacher, instrument, course, notice and state components remain presentation-only and unchanged.

## 10. Validation performed

Passed: JSON parsing, JavaScript syntax, static delimiter/non-empty checks, semantic-token/reference scan, runtime architectural boundary scan, package integrity, URL/content/dependency read-only inspection, direct desktop RTL/overflow/head checks, and colour-contrast calculations.

Not yet passed: PHP lint (PHP unavailable locally), WordPress activation/template runtime, exact mobile screenshots, Gutenberg editor/runtime parity, automated accessibility/screen reader, performance, and full Rank Math/page regression. These remain hard staging gates; no pass is implied.

## 11. Remaining migration risks

1. No dedicated theme repository/history/remote ownership.
2. Current Enhancements 2.8.0 public CSS/JS is strongly coupled to Neve selectors, physical directions and DOM mutation; only an older 2.0.16 source archive is available.
3. Enrolment is intentionally LTR today and still loads Amelia; RTL integration and eventual replacement require separate authorised migration.
4. Rank Math social locale remains `en_US` despite Persian content; SEO owner must correct/validate it without theme duplication.
5. Mobile visual baselines need exact-width staging screenshots and overflow testing.
6. Missing content/media alt text requires editorial remediation outside theme code.
7. Menu/widget location assignments, blog sidebar, footer blocks and the stale footer Customizer URL require staging/configuration verification.
8. Homepage/APB/Otter/inline behaviour must remain until replacements are proven page-by-page.

## 12. Decisions required from Hamed

One genuine decision is required: approve/provide a dedicated production-theme repository and ownership/name. The theme must not be placed in `delnavazan-platform`. No palette, language or header decision remains; all three are locked. Staging infrastructure selection is a CD operational decision unless it changes product scope or data handling.

## Stop condition honoured

No production content, theme, plugin, menu, metadata, setting, database, staging environment or presentation was changed. The theme was not uploaded, activated or deployed. Work stopped before repository creation/history changes and before any action capable of altering live presentation.
