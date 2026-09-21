# Single Content Page V1 — Article, Policy and General documents

Theme version **0.7.0**. Presentation only. No deployment, no production cutover.

## Correction round 1 (independent review FAIL)

The independent review of candidate `8e0af6253ff9c0243ff25000bf15a681efcf376b` failed on six findings.
All six are corrected on additive descendants of that commit. Correction round 1, Correction round 2,
Correction round 3 and Correction round 4 of the *platform* work are unrelated; this section records the
Theme review round only.

| Finding | Correction |
| --- | --- |
| 1. Opening-tag parsing broke on `>` inside quoted attribute values | The heading scan is now quote-aware: the opening tag ends at the first `>` **outside** quotes, and the visible text comes from the heading's inner content, never from an attribute fragment. An implausible opening tag (with unbalanced quotes, or with a nested `<` outside quotes, including markup that WordPress texturised into `title="a > b&#8221;`) is skipped entirely rather than guessed. Attributes containing `>`, `<`, single quotes, entities, nested inline markup and mixed Persian/Latin text are covered by adversarial tests. |
| 2. ID collisions were only checked between H2/H3 | Anchors are now assigned against **every** id already present in the rendered document plus the ids this feature reserves (`dzn-toc-title-desktop`, `dzn-toc-title-mobile`, `dzn-related-title`), so a heading can never duplicate a non-heading id, a theme-owned id, another heading's authored id, or a generated anchor. The outline `href`s and `aria-labelledby` target the final unique ids, and resolution stays deterministic and idempotent. |
| 3. Paginated documents stranded readers | Document modes now render `wp_link_pages()` after the content with a localised accessible `aria-label` and the current page marked. The deliberate carve-out is preserved: paginated content still receives no generated anchors and no outline. |
| 4. Anchoring ran globally on `the_content` | The global filter is removed. Anchoring happens inside document rendering only (`dzn_theme_content_page_data()` renders through `apply_filters( 'the_content', … )` and anchors the result for this template). Archives, the front page, widgets, plugin-style secondary calls, feeds and REST responses keep untouched WordPress output, and Portal surfaces never reach the code path. |
| 5. Document tables were forced LTR | Document tables now inherit the document direction and alignment (Persian RTL by default) with horizontal overflow retained; LTR is available only through an explicit `dir="ltr"` or the `.dzn-table--ltr` opt-in. |
| 6. Print rules leaked to every surface | Every print rule is scoped through `body.dzn-document-body`, a class added only to singular post/page document responses that are not the front page and not a Student or Teacher Portal screen. The homepage, archives, Portal screens, feeds and REST responses keep their own print behaviour, and a static check fails if any print selector is unscoped. |


## Why this round exists

Legal, policy and help content (shipping, returns, privacy, terms, teaching guidance) had no shared
document presentation: pages inherited the generic page template, articles had a single-column layout
with no outline, and nothing supported print or long-form Persian reading. This round adds one
resuable document system that reuses the existing header, footer, palette, typography and prose
rules.

## Correction round 2 (independent re-review FAIL)

The independent re-review of correction-round-1 candidate `060f2cb2f864ec7e3f64b691f59eec36ee2fd8f1`
failed on five findings. All five are corrected on additive descendants of that commit.

