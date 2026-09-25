# Mobile and Responsive Baselines

Date: 6 September 2026

The 1,348 px desktop reference was directly rendered and inspected in the Cloud Browser. The 320, 375/390 and tablet baselines below are derived from the exact production CSS media rules and DOM/asset inspection because the available Cloud Browser session has a fixed desktop viewport. They are reliable migration-behaviour baselines, not pixel screenshots. Fresh screenshots at every target width remain a mandatory disposable-staging gate.

| Width | Current behaviour | Primary migration checks |
| --- | --- | --- |
| 320 px | Neve mobile header (collapse threshold 960 px); homepage rules at 980/768/760/600/390 all apply; feature/content grids collapse; fixed mobile CTA and WhatsApp controls appear and add bottom body padding; articles index is one column; article promo stacks; enrolment is a single column and forced LTR | Header/logo/toggle fit without collision; fixed controls do not cover content; no horizontal overflow from long Latin references; button pairs remain usable; readable Persian line length and media width |
| 375 px | Same compact branch as 320 px, including the `max-width:390px` homepage overrides | Touch target spacing, hero hierarchy, CTA crowding, accordion controls, images and mixed-direction enrolment UI |
| 390 px | The 390 px breakpoint is inclusive, so the smallest homepage grid/spacing rules still apply | Boundary regression at exactly 390/391 px; bottom-fixed controls; text wrapping and focus outlines |
| Tablet reference: 768 px | Header remains mobile; homepage 768/760 rules apply; article index is two columns (under 900 but above 620); enrolment is one column at 782 and below; the single-article promo remains horizontal above 680 | Mobile navigation keyboard flow; two-column card balance; form/integration direction; content spacing; no overlap with admin/plugin bars in staging |
| Desktop reference: 1,348 px | Directly observed with no document horizontal overflow on all representative pages; desktop header/menu; article grids and sidebars active; enrolment Amelia root loaded LTR | Compare chrome, hierarchy, reading width, sidebar/promo presence, Rank Math head output and plugin assets |

## Direct accessibility/semantic findings

- All sampled public pages declared `lang="en-AU"`, lacked document `dir`, and exposed Open Graph locale `en_US`.
- Homepage and most content render visually RTL, but enrolment body and Amelia root compute LTR because production Additional CSS forces page 91 and descendants to LTR.
- Teacher, course, regional course and booking-received pages exposed no H1.
- Empty/missing image alt attributes are widespread, including logo instances; ownership is split between theme chrome and WordPress media/content.
- A DOM size heuristic found many interactive elements below 44 px: homepage 17/35, articles 32/33, article 20/30, enrolment 28/31, teacher 16/17, course 17/18, regional page 15/16. This includes inline text links, so each flagged control requires manual classification rather than blanket resizing.

## Behaviour by concern

| Concern | Current baseline | Migration intent |
| --- | --- | --- |
| Navigation | Neve desktop/mobile chrome; collapse at 960 px; WordPress menu URLs are stable | Reuse menu assignments; provide a labelled button, Escape close, focus return and compact 320 px treatment |
| Typography | Vazirmatn body, Lalezar headings; RTL primarily imposed through broad CSS | Truthful document semantics, Vazirmatn compatibility baseline, logical properties and no universal direction rule |
| Spacing | Page-specific breakpoints and many high-specificity overrides | Semantic spacing scale; tolerate legacy blocks in staging before retiring overrides |
| Overflow | None observed at 1,348 px; enrolment CSS explicitly hides overflow/maximises width | Test 320/375/390/768 without masking overflow; isolate long URLs/email/phone/reference values |
| Hero/hierarchy | Homepage section order is partly manipulated by JS | Preserve current content first; replace DOM movement with explicit component/order only during page migration |
| Article reading | Neve cover title/right sidebar plus Enhancements promo | One H1, constrained readable measure, responsive media, explicit optional promo/sidebar components |
| Forms | Amelia integration is present but its root is LTR; no native form was counted during the sampled load | Keep integration isolated in staging; do not implement form submission/business logic in theme |
| Buttons | Numerous plugin/block/theme styles; fixed mobile actions on homepage | 44 px theme control baseline, visible focus, semantic primary/secondary roles |
| Media | Responsive WordPress images generally fit; many missing alts | Keep responsive intrinsic media; fix theme-owned semantics and separately audit content alts |

## Staging capture set

For every representative page, capture full-page and header/content crops at 320, 375, 390, 768 and 1,348 px with Neve baseline and inactive-theme candidate. Record viewport, browser, logged-in state, cache state, URL, timestamp, overflow width and console errors. Existing defects are documented, not requirements for reproduction.
