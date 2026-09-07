# Theme Vision and Design Brief

## Long-term direction

> Build a bespoke, elegant, Persian-first Delnavazan design system and WordPress theme that becomes the stable visual shell for the academy, editorial content and future Delnavazan ecosystem, while Delnavazan Platform remains the independent operational engine underneath it.

## Experience principles

- Culturally Persian, warm, artistic and contemporary—not a SaaS dashboard or generic technology startup.
- Persian-first public semantics and typography, with careful isolation for mixed Persian/Latin content.
- Calm editorial hierarchy, generous reading rhythm and restrained ornament.
- Accessible keyboard, focus, landmark, heading and media semantics.
- Mobile-first robustness from 320 px upward, with no hidden horizontal overflow.
- Reusable tokens and components rather than page-specific colour/layout hacks.
- Progressive enhancement and minimal JavaScript.

## Stable visual-shell responsibility

The theme owns public structure, header/navigation, footer, typography, colour, spacing, responsive layout, Gutenberg presentation and passive display components. It must be capable of presenting current WordPress content and later ecosystem surfaces without becoming coupled to how those surfaces store data or run workflows.

The future two-level Cultural Portal remains deferred. The current academy navigation and stable URLs continue until separately approved information-architecture work.

## Operational-engine boundary

Delnavazan Platform owns identities, roles, teachers/students, eligibility, enrolments, terms, lessons, booking requests, matching/routing, payments, notifications, calendars and persistence. The theme may receive display-ready values from an authorised owner; it must never query Platform tables or reproduce Platform rules.
