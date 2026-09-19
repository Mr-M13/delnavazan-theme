# Student Portal V1 presentation architecture

## Scope

Theme 0.5.0 adds presentation scaffolding for a shallow, Persian-first Student Portal. It extends the accepted recovered 0.4.6 Theme and deliberately does not create an application or authority layer.

The only primary Portal destinations are:

1. **خانه** — announcements, the dominant Upcoming Lesson, Term progress, recent history, feedback and contact.
2. **حساب کاربری** — personal details, timezone preference, past classes, payment/subscription placeholders, future purchases/warranty and the latest five one-way notifications.

Lessons, messages and orders are not top-level destinations. Lesson history stays inline. Notifications are one-way announcements, not chat.

## Templates and data boundary

| Template | Purpose | Data source |
| --- | --- | --- |
| `page-templates/student-portal-home.php` | Real Home presentation | `dzn_theme_student_portal_view_model` filter |
| `page-templates/student-portal-account.php` | Real Account presentation | `dzn_theme_student_portal_view_model` filter |
| `page-templates/student-portal-preview.php` | Synthetic development preview | Theme fixture, gated as described below |

The filter receives `null`, the screen name (`home` or `account`) and the queried page ID. A future authoritative adapter may return a display-ready array. The Theme does not authenticate Students, query Platform storage, resolve canonical status, calculate entitlement or write data. If no model is supplied, the real templates render an honest unavailable state rather than demo data.

## Component inventory

- shared Portal shell and minimal Home + Account navigation;
- dismissible announcement banner;
- dominant Upcoming Lesson with time, timezone and safe actions;
- Lesson state notice;
- Term timeline with separate normal replacement and academy-owed/remedial facts;
- reusable class-history rows;
- presentation-only feedback controls;
- direct WhatsApp, email and Instagram contact links;
- personal details and timezone preference controls;
- payment/subscription summary and receipt-row presentation;
- future purchases and one-year warranty empty state;
- latest-five one-way notification list.

Portal-specific CSS and JavaScript are conditionally enqueued only on the three Portal templates. Existing public page templates and public assets are not changed by Portal selectors.

## Upcoming Lesson state contract

The component accepts one already-resolved presentation state:

- `upcoming` — normal upcoming;
- `starting_soon` — starting soon;
- `absence_notified` — Student absence notified;
- `time_changed` — Lesson time changed;
- `academy_cancelled` — Teacher/academy cancellation;
- `awaiting_reschedule` — awaiting a new time;
- `none` — no Upcoming Lesson.

The view model also keeps lifecycle, schedule, attendance and entitlement fields separate. The Theme does not derive one from another. A schedule release is not presented as a cancellation unless the supplied presentation model explicitly says so. An academy-owed session message renders only when supplied.

The render boundary strictly allowlists those seven values. Missing state resolves to the honest `none` presentation. Any malformed, misspelled or future value renders an explicit unavailable notice and suppresses Lesson facts, Join, absence, calendar and their trusted action URLs; it never falls open to `upcoming`.

## Timezone behaviour

Home renders a human label such as “زمان بریزبن”. Account exposes an editable-looking timezone preference and may carry an IANA value as non-prominent form metadata. Theme controls do not persist the change and never reschedule a Lesson. A future authorised account-preference endpoint must own validation and persistence.

## Calendar, Join and absence boundaries

- Join renders a real link only when a trusted model supplies a URL. Otherwise it is disabled and says why.
- Absence uses a native dialog to demonstrate the future request shape. Its action is `type="button"`, makes no request and reports that nothing was saved. Native close restores the exact opener.
- Calendar uses a native dialog with disabled Google and Apple options. No OAuth, subscription, provider credential, background write or generated event exists in V1.
- Browsers without `showModal()` receive an explicitly non-modal inline disclosure: it does not claim `aria-modal`, leaves focus on the keyboard-operated opener while opening and restores that opener when closed. It intentionally has no modal focus trap.

## Account and commerce boundaries

Profile, timezone and feedback controls are deliberately not submittable forms. Password change can become a link only when an integration supplies an authorised URL.

Payments, receipt download, subscription cancellation, purchases, orders and warranty are presentation reservations. The Theme does not integrate Stripe or cancel a subscription, Term or Lesson. Those three cancellation concepts remain explicitly separate.

## One-way notifications

Account displays at most five display-ready notifications and an optional archive URL. There is no reply, conversation, delivery or notification authority in the Theme.

## Safe development preview

1. Use a disposable WordPress environment with `WP_ENVIRONMENT_TYPE` set to `local`, `development` or `staging` (anything other than `production`).
2. Activate this Theme and create a private page assigned to **Student Portal — Development Preview**.
3. Sign in as a user with `edit_theme_options` and open the page.
4. Use the Home and Account navigation within the preview.

For state review, append `lesson-state=upcoming`, `starting_soon`, `absence_notified`, `time_changed`, `academy_cancelled`, `awaiting_reschedule` or `none` to the Home preview query. These are isolated synthetic storyboards, not inferred domain state.

The preview refuses access in `production` and for unauthorised visitors. Fixtures use only explicitly allowlisted `.invalid` contact destinations (`contact.example.invalid`, `student-portal@example.invalid` and `social.example.invalid`), are held only in PHP arrays, are visibly labelled and are never stored. They cannot reach Delnavazan WhatsApp, email, Instagram or production phone destinations. The real Home and Account templates never fall back to these fixtures; authoritative adapters retain control of production contact values.

## Independent-review correction and merge closeout

The original 0.5.0 candidate failed independent review because an unknown Lesson state fell open to `upcoming`, the development fixture used live-looking Delnavazan contact destinations, and the non-native dialog path imitated a modal without adequate focus behaviour. Corrected candidate `bc891ae401aed0a58c1dea2c5d7587b3046b6773` addressed those findings, passed independent re-review and was fast-forwarded unchanged to `main`.

`tests/render/portal-corrections.php` executes the unknown-state render boundary and fixture factory. `tests/static/portal-dialog.mjs` executes native and disclosure behaviour with a deterministic DOM double. `tests/static/validate-theme.mjs` additionally pins the contact allowlist and fallback contract. Full WordPress runtime, browser keyboard, responsive-width and visual checks remain mandatory staging gates; these dependency-free tests do not claim to replace browser execution. No production deployment has occurred. Platform integration, Google/provider operations, attendance authority and Student write actions remain unimplemented by the Theme.

## Deferred integrations

- Student authentication and protected read model;
- profile and timezone persistence;
- Meet/provider URL authority;
- absence command;
- standards-based or provider calendar export;
- feedback storage;
- payment, receipt and subscription authority;
- purchase/order/warranty authority;
- notification data source and archive;
- production route/page provisioning.

All deferred integrations belong to an authoritative plugin or service. The Theme continues to render escaped display values only.
