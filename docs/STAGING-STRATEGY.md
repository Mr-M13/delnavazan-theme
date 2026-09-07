# Disposable Staging Strategy

## Recommended environment

Create an access-controlled, disposable WordPress clone on infrastructure separated from production. Use a separate database, uploads copy and hostname; set `WP_ENVIRONMENT_TYPE=staging`; block search indexing with both authentication and `noindex`; use a mail sink; disable outbound webhooks, payment callbacks and production cron side effects; exclude or anonymise live customer/booking data.

The preferred data set is published pages/posts, menus, widgets, media and presentation plugin settings only. If a full copy is operationally unavoidable, restrict access, rotate/remove live integration credentials, block outbound requests and purge personal/business records before testing.

## Pre-activation gate

- Confirm hostname, database name/prefix, uploads path and cache namespace differ from production.
- Record WordPress/PHP/plugin/theme versions and take a staging snapshot.
- Keep Neve 4.2.3 installed and unchanged; export Customizer state and menu/widget assignments.
- Capture Neve baselines for the representative URL set at 320, 375, 390, 768 and 1,348 px.
- Store the URL/canonical/schema/head inventory and console/network-error baseline.
- Confirm email, payment, analytics and webhook traffic cannot reach live services.
- Confirm no customer, booking, payment or learner/teacher private data is required for the presentation tests.

Only after those checks may v0.2 be installed and activated **in the disposable environment**. Production activation is prohibited.

## Validation sequence

1. Run `php -l` on every PHP file and the package boundary scanner.
2. Activate v0.2 in staging and confirm WordPress has no fatal/template errors.
3. Reassign/verify existing primary and footer menu locations without editing item URLs.
4. Render homepage, articles, long article, enrolment, teacher, course, regional course and booking-received pages.
5. Compare `lang`, `dir`, headings, landmarks, keyboard navigation, focus, responsive layout, overflow and media alternatives.
6. Compare Rank Math canonical, robots, description, Open Graph/social fields, JSON-LD and sitemap URLs.
7. Verify Gutenberg core, Otter, APB and existing shortcode output; keep legacy dependencies active.
8. Confirm no request, template or asset references Platform internals or adds Amelia/business behaviour.
9. Run automated accessibility scan plus keyboard and screen-reader smoke tests.
10. Restore Neve from the snapshot and verify the rollback path before any release discussion.

## Rollback

The first rollback is theme reactivation to unchanged Neve 4.2.3 using the staging snapshot. Do not delete Neve, its Customizer data, menus/widgets, Delnavazan Enhancements or presentation plugins. Because this theme performs no data mutation, rollback must not require reverting Platform/business data.

## Exit criteria for Increment 0.2

The strategy, manifest and gates are prepared; no environment was created or modified during repository initialization. Hosting/provisioning may proceed only through CD-authorised infrastructure. Repository initialization does not authorise staging activation.
