# Production Presentation Reconnaissance

Date: 6 September 2026  
Mode: read-only public and authenticated metadata inspection

## Current platform

- WordPress 7.1, PHP 8.5.9.
- Active theme: Neve 4.2.3; no child theme is active.
- Front page: page ID 8 (`home`) at `/`.
- Post index is a normal page at `/articles/`; WordPress `page_for_posts` is not assigned.
- WordPress reports site language `en-AU`; sampled public documents retain `lang="en-AU"`, have no document `dir`, and expose Open Graph locale `en_US`. Most pages are visually RTL through CSS.
- Body font: Vazirmatn. Heading font: Lalezar.
- Rank Math supplies SEO functionality.

## Presentation dependencies observed

- Core Gutenberg blocks.
- Otter Blocks advanced columns, accordion and post-grid assets.
- Neve starter-content images and Neve header/footer/runtime CSS/JS.
- Delnavazan Enhancements 2.8.0 page-scoped CSS/JS for core, homepage, footer and articles.
- Advanced Post Block on content/archive surfaces.
- Payment icon plugin output.
- Homepage markup/assets include inline CSS/JavaScript and plugin-generated runtime data. The enrolment page stores an Amelia shortcode and loads the current Amelia public integration.
- Google Site Kit and Meta Pixel scripts are present but remain outside theme ownership.

## Stable public URL inventory

Preserve at minimum:

- `/`
- `/enrol/`
- `/articles/`
- `/honarjo/`
- `/ostad/`
- `/pay/`
- `/payment-success/`
- `/booking-received/`
- `/booking-cancelled/`
- `/cancellation-help/`
- `/how/`
- `/info/`
- `/terms/`
- `/privacy-policy/`
- `/refund_returns/`
- existing Persian article, teacher and location/instrument landing-page permalinks.

## Current navigation

Primary labels include registration, articles, contact, student login, teacher login and home. Footer links include Terms, Privacy, learner/instructor login and contact.

One footer link in the inspected article output contained a stale Customizer preview query string. Treat this as a content/configuration defect to correct separately; do not copy it into the new theme.

## Key risks

1. A direct switch would remove Neve wrappers and CSS on which current block markup depends.
2. Disabling Otter or Delnavazan Enhancements during the theme migration would conflate independent changes and make rollback unreliable.
3. Inline page behaviour can be mistaken for theme behaviour.
4. The `lang`/`dir` mismatch affects accessibility, pronunciation and SEO.
5. `/articles/` is a hand-built page rather than the native posts page.
6. Public pages include business-function surfaces. Styling them is safe only after their plugin behaviour is independently stable.

## Additional Increment 0.2 findings

- Production Additional CSS globally forces RTL, then forces page IDs 65, 67, 91 and 713 plus descendants LTR. Enrolment body and Amelia root were directly observed computing LTR.
- Teacher 1096, course 1088, regional course 1147 and booking-received 1023 expose no H1 under the current hidden-title configuration.
- Sampled pages contain numerous empty/missing image alt values, including both shared logo instances.
- Public Enhancements 2.8.0 CSS/JS was downloaded read-only for static inspection. A separately preserved 2.0.16 archive is historical only.
- Direct desktop reference at 1,348 px found no horizontal overflow on representative pages. Mobile behaviour is documented from exact production media rules; pixel captures remain a staging gate.

## Secondary reconnaissance incorporation

The stopped secondary chat supplied read-only observations for the same seven representative surfaces. Direct checks corroborated the language/direction mismatch, Open Graph locale, enrolment LTR/Amelia boundary, Rank Math ownership, missing H1s and missing logo alt text. No contradiction was found. The secondary chat made no implementation or environment change and is not a source-code authority.
