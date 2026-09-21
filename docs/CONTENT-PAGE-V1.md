# Single Content Page V1 — Article, Policy and General documents

Theme version **0.7.0**. Presentation only. No deployment, no production cutover.

## Why this round exists

Legal, policy and help content (shipping, returns, privacy, terms, teaching guidance) had no shared
document presentation: pages inherited the generic page template, articles had a single-column layout
with no outline, and nothing supported print or long-form Persian reading. This round adds one
resuable document system that reuses the existing header, footer, palette, typography and prose
rules.

## What the system provides

| Capability | Implementation |
| --- | --- |
| One document template | `template-parts/content/content-document.php`, driven by the mode argument |
| Modes | `article`, `policy`, `general` resolved by `dzn_theme_content_page_mode()` |
| Deterministic anchors | `dzn_theme_content_page_anchor_content()` anchors H2/H3 on the canonical `the_content` pipeline (priority 20) |
| Authored ids | preserved verbatim; a repeated id or heading receives `-2`, `-3`, … in document order |
| Empty headings | positional fallback `section-N`, still deterministic |
| Table of contents | `template-parts/content/table-of-contents.php`, rendered only when the outline has at least three sections |
| Sticky desktop outline | `nav.dzn-toc--desktop`, `position: sticky`, `aria-labelledby` |
| Mobile outline | native `<details>`/`<summary>` disclosure: keyboard accessible, no JavaScript, no fake modal |
| Reading time | `dzn_theme_content_page_reading_minutes()`, presentation-only, 200 words per minute |
| Related content | `template-parts/content/related-content.php` — published posts sharing a category, up to three |
| Print support | `@media print` removes site chrome, the outline and related content; Policy pages also state they are print-ready |
| Reading measure | `--dzn-measure-document: 43rem`, reusing the shared prose rules by scoping `--dzn-content` |

## Product decisions

1. **Mode selection** — posts are always Article; pages are General/Help unless they explicitly select
   the `سیاست — Policy (Persian RTL)` page template. This keeps authoring inside the standard
   WordPress page-template mechanism: no custom field, no bespoke workflow, no content lock-in.
2. **Anchor source of truth** — anchors are generated in one deterministic pass over the rendered
   content, and the outline is derived from the same pass, so a link can never point at an anchor that
   was not rendered. Anchoring is idempotent, so re-rendering cannot drift the ids.
3. **Outline presentation** — the desktop outline is a sticky labelled navigation; the mobile outline
   is a native disclosure. Two renderings of one list, toggled by media query, so no JavaScript is
   required and no script can be a single point of failure. The hidden variant is `display: none` and
   therefore absent from the accessibility tree at that breakpoint.
4. **Restraint in Policy mode** — no categories, no featured image, no related content, no reading
   time; only publication and last-revision metadata, the document column, the outline and print
   support. Print rules deliberately drop navigation, footer and promotional surfaces.
5. **General/Help mode** — title, outline and content only. Neutral by construction rather than by
   CSS overrides.
6. **Paginated posts** — content containing `<!--nextpage-->` is left entirely to core so page
   splitting keeps working; those pages simply render without the generated outline. Recorded as a V1
   limitation rather than a silent behaviour change.
7. **Portals untouched** — the Student and Teacher Portal templates, assets and fixtures are not
   referenced, modified or extended by this system, and `assets/css/portal.css` remains independent
   of every document rule.

## Validation (local, this workspace)

Executed:

- `node tests/static/validate-theme.mjs` — passes, including the new document contract checks
  (mode list, anchor functions, `the_content` filter, three-section outline gate, page-template mode
  selection, `<!--nextpage-->` carve-out, both outline variants, native disclosure, JavaScript-free
  assertion, related-content query/reset, 43rem measure, sticky rule, print rules, portal isolation).
- `node tests/static/portal-dialog.mjs` — passes.
- `node --check` on `assets/js/navigation.js`, `portal.js`, `teacher-portal.js`, `pricing-region.js` —
  passes.
- PHP lint over every theme PHP file (`php:8.3-cli` container) — passes.
- `php tests/render/content-page.php` — passes: deterministic Persian anchors, authored-id
  preservation, duplicate resolution, positional fallback, idempotence, reading-time boundaries, the
  three-section gate, the mode contracts, and both outline variants including the empty-outline case.
- `php tests/render/portal-corrections.php`, `php tests/render/teacher-portal-corrections.php` —
  pass unchanged (portal regressions).
- CSS brace balance and the token/palette parity check — pass.

Not executable here (explicit staging gates, not assumed passes):

- real WordPress rendering, editor parity and Gutenberg block output;
- browser visual, responsive, RTL and screen-reader verification;
- print-to-PDF inspection;
- package installation on staging.

## Deliberate exclusions

No page builder, no bespoke authoring workflow, no custom fields, no content migration, no new
JavaScript, no analytics, no promotional templates, no Portal changes, no deployment.
