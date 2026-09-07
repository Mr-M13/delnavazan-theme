# Theme Architecture

## Decision

Use a hybrid classic WordPress theme with `theme.json` rather than a full-site-editing-only theme.

Why: the current site is Neve-based and its content is a mixture of core Gutenberg blocks, Otter blocks, Neve starter assets, inline page code, and page-scoped Delnavazan Enhancements assets. A hybrid theme preserves the WordPress template hierarchy and renders existing blocks unchanged while allowing a gradual move to native patterns and components.

## Ownership boundary

| Concern | Owner |
| --- | --- |
| URLs, post/page identity, content and SEO metadata | WordPress content + Rank Math |
| Teacher, student, lesson, booking, eligibility and matching rules | Delnavazan Platform |
| Payments, notification delivery and calendar behaviour | Delnavazan Platform/integration modules |
| Markup, layout, type, colour, responsive behaviour and interaction states | Theme |
| Temporary legacy presentation | Delnavazan Enhancements until explicitly migrated |

The theme consumes display-ready values only. It must not become a second application layer.

## Layers

1. Tokens: semantic colour, type, spacing, radius, motion and layout values.
2. Base: document, Persian typography, links, fields, focus and selection.
3. Layout: containers, site chrome, reading width, grids and responsive rules.
4. Components: buttons, cards, notices, badges, form controls and states.
5. Content: articles, captions, quotes, media and legacy Gutenberg compatibility.
6. Templates: WordPress hierarchy and stable wrappers.

## Data contract

Theme components receive arrays of display values. A plugin, block render callback, or template controller may map domain objects into those arrays. The theme does not know internal Platform IDs, status transitions, capabilities, schema, or repositories.

## JavaScript policy

JavaScript is progressive enhancement only. Version 0.2.0 includes a small navigation toggle with Escape close, focus return and initial-link focus. Native elements (`details`, forms, links) are preferred. No framework or general-purpose slider is included.

## Accessibility baseline

- Skip link and focus target.
- Named navigation landmarks.
- 44 px minimum interactive target.
- Visible `:focus-visible` outline.
- Reduced-motion handling.
- Semantic article/cards and ordered heading expectations.
- Explicit live regions for loading/error/empty states.
- Logical properties for RTL.
- LTR opt-in for code, tables, URLs and mixed-language content.
- Public `fa-IR`/RTL language attributes without a WordPress/admin locale change.
- Generic page H1 fallback that yields to an authored content H1.

## Typography

Vazirmatn is the compatibility baseline because it is already used on the site. Font files are not bundled in v0.2.0; staging must confirm a locally hosted font source with appropriate licences and no layout shift.

## Versioning

The theme uses semantic versions. Every production candidate must include a changelog, ZIP checksum, test report, screenshots and rollback instruction.