| Finding | Correction |
| --- | --- |
| 1. A valid opposite quote inside a quoted value was rejected (`<h2 title="don't > stop">`) | The scanner is driven purely by the **active** delimiter: an apostrophe inside a double-quoted value (or a double quote inside a single-quoted value) is ordinary content, and `>`/`<` remain legal inside any quoted value. The global even-count quote assumption is gone. A tag that never closes (no `>` outside quotes, a nested `<` outside quotes) is reported as malformed and left untouched. |
| 2. Malformed nested/overlapping headings corrupted output | Heading candidate ranges are grouped into clusters of overlapping ranges before any mutation. Only clusters containing exactly one heading are anchored; an ambiguous cluster is left byte-stable and contributes no outline entry, and a neighbouring unambiguous heading is still anchored. Verified for H2/H3 nesting in both directions, same-level nesting and crossing ranges. |
| 3. Only content ids were reserved | A single explicit contract, `dzn_theme_content_page_reserved_ids( $post )`, now returns every id the template and its wrappers emit before anchors are assigned: `main-content`, the dynamic `post-{ID}` article wrapper, and the feature's own `dzn-toc-title-desktop`, `dzn-toc-title-mobile` and `dzn-related-title`. The anchoring path consumes that function (with a pure `dzn_theme_content_page_owned_ids()` fallback for direct calls), so a future wrapper id is added in one place. A runtime render of the whole `single.php` document proves final DOM id uniqueness. |
| 4. The shared utilities layer was deleted | The previous correction truncated the file inside the print-block region and removed the utilities layer (`screen-reader-text`, `.screen-reader-text:focus`, `[hidden]`, `.site-branding__description`, `.menu-toggle__label`, navigation/footer link-colour compatibility, `.dzn-owned-media-slot`). The layer is restored **byte-identical** to the reviewed parent, the CSS diff against that parent now contains only the intended document rules, and a static check fails if a shared utility or the token layer shrinks. |
| 5. The print marker was too broad | One shared predicate, `dzn_theme_content_page_is_document_response()`, decides the marker and mirrors what the templates do: single posts, default pages and the Policy template qualify; the front page, non-singular requests, both Portals and any custom or plugin page template do not. Runtime checks cover every positive and negative case. |

Correction round 2 was also regression-audited beyond the listed items: pagination navigation, the
no-global-filter guarantee, paginated carve-out, table RTL, print scoping, accessibility utilities,
portal isolation and query hygiene are all re-asserted.

## Correction round 3 (independent re-review FAIL — one parser defect)

The independent re-review of correction-round-2 candidate `642501f7106697af5e65ecef4373a82c9d429bf1`
failed on one blocking parser defect: mismatched heading closures were not rejected before pairing, so
`<h2>First</h3><h3>Second</h2>` was rewritten into an anchored H2 with a misleading outline entry, and
`<h2>Broken</h3><h2>Valid neighbour</h2>` let the malformed opening greedily consume the later valid
heading's closing tag.

**Correction.** Headings are no longer found by searching each opening tag for the next same-level
close. `dzn_theme_content_page_heading_tokens()` reads every H2/H3 opening and closing tag in document
order using the existing quote-aware tag-end logic and pairs them structurally:

- an opening tag pushes a pending heading; an opening while another heading is open marks both the
  inner and the enclosing heading as ambiguous;
- a closing tag that matches the pending heading completes a well-formed pair;
- a closing tag of a different level, a stray closing tag with nothing pending, a nested pair, and an
  opening that never closes all record a malformed region;
- any pair whose span overlaps a malformed region is discarded, so a malformed heading never produces
  an anchor or an outline entry and never rewrites authored markup;
- pairing resumes after the malformed region, so a later structurally valid heading is anchored and
  outlined normally, and a malformed opening can no longer absorb a valid heading's closing tag.

Only the outline levels (H2/H3) are rewritten; every other heading level participates in the structure
walk so nesting and mismatches are detected, but is never modified. The rebuild still runs from the end
of the document backwards, so offsets stay valid, authored ids, the reserved-id contract, deterministic
collisions and idempotence are unchanged.

**Adversarial matrix executed** (render suite, all passing): reverse crossing; malformed opening
followed by a valid neighbour (both levels); nested H2/H3 and H3/H2; same-level H2/H2 and H3/H3;
stray closing tags before and between valid headings; unclosed heading followed by another heading;
malformed region followed by several valid Persian headings; nested inline non-heading markup inside a
valid heading; the correction-round-2 opposite-quote and `>`/`<` attribute cases; and idempotence plus
final-id uniqueness across a mixed malformed/valid document. The disposable WordPress runtime
additionally renders the reproduced cases end-to-end: the malformed tags survive untouched and
unduplicated, the three valid neighbours are anchored and outlined in both outline variants, no
malformed heading receives an id or an `href`, and the recovered document stays id-unique.

## Correction round 4 (independent re-review FAIL — malformed-opening-tag recovery defect)

The independent re-review of correction-round-3 candidate `eb778142a021ba8b71eba2d2659687dde8559328`
failed on one new blocking parser-recovery defect. After rejecting a malformed heading opening tag, the
tokenizer could still interpret heading-like text inside that malformed tag or its unterminated quoted
attribute as genuine markup. The reviewer reproduced three inputs where malformed markup was mutated
and erroneous anchors/outline entries were generated.

