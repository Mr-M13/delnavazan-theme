# Locked Decisions and Required Coordination

## Visual direction

Use the existing `Delnavazan Persian Palette Style Guide.png` as the authoritative exploration. The shortlist below preserves its naming and colour values.

### Turquoise & Pomegranate / existing Option 2 — approved

- Turquoise `#1E7B70`
- Deep turquoise `#104A4D`
- Blue `#315C82`
- Pomegranate `#A33E48`
- Saffron highlight `#D4AB4F`
- Warm background `#FBF8F2`
- Ink `#252726`
- Typography: Estedad headings + Vazirmatn body
- Character: refined, warm and unmistakably Persian without ornamental overload

### B — Pomegranate & Midnight / existing Option 3

- Pomegranate `#813842`
- Deep pomegranate `#56272E`
- Midnight `#25384A`
- Gold `#B9914B`
- Turquoise accent `#56898A`
- Warm background `#FAF7F1`
- Ink `#292725`
- Typography: Estedad headings + Vazirmatn body
- Character: luxurious, intimate and premium

### C — Persian Lapis / existing Option 1

- Lapis `#1E5A5F`
- Deep lapis `#13283F`
- Turquoise `#2A7F83`
- Gold `#C69A45`
- Pomegranate accent `#9D3D46`
- Warm background `#FAF7F0`
- Ink `#25282A`
- Typography: Estedad headings + Vazirmatn body
- Character: elegant, calm and timeless

The existing Option 4, Contemporary Persian Minimal, remains documented but is not in the three-theme shortlist because it is the closest to the generic modern/startup feel Hamed asked us to avoid.

Version 0.2.0 implements these source values as semantic tokens. Functional-state colours are accessibility extensions, not competing brand colours. Saffron remains a decorative highlight; blue is used for focus because it provides stronger contrast.

## Front-end language strategy

### Persian front end — approved

Emit `lang="fa-IR" dir="rtl"` on public Persian pages and mark English legal text with `lang="en-AU" dir="ltr"` where needed. Keep admin locale independent if possible.

The theme implements this with a front-end `language_attributes` filter only. It does not filter `locale`, change the Site Language option, or affect wp-admin/Ajax.

## Header density

### Editorial — approved

Logo, six primary destinations and one restrained enrolment action; mobile opens a full-width panel.

The current six-destination WordPress menu remains the information architecture. Enrolment receives restrained action treatment and portal links remain peers; no two-level Cultural Portal is introduced.

## Repository ownership — approved

`Mr-M13/delnavazan-theme` is the dedicated authoritative code home. The Platform repository remains explicitly out of scope.

## Single Content Page V1 — Article, Policy and General documents

### Mode selection — approved

Posts are Articles; pages are General/Help unless they explicitly select the `سیاست — Policy (Persian
RTL)` page template. The mode is therefore selected with a standard WordPress mechanism, not a custom
field and not a bespoke authoring screen.

### Anchor generation — approved

Anchors are generated deterministically in one pass over the rendered content on the canonical
`the_content` pipeline (priority 20), and the outline is derived from that same pass, so the table of
contents can never link to an anchor that was not rendered. Authored ids are preserved; duplicates
resolve in document order; empty headings fall back to `section-N`. Anchoring is idempotent.

### Outline presentation — approved

The desktop outline is a sticky labelled navigation; the mobile outline is a native `<details>`
disclosure. Rendering the same list twice and toggling it by media query keeps the system free of
JavaScript and keeps keyboard and screen-reader behaviour native. No fake modal dialog is introduced.

### Policy restraint and print — approved

Policy mode omits categories, featured image, related content and reading time, keeps only publication
and last-revision metadata, and enables print support. `@media print` removes the header, footer,
outline, related content and floating actions for every document mode.

### Paginated content — accepted V1 limitation

Content containing `<!--nextpage-->` is left entirely to core, so those pages render without generated
anchors or an outline rather than changing page-splitting behaviour.

### Correction round 1 decisions — approved

- Anchoring is a **read-only**, document-scoped operation: the canonical `the_content` pipeline is still
  consumed (so formatting and shortcodes behave normally), but nothing is registered globally, so no
  other surface can be mutated.
- Print behaviour is opt-in per surface: the document body class is the only switch, and Portal or
  homepage print output must remain the platform's own.
- Document tables follow the document writing direction; LTR is an explicit author choice, not a
  template default.
- A heading whose opening tag cannot be parsed confidently is left untouched rather than rewritten:
  losing a table-of-contents entry is safer than corrupting authored content.

### Correction round 2 decisions — approved

- Anchor ids are reserved through one explicit contract that names every id the template and its
  wrappers emit; a heading — authored or generated — may never take a wrapper id.
- Malformed heading structures are never repaired by the theme: an ambiguous nested or crossing range
  is left exactly as authored and simply contributes no outline entry.
- The print marker is driven by one predicate shared with the rendering decision, so a new page
  template cannot silently inherit document print behaviour.

### Correction round 3 decision — approved

Heading pairs are established from tag order, not by searching each opening tag for a same-level
closing tag. Structure is decided first and mutation second, so malformed markup is always left exactly
as authored while well-formed headings around it are still anchored.

### Correction round 4 decision — approved

Token discovery owns the lexical recovery boundary. A tag is scanned once to either its closing `>` or
the end of its malformed lexeme; bytes inside that lexeme — including heading-looking substrings inside
an unterminated quoted attribute, an unclosed tag, or a nested `<` outside quotes — are skipped as one
unit and can never be re-tokenized as markup. Recovery resumes only at a defensible structural boundary,
so unrelated later valid markup is never greedily consumed.
