# Content-Preservation Matrix

| Surface | WordPress content | Neve-owned | Otter/APB | Enhancements/page-specific | Survives unchanged | Equivalent needed | Must remain temporarily |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Homepage | Page 8 blocks, text, media, links, inline HTML/code/shortcode | Header/footer, container/reset rules, responsive chrome | Otter accordion/columns/heading/icons/posts grid; carousel block | `dn-*` hooks, homepage CSS/JS and DOM movement | Stored content, IDs, links and media | Editorial header/footer; later homepage patterns and stable section components | Otter, carousel provider, Enhancements, analytics |
| Articles | Page 235 APB/query markup | Page wrapper/sidebar/header/footer | Advanced Post Block and core Query | Injected H1/intro/sidebar and article CSS/JS | Page identity, `/articles/`, listing configuration | Accessible archive header and native listing presentation | APB and Enhancements until regression parity |
| Long article | Post 340 paragraphs/lists, media and permalink | Single template, cover title, sidebar, metadata | Incidental posts-grid styles | Promo/sidebar insertion | Article body, post ID, slug, Rank Math data | Single/article template, readable measure, optional explicit promo component | Enhancements article assets until promo/sidebar replacement |
| Enrolment | Page 91 layout and Amelia shortcode | Page wrapper and shared chrome | None material beyond core blocks | Amelia runtime, enrolment CSS, global page-ID LTR override | Page ID, `/enrol/`, authored copy and shortcode while plugin is active | Theme form primitives only; no backend; explicit integration direction boundary | Amelia and enrolment styling until separately authorised replacement |
| Teacher | Page 1096 copy/headings | Hidden page title/shared chrome | None observed | Global core/footer only | All authored content and Persian permalink | Generic page H1; later passive teacher presentation | Current content and Enhancements core/footer during migration |
| Course | Page 1088 copy/headings | Hidden page title/shared chrome | None observed | Global core/footer only | All authored content and Persian permalink | Generic page H1; later passive course presentation | Current content and Enhancements core/footer during migration |
| Regional course | Page 1147 copy/headings | Hidden page title/shared chrome | None observed | Global core/footer only | All authored content and Persian permalink | Generic page H1 and responsive prose | Current content and shared assets |
| Booking received | Page 1023 copy and stable slug | Hidden page title/shared chrome | None observed | Global core/footer only | Content and `/booking-received/` | Generic page H1/status presentation; no status logic | Current content and any plugin-rendered message source |
| Header/navigation | Menu 14 assignments and URLs | All current markup/layout/toggle | None | Enhancements header classes | Menu item records and URLs | Editorial header, accessible toggle, URL-preserving styling | Neve until staging sign-off |
| Footer | Menu 13, widget blocks and site description | Footer markup/areas | Block widgets | Footer CSS/JS/disclosure move | Menu items/site text | Theme footer and validated disclosure location | Neve and Enhancements until replacement proven |
| SEO/head | Rank Math post metadata/options | Semantic template/title-tag hook | None | Analytics scripts through standard hooks | All Rank Math records and canonical URLs | Correct headings/landmarks only | Rank Math and standard `wp_head()`/`wp_footer()` hooks |

## Image alternative-text ownership

**Theme-owned semantics:** the custom-logo home link in v0.2 receives an explicit accessible name even when the Media Library logo alt is empty. Theme components already require/escape their passed image alt text and should use decorative empty alt only deliberately.

**Content/media deficiencies:** sampled production images with empty/missing alt were homepage 10 of 16, articles index 2 of 2, long article 3 of 4, enrolment 2 of 3, and both shared logo instances on teacher/course/regional pages. The theme must not silently invent or rewrite Media Library alt text. Content owners should audit those records/page blocks separately.

## Heading ownership

- Teacher, course, regional course and booking-received pages currently lack an H1 because Neve globally hides page titles and the content does not supply one. This belongs to generic page-template semantics; v0.2 supplies the page title.
- Enrolment already contains an authored H1. The generic template checks preserved raw content and avoids a duplicate.
- `/articles/` currently receives an H1 through Enhancements JavaScript. Its eventual replacement belongs to the articles page migration, not a live content edit.
- Single posts receive their H1 from the theme template.
