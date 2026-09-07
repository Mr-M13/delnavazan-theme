# Recommended Increment 0.3 Scope

Preparation status: repository-owned preflight tooling is ready, but no
disposable staging target has been identified or modified. See
`INCREMENT-0.3-PREPARATION.md`.

## Name

Disposable staging compatibility and representative-page regression.

## Authorised objective

Exercise the unchanged 0.2.0 theme in a separated, disposable WordPress environment and close only presentation/runtime compatibility defects discovered there. Increment 0.3 is a validation increment, not a production migration or content redesign.

## Redesign boundary

Increment 0.3 is a **compatibility and evidence-gathering gate**. It is not a
visual redesign increment. After a staging result demonstrates a specific
problem, only a narrow, reusable compatibility fix may be considered. Page
redesign, content rewriting, dependency removal, broad styling overhauls and
the future Cultural Portal hierarchy are out of scope.

## Exact scope

1. Provision or verify an access-controlled staging environment with separate database, uploads, cache namespace and hostname.
2. Import published presentation content, menus, widgets, media and required plugin settings using anonymised/no live customer or booking data.
3. Preserve a Neve 4.2.3 snapshot and prove rollback before activating the new theme in staging.
4. Run PHP lint and activate 0.2.0 only in staging.
5. Validate homepage, articles index, long-form article, enrolment, teacher, course, regional course and booking-received pages.
6. Capture Neve and candidate screenshots at 320, 375, 390, 768 and 1,348 px; record overflow, console errors and loaded assets.
7. Verify `fa-IR`/RTL semantics, mixed-direction content, accessible navigation, headings, focus, media, forms and keyboard/screen-reader smoke behaviour.
8. Verify Gutenberg core, Otter, Advanced Post Block and current shortcode/integration output without removing those dependencies.
9. Compare Rank Math canonical, robots, description, Open Graph/social metadata, JSON-LD/schema and sitemap behaviour.
10. Apply only narrow, reusable presentation fixes needed for staging compatibility; document every retained temporary dependency.
11. Re-run the boundary scan and prove Neve rollback after testing.

## Explicit exclusions

- No production upload, activation or deployment.
- No production content, page ID, permalink, menu or Rank Math metadata changes.
- No Delnavazan Platform code/schema/behaviour changes.
- No Booking Request backend, matching, student/enrolment/term/lesson, payment, notification or calendar implementation.
- No new Amelia coupling and no removal of current Amelia/Enhancements/Otter/APB dependencies.
- No homepage redesign, Cultural Portal hierarchy or Increment 0.4 work.

## Exit evidence

A staging validation report, exact environment/build record, screenshot set, accessibility/SEO regression results, retained-dependency list, rollback proof, clean validation output, package checksum and reviewed Git commit/branch.
