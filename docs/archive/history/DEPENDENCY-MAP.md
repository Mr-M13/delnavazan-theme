# Production Dependency Map

Date: 6 September 2026
Production mode: read-only
Evidence labels: **Observed** means directly verified in production by this workstream; **Secondary** means verified by the stopped secondary chat and independently corroborated where noted; **Inferred** means a migration conclusion derived from observed markup/assets.

## Cross-site presentation stack

| Dependency | Evidence | Current responsibility | Theme-switch consequence |
| --- | --- | --- | --- |
| Neve 4.2.3 | Observed | Header/footer wrappers, Customizer layout, page-title suppression, blog/sidebar presentation, typography and responsive navigation | Markup/classes and Customizer styling disappear when Neve is inactive. The new theme must provide equivalents. |
| Gutenberg core | Observed | Stored page/post content and block-generated CSS | Content survives when rendered through `the_content()`; theme must retain block styles and wide/full alignment support. |
| Otter Blocks | Observed | Homepage columns, accordion, headings, icons and posts grid | Blocks and plugin assets must remain active temporarily. Removing Otter is a separate migration. |
| Advanced Post Block | Observed | `/articles/` post listing and its runtime dependencies | Must remain until the articles index is deliberately migrated and regression-tested. |
| Delnavazan Enhancements 2.8.0 public assets | Observed | Global core/footer presentation plus page-scoped homepage, articles and enrolment styling/DOM enhancement | Current selectors depend on Neve and authored classes. Keep temporarily; retire one surface at a time after replacement. |
| Customizer Additional CSS | Observed | Global RTL forcing, payment-icon centring, explicit LTR overrides for page IDs 65, 67, 91 and 713 | It is theme-specific and will not automatically follow a theme switch. Its broad `* { direction: rtl; }` rule must not be copied. |
| WordPress menus/widgets | Observed | Primary menu 14, footer menu 13, blog-sidebar block widgets and footer-one blocks | Assignments may be theme-location-specific. Register the same `primary` and `footer` locations and verify mapping in staging. |
| Rank Math | Observed; Secondary agrees | Canonical, description, robots, Open Graph/social data, JSON-LD/schema and sitemap ownership | Keep plugin active and preserve `wp_head()`. Theme must not duplicate or override metadata/schema. |
| Amelia on `/enrol/` | Observed; Secondary agrees | Current production enrolment integration loaded by stored shortcode | Temporary content dependency only. The new theme adds no Amelia code and must not remove it until separately authorised replacement behaviour is proven. |
| Site Kit, gtag and Meta Pixel | Observed | Analytics/marketing scripts | Outside theme ownership; preserve standard `wp_head()`/`wp_footer()` hooks and validate consent/performance separately. |
| Payment icon plugin | Observed | Payment icon output and page CSS | Preserve during migration; do not absorb payment behaviour into the theme. |

## Representative pages

