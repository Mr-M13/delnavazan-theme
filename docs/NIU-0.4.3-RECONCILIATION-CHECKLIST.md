# Delnavazan Theme 0.4.3 — NIU reconciliation checklist

Use this checklist only after the 0.4.3 package has passed source validation and is manually installed on NIU. It does not authorise activation, reset, deployment, or production changes.

## Record before changing content

- Record the active Theme name, version, and package SHA-256.
- Record the front-page ID, revision count, and current block structure.
- Record primary and footer menu assignments.
- Record Site Identity / Custom Logo attachment ID and current rendered logo markup.
- Record active caching, minification, Additional CSS, and Site Editor / global-style configuration.

## Confirm the loaded source

- Inspect the stylesheet URL and its `ver=0.4.3` query value.
- Inspect the computed-style origin for `.site-header`, `.primary-navigation`, and primary-navigation links.
- Confirm the header is the light institutional field with readable dark navigation text.
- Confirm the Custom Logo renders as `.custom-logo-link` containing the intended image and that no text brand appears beside it.
- If a different stylesheet, global style, page-level style, or cache layer wins, record it. Do not reset NIU configuration.

## Reconcile Gutenberg content conservatively

- Inspect the existing homepage against the corrected pattern before editing.
- Make the smallest safe block-level update required; do not wholesale-replace the page by default.
- Preserve WordPress revisions and verify that the standalone human-trust section is structurally removed, not hidden.
- Confirm the hero uses the neutral owned-imagery slot rather than the Delnavazan logo artwork.
- Confirm one concise course-facts presentation, four How It Works steps, and no three-month term claim.
- Confirm the compact six-discipline folio contains no invented course URLs or stock imagery.

## Menus, pricing, and public contact

- Confirm same-page anchors do not display as multiple current pages.
- Confirm hover, focus-visible, current-page, and enrolment CTA states are visually distinct.
- Confirm all six regional prices and editable manual region selection.
- Confirm phone `0413 413 004`, email `delnavazan@mail.com`, and Instagram `@insta.delnavazan` only.
- Confirm `+61 431 364 200` is absent from public header, homepage, footer, menus, and contact content.

## Visual and accessibility validation

- Check 320px, 375px, 390px, tablet, and desktop widths.
- Check keyboard navigation, mobile-menu Escape behaviour, focus-visible, RTL flow, reduced motion, contrast, tap targets, and horizontal overflow.
- Confirm FAQ remains above articles and that homepage article dates remain absent.
- Confirm no URL, permalink, Rank Math, Platform, payment, or SEO-authority change occurred.
