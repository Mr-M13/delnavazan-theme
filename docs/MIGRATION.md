# Incremental Migration and Rollback

## Gate 0 — Complete reconnaissance (Increment 0.2)

- Preserve an export/archive of the active Neve customisation state and current Delnavazan Enhancements source when staging is provisioned.
- Inventory menus, widgets, Customizer values, representative blocks, media and Rank Math output.
- Capture current production CSS-breakpoint behaviour and direct desktop reference; complete same-browser pixel captures in disposable staging.
- Record plugin output contracts without changing them.

## Increment 1 — Inactive scaffold (0.1.0)

- Local source only.
- Templates, `theme.json`, tokens and passive components.
- No upload, installation, activation or content edits.

## Increment 2 — Mapping and disposable-staging preparation (this 0.2.0 package)

- Finish dependency/preservation maps, semantics, tokens, Editorial header and validation plan locally.
- Do not create Git history until a dedicated repository is approved.
- Provision/import only through CD-authorised isolated staging; do not expose publicly.
- Confirm representative existing URLs resolve with matching Rank Math metadata.
- Add narrow compatibility CSS only when it is clearly presentation-owned; do not rewrite page content.

## Future increment — Shared chrome staging validation

- Rebuild header, navigation and footer in staging.
- Reuse existing menu assignments.
- Validate the approved public language/direction strategy.
- Verify portal and transactional pages before proceeding.

## Increment 4 — Articles

- Migrate `/articles/`, single posts, archives and search first because they have low business-logic coupling.
- Preserve post IDs, slugs, canonicals, schema and internal links.
- Replace article presentation from Delnavazan Enhancements only after visual parity and accessibility checks.

## Increment 5 — Static academy pages

- About, information, how-to, terms, privacy and refund pages.
- Preserve content and URLs; migrate markup incrementally.

## Increment 6 — Homepage

- Rebuild section-by-section using reusable blocks/patterns.
- Preserve the online-academy positioning.
- Hamnavaz remains a secondary community section and consumes plugin-rendered data only when the Platform/Expansion owner provides it.

## Increment 7 — Functional surfaces

- Enrolment, pay, portals and status pages last.
- Presentation work only after the relevant Platform behaviour is complete and independently tested.
- No theme-owned submission, routing, payment or notification logic.

## Production release gate

1. Full files and database backup.
2. Staging sign-off at 360, 390, 430, 768, 1024 and 1440 px.
3. Keyboard and screen-reader smoke tests.
4. URL/canonical/schema comparison.
5. Core Web Vitals and asset-budget comparison.
6. Functional smoke tests for all plugin surfaces.
7. Short maintenance window and named rollback operator.

## Rollback

- Keep Neve 4.2.3 installed and unchanged.
- Retain its Customizer configuration and menu assignments.
- Retain Delnavazan Enhancements unchanged until each responsibility is formally retired.
- If validation fails, reactivate Neve; clear only normal page caches; do not revert business data because the theme does not mutate it.
- Do not delete the new theme or legacy presentation until the observation window completes.