| Surface | Stored content/blocks | Page-scoped presentation and behaviour | Template/menu/SEO assumptions | Switch survival and required equivalent |
| --- | --- | --- | --- | --- |
| Homepage, page 8, `/` | 53,935-character Gutenberg document; core cover, columns, groups, headings, images, buttons, code/HTML/shortcode plus Otter accordion, columns, heading, icons and posts grid; `bicb/carousel` | Enhancements `homepage.css` (38,058 bytes) and `homepage.js` (7,363 bytes); authored `dn-*` hooks; page-originated inline content; footer/core assets | Neve header/footer, menu 14/13, no template title; Rank Math canonical/meta/schema | Stored blocks survive through `the_content()`. Otter/carousel/Enhancements must remain initially. New theme supplies shared chrome; homepage sections migrate later as reusable patterns/components. |
| Articles index, page 235, `/articles/` | Advanced Post Block plus core Query/post-template/title/excerpt/pagination | Enhancements `articles.css` and `articles.js`; script injects archive H1/intro/sidebar labels; APB runtime includes jQuery/React/Backbone-related assets | Manual page, not `page_for_posts`; menu link must continue to `/articles/`; Rank Math owns head | Stored content survives if APB remains. An accessible archive heading and listing component eventually replace JS injection, but not during live mapping. |
| Long-form article, `/tar-in-guitar-persian-roots/` | Core paragraphs and lists; post ID 340 | Enhancements article promo/sidebar mutation; Otter posts-grid CSS may load; no authored shortcode dependency observed | Neve single-post cover header/right sidebar; template supplies H1; Rank Math head/schema | Body content survives. New single template supplies H1, metadata, reading width, featured media and navigation. Promo/sidebar require an explicit later component decision. |
| Enrolment, page 91, `/enrol/` | Core cover, columns, buttons, paragraphs, spacer and `[ameliacatalogbooking...]` | Amelia public assets/runtime; Enhancements `enrolment.css`; Additional CSS forces page 91 and every descendant LTR | Existing content supplies H1; Rank Math head; current integration root computes LTR | Content and shortcode survive only while Amelia remains. Theme avoids duplicate H1. RTL/LTR integration boundary requires staging validation; no live change or new Amelia coupling. |
| Teacher, page 1096 | Core headings and paragraphs | No page-specific script observed; global Enhancements core/footer and Neve apply | Neve globally hides page title; no observable H1; Rank Math head | Content survives. New page template supplies title H1 because authored content lacks one. A teacher card/detail component can replace page styling later without changing URL/content. |
| Course, page 1088 | Core headings and paragraphs | No page-specific script observed | Neve hides page title; no observable H1; Rank Math head | Same preservation path as teacher page; course component is presentation-only. |
| Regional instrument/course landing page, page 1147 | Core headings and paragraphs | No page-specific script observed | Neve hides page title; no observable H1 | Stored content survives; template-owned H1 corrects document outline in staging. Persian permalink remains unchanged. |
| Booking received, page 1023, `/booking-received/` | WordPress page content | Global Neve/Enhancements presentation; no page-specific runtime observed | No observable H1; stable transactional URL; Rank Math head | Content survives; generic page template supplies one H1. No booking state or backend behaviour belongs in the theme. |

## Current custom CSS boundary

Production Additional CSS is 552 characters and globally applies RTL to `html`, `body`, `*`, text elements, and then forces pages 65, 67, 91 and 713 plus their descendants to LTR. This explains the observed enrolment LTR body/integration. It is a migration artifact, not a semantic model. Increment 0.2 replaces it with document-level RTL plus explicit local isolation utilities; it does not edit the live CSS.

## Header, footer and navigation contract

- Primary menu: WordPress menu ID 14, six existing destinations: home, enrolment, articles, contact anchor, learner portal and teacher portal.
- Footer menu: WordPress menu ID 13, Terms, Privacy, learner/instructor login and contact.
- Neve Customizer currently positions desktop primary navigation and logo and defines a mobile toggle/header layout.
- Blog sidebar and footer-one contain block widgets. They are inventory dependencies, not automatically reproduced by the v0.2 templates.
- A stale Customizer-preview query URL was observed in footer output. It must not be copied; correction is configuration/content ownership outside this increment.

## Asset-coupling indicators

Directly downloaded public Enhancements 2.8.0 assets total 75,786 bytes. Static inspection found high-specificity migration debt: `homepage.css` contains 430 `!important` declarations, `articles.css` 212, `core.css` 127 and `enrolment.css` 58. The assets include Neve selectors, physical left/right rules and DOM-moving JavaScript. A Library source archive labelled 2.0.16 is historical evidence only and is not treated as current 2.8.0 source.

## Article-specific and responsive behaviour

- Articles index: three columns at wide widths, two at 900 px and below, one at 620 px and below.
- Single article promotional material stacks at 680 px and below; sidebar variants change at 960/620 px.
- Homepage JavaScript tags and moves authored sections and inserts mobile CTA/WhatsApp controls.
- Footer JavaScript moves a disclosure into the footer.
- These behaviours must be evaluated as presentation dependencies before their plugins/assets are removed.
