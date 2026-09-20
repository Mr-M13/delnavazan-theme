# Teacher Portal V1 — merged presentation scaffold

## Merge closeout

Teacher Portal V1 passed final independent re-review after correction round 3. Approved implementation candidate `6ce6718c3038faf892542d033b73d021800d28ee`, tree `8c27144abd3a84d9e8037f3cf92abb7f962742f0`, was fast-forward merged to `main` as Theme 0.6.0. Repository implementation work is merged and closed. No deployment, production, NIU or Platform change occurred; Student Portal behavior is unchanged. Live WordPress execution, browser interaction, responsive/RTL visual verification and screen-reader checks remain staging gates and are not claimed here.

## Scope and boundary

Teacher Portal V1 is Persian-first Theme presentation against synthetic, display-ready `TeacherPortalReadModel` fixtures. The Theme does not query Platform tables and owns no scheduling, attendance, delivery, payment, absence, replacement, enrolment, Term, Lesson, availability, Google-connection or notification truth. A future adapter supplies the same array shape through `dzn_theme_teacher_portal_view_model`; templates only escape, arrange and progressively disclose it.

Correction round 1 follows independent-review findings TP-1, TP-2 and TP-3. The adapter result must explicitly carry `available=true`, match the requested screen and satisfy that screen's bounded presentation structure. Null, false, empty, explicitly unavailable, wrong-screen and malformed models all render unavailable. Recognized state names never authorize controls by themselves: attention and class items require non-empty references/context plus explicit action/capability markers, including authorized-replacement, schedule-review and flexible-Lesson-entitlement markers. Unknown and malformed recognized states share one unavailable path.

Round 1 independent re-review failed because the Account contract remained shallow, Home did not validate collection contents at its top-level boundary and the synthetic class absence identifier differed from its validator. Round 2 requires complete Teacher identity and navigation on every screen; validates Account profile, recurring availability/exceptions, statistics and recognized Google/payment states as one indivisible presentation contract; and validates every Home attention, class and calendar item before the Home model becomes available. The canonical presentation identifier is now `student_absence`. An otherwise recognized Connected or Paid state inside malformed Account data cannot reach the Account template.

Round 2 independent re-review failed on two remaining presentation-contract gaps: any non-empty timezone string was accepted, and a unique current navigation item was not required to represent the requested screen. Round 3 checks profile timezone against PHP's canonical `timezone_identifiers_list()` with no inference or fallback. Navigation entries now carry stable `home`, `account` or `onboarding` screen identifiers; the complete unique set is required, and the sole `current=true` entry must equal the requested screen. Behavioral adversarial tests cover valid Brisbane/Tehran identifiers, malformed/empty/wrong-type timezones, zero/multiple/wrong-screen current entries and missing screen identifiers.

## Home hierarchy

Home renders an optional academy/technical/reminder announcement, nine “Needs Your Attention” scenarios, chronological classes, a calendar/list preview and troubleshooting. Attention cards cover intro requests, reported Student absence, Teacher disruption, authorised replacement scheduling, paid-Term safety review, flexible-Term dates, Google presentation problems, availability conflicts and administrator requests. Every action opens an inert preview and records nothing.

Today’s examples cover upcoming, starting soon, reported absence, replacement, introductory and flexible classes. Expansion presents Student/schedule context, previous private notes and management previews. Private Teacher notes and Student-facing practice are separate sections with separate labels, controls and visual treatments. “Start Class” is navigation-only in the future contract and cannot imply attendance, delivery, completion or presence.

## Account

Account presents name, synthetic email and mandatory mobile/WhatsApp, timezone, Gregorian/Persian calendar preference, three Google states, recurring multi-block availability, recommended hours, booked classes, dated exceptions, payment/statement placeholders, non-competitive teaching statistics and security/help placeholders. There is no photo UI and no save, OAuth, provider, payment or credential behavior.

## Onboarding

The storyboard has seven future steps: welcome/password, details, Google, availability, readiness, guided tour and completion. Its readiness checklist covers starting classes, emergency contact/Meet knowledge, hardware readiness and music configuration. It creates no invitation, account or readiness authority.

## Troubleshooting

Help covers start/join problems, music audio, connection, Student password and the locked emergency procedure. “Set up Google Meet for music” is a placeholder guide only; no Google automation is present.

## Fixture and unknown-state safety

Fixtures are admin-only outside production, memory-only, use `example.invalid` or inert values, and never persist. Unknown state identifiers render an explicit unavailable/error state and suppress start, scheduling, payment and other privileged controls. Fixture state must never be used as a production fallback.

Dependency-free PHP behavioral tests render the actual shell and components. They cover null/false/empty/explicitly unavailable and malformed top-level models, recognized-but-incomplete replacement, intro, paid-Term, flexible-Term and class items, complete and adversarial Home collections, complete and malformed Account contracts, valid and invalid Onboarding steps/envelopes, and positive Student-absence rendering without Start Class. Static source checks remain supplementary rather than substitutes for rendered-output assertions.

## Accessibility and responsive behavior

Native dialogs use `showModal()`. Unsupported browsers receive an explicit inline non-modal disclosure, with no false `aria-modal`; close and cancel restore opener focus. Semantic headings, labels, status regions, visible focus and RTL are included. Layout is mobile-first for 320, 375, 390 and 430 pixels, then expands at 768 and 1280 pixels. Color always accompanies state text.

## Deferred integrations

Deferred: Platform reads/commands, authenticated Teacher principal resolution, class links, intro decisions, absence acknowledgement, disruption reporting, schedule review/change, replacement commands, profile writes, availability authority, Google OAuth/API, notification delivery, finance/statements and account invitations.

## Staging gates

Before integration or release: real WordPress template execution, keyboard/screen-reader dialog checks, authenticated preview gate, screenshots at all target widths, RTL overflow, public/Student Portal regression, adapter contract review, privacy review and independent acceptance. This document does not mark the scaffold merged, deployed or production-ready.