**Correction.** Token discovery now walks the document left-to-right and scans every tag lexeme once.
`dzn_theme_content_page_tag_scan()` returns either the tag's closing `>` or a malformed-lexeme boundary:

- an unterminated quoted attribute or an unclosed tag consumes the remainder of the document, because
  no trustworthy tag boundary exists before it;
- a nested `<` outside quotes makes the opening tag malformed; tokenization resumes only after the
  first `>` outside quotes following that `<`, so the malformed lexeme and any heading-looking bytes
  inside it are skipped as one unit;
- a malformed non-heading tag is scanned the same way, so a heading-looking substring inside its
  attribute/text cannot leak back into the token stream.

`dzn_theme_content_page_heading_tokens()` now reads every `<` in order and never re-tokenizes bytes
inside a recorded malformed region. Well-formed H2/H3 tags are still paired by the C3 ordered stack,
so mismatched closes, nesting, crossing, stray closes and unclosed openings keep their byte-stable
behaviour, and a structurally separate valid neighbour is still anchored after recovery.

**Adversarial matrix executed** (render suite, all passing): unterminated double- and single-quoted
attributes containing literal H3/H2-looking text; a malformed opening tag with a nested `<` outside
quotes; malformed tags followed by valid H2/H3 neighbours at both levels; multiple malformed regions
separated by valid headings; `>`/`<` inside correctly terminated quoted attributes; C2 opposite-quote
cases; the C3 reverse-crossing/mismatched/nested/stray-close/unclosed matrix; mixed-case H2/H3;
Persian valid neighbours after malformed regions; a heading-looking substring inside a malformed
non-heading tag; and idempotence plus final rendered id uniqueness.

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
- `php` lint of all 62 theme PHP files in a `php:8.3-cli` container — no failures.
- CSS brace balance and the token/palette parity check — pass.

- Correction round 2 (same disposable WordPress runtime): a full `single.php` render of a post whose
  content carries `id="post-{ID}"` and `id="main-content"` produced a document in which every id was
  unique, the wrapper kept its own id and the colliding authored ids resolved to `post-{ID}-2` and
  `main-content-2`; a post with malformed nested headings rendered without duplication, produced no
  outline entry for the malformed cluster and still anchored its unambiguous neighbours; and the print
  marker appeared for a post, a default page and the Policy template and was absent for the homepage
  and a Student Portal page template.
- Correction round 1 (same disposable WordPress runtime): the article rendered with an
  entity-encoded `>` in a heading attribute, a Persian table and the outline; every generated id
  matched `^[\p{L}\p{N}-]+$` and no id repeated; a paginated document rendered
  `dzn-document__pagination` with the localised `aria-label="صفحه‌های این سند"`, the current page marked
  and a link to the second page, while still receiving no outline; `apply_filters( 'the_content', … )`
  outside document rendering and a full archive loop produced **no** generated heading ids; the
  document body class was present on a singular post and absent on the homepage and on a Student
  Portal page template.
- Real WordPress rendering (disposable WordPress 6.8.3 + MariaDB 11.4.13, the candidate theme the only
  active theme, driven with WP-CLI outside the repository): the theme's own `single.php` resolves for a
  single post, the Policy page template resolves for a page carrying
  `_wp_page_template = page-templates/content-policy.php`, and a plain page keeps `page.php`. Article
  rendering produced both outline variants, the native disclosure, the labelled desktop outline,
  reading time, category presentation and related content; every outline `href="#…"` had a matching
  rendered `id`, Persian headings received Persian anchors, and the repeated heading resolved to
  `id="انتخاب-ساز-2"`. General mode stayed neutral with an outline, Policy mode rendered revision
  metadata and the print hint with no categories, related content, reading time or featured image, and
  a `<!--nextpage-->` post produced no generated outline and no rewritten heading markup.
- Local package build: `delnavazan-production-theme-0.7.0.zip` (byte-verified `Version: 0.7.0` inside
  the archive) with its SHA-256 recorded; `dist/` remains ignored by Git.

Not executable here (explicit staging gates, not assumed passes):

- browser visual, responsive, RTL and screen-reader verification (no browser runtime in this workspace);
- editor/Gutenberg visual parity and block-pattern inspection;
- print-to-PDF inspection;
- package installation on staging.

## Deliberate exclusions

No page builder, no bespoke authoring workflow, no custom fields, no content migration, no new
JavaScript, no analytics, no promotional templates, no Portal changes, no deployment.
