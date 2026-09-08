# Increment 0.4.2 implementation handoff

## Scope

This source candidate is a presentation-only Theme increment. It changes no WordPress data, Platform behaviour, payment authority, booking flow, external communication or SEO-plugin ownership.

The editorial homepage pattern order is:

1. Hero
2. Essential facts
3. Instrument discovery
4. How it works
5. Term pricing
6. FAQ
7. Direct contact
8. Selected articles
9. Institutional footer

## Regional pricing presentation

The Theme contains a small, isolated display module with the configured public figures:

- AU — A$250
- NZ — NZ$250
- US — US$250
- CA — C$250
- EU — €150
- GB — £150

A visitor can select the region manually. The selected code is stored only in the browser under dzn-pricing-region. If no manual choice exists, the browser may ask ipwho.is for a country code and show a non-authoritative suggestion. Failures and unsupported countries remain neutral and direct the visitor to choose a region; there is no silent USD fallback.

This is presentation only. It does not create a price, payment, entitlement, enrolment, acceptance or Platform authority. Any future transaction must obtain authoritative pricing from its own owner.

## Final visual asset debt

The current abstract string artwork is intentionally temporary in the hero and instrument folio. It establishes replaceable media architecture, not the final photographic direction. Before visual acceptance, replace the six core Image blocks and hero Image block with approved Academy-owned or properly licensed visual material. Keep meaningful images with Persian alt text; decorative images should remain empty-alt.

## Runtime acceptance focus

Validate at 320, 375, 390, 768 and desktop widths:

- header/logo/navigation open-close state;
- inline gutters and no horizontal clipping;
- Vazirmatn loaded before typographic sign-off;
- hero media, editorial hierarchy and section rhythm;
- regional-price selector, manual persistence and neutral detection failure state;
- keyboard focus, details disclosure and reduced-motion behaviour;
- footer contact destinations;
- authored content, URL/permalink and Rank Math head preservation.

Do not activate ordinary plugins, alter blog_public, weaken the MU guard, or use the Theme as an operational integration point.
