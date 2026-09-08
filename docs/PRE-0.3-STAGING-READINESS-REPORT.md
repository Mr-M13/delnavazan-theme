# DELNAVAZAN THEME — PRE-0.3 STAGING READINESS REPORT

Date: 7 September 2026
Status: planning and repository preparation only
Production/staging impact: none

## Decision

No disposable staging environment is currently identified or authorised. Actual
Increment 0.3 runtime work is therefore **not authorised**. The preferred next
step is a host-provided clone only if it satisfies every mandatory control in
this report; otherwise use a newly created temporary WordPress instance under a
non-production hostname.

## Ranked staging options

| Rank | Option | Separation and fidelity | Constraints and safety assessment |
| --- | --- | --- | --- |
| 1 | Host-provided clone/staging | Usually the best production fidelity: equivalent PHP/web server, WordPress, uploads and installed presentation plugins. It is acceptable only with a separate database, uploads location, cache namespace and hostname. | Host access and plan capabilities are unverified. It may copy live credentials by default, so external integrations must be disabled, removed or sandboxed before activation. Cost/quota is host-plan dependent. It must support snapshot/reset or destruction. |
| 2 | Temporary WordPress instance on an isolated hostname/domain | Strong isolation when provisioned with a new database, new uploads and an access-controlled hostname. It can import a sanitized presentation-only dataset and the necessary plugins/settings. | Requires hosting/DNS/PHP setup and possibly licensed-plugin installation. Do not copy production secrets. Cost is provider-dependent. The environment must be disposable and resettable. |
| 3 | Local/disposable WordPress environment | Strongest protection from live side effects and very easy destruction. Suitable for early template, PHP, Gutenberg and static asset checks. | This workspace has no PHP runtime and no local WordPress installation. It is lower-fidelity for host configuration and must not be relied on alone for production-plugin/Rank Math/integration regression. No production credentials should be copied. |
| 4 | Ephemeral container/CI environment | Acceptable only when it can reproduce the required PHP, WordPress, plugin and sanitized-content configuration, with a non-public URL and no outbound effects. | More setup than a host clone and likely lower fidelity. Licensed/premium plugin configuration and media imports can be difficult. Treat as a supplement, not a shortcut around missing isolation controls. |

### Recommended option

Ask the hosting owner to confirm whether a host-provided clone can meet the
mandatory controls below. If it cannot, provision a new temporary WordPress
instance on a clearly non-production hostname. Do not use production, an
unisolated database copy, or a preview that shares production uploads and
credentials.

## Mandatory isolation controls

Before the candidate theme is installed or activated, all facts below must be
recorded in a local manifest and independently verified:

- Separate hostname, database/prefix, uploads location and cache namespace;
  `WP_ENVIRONMENT_TYPE=staging`; no production database write path.
- Access control and `noindex`/robots prevention together; staging must be
  recognisably non-production.
- Mail routed to a sink; WhatsApp disabled; no real customer notification.
- Payment gateways disabled or test-only; no real payment capture.
- Amelia booking actions neutralised; Google Calendar/Meet disabled or isolated;
  no real enrolment/booking creation or external notification.
- Outbound webhooks, production webhook callbacks, production cron effects and
  production analytics disabled or safely isolated.
- No production API secret unless unavoidable and sandboxed; never record
  credentials in the manifest or repository.
- Neve 4.2.3 retained, with a snapshot, Customizer export, menu/widget record,
  plugin inventory and a tested restore/reset path.
- Explicit CD approval for **staging activation** after every other control is
  evidenced. Manifest validity alone is not activation permission.

## Presentation data strategy

Use a sanitized presentation-only copy. Required source material is:

| Surface | Required material |
| --- | --- |
| Homepage | Page 8 Gutenberg/Otter content, media, blocks, existing page-scoped presentation assets and current public structure. |
| Articles index | Page 235, Advanced Post Block/query output and its dependency configuration. |
| Long-form article | Representative public post (ID 340), featured media, relevant taxonomy/sidebar presentation. |
| Enrolment | Page 91 markup and shortcode configuration sufficient to render safely; Amelia must be neutralised. |
| Teacher/course/instrument | Representative public pages 1096, 1088 and 1147; no private teacher or learner records. |
| Booking received | Page 1023 static presentation only; no booking state. |
| Shared chrome | WordPress menus 14 and 13, widgets/footer blocks, logo/media and public navigation URLs. |
| SEO/presentation plugins | Rank Math metadata/schema settings plus Otter, Advanced Post Block and Delnavazan Enhancements configuration required to render the public surfaces. |

Do not import Student, learner, booking, customer, contact, payment or private
teacher data. If a plugin cannot render without a record, use synthetic data
that cannot trigger actions, or exclude the behaviour and record the limitation.

## Rollback and reset proof

1. Record WordPress, PHP and relevant plugin versions; export Customizer state;
   record menu/widget assignments and Rank Math baseline head output.
2. Snapshot the staging database, uploads and current theme/plugin state while
   unchanged Neve 4.2.3 is active.
3. Capture Neve screenshots/head records for every representative surface at
   320, 375, 390, 768 and 1,348 px.
4. Only after the manifest passes and CD authorises staging activation, install
   the exact packaged candidate in staging.
5. After testing, reactivate unchanged Neve, restore the snapshot if needed,
   recheck URLs, canonical/robots/Open Graph/JSON-LD and menus, then record a
   successful reset.

The theme must not mutate business data. Rollback must therefore be a staging
theme/configuration restore, not a Platform or production database recovery.

## Manifest contract

`tests/staging/staging-manifest.example.json` and
`scripts/check-staging-readiness.mjs` require explicit confirmation of:

- candidate repository, branch, commit, version and package checksum;
- CD staging-activation authorisation reference;
- hostname, WordPress/PHP versions and all environment boundaries;
- access/noindex controls;
- mail, WhatsApp, payment, Amelia, Calendar/Meet, webhook, cron, analytics and
  production-integration/API-secret containment;
- sanitized-data and production-write guarantees;
- Neve snapshot/baseline, Customizer/menu/widget/plugin preservation, Rank Math
  baseline and tested rollback/reset/SEO preservation;
- required plugin versions, representative surfaces and viewport widths.

The validator fails closed. Empty values, false controls, production hostname,
missing page paths, missing required widths, malformed build identity and
secret-like field names fail the gate.

## Runtime validation required after a staging gate passes

- `php -l` on every theme PHP file; no PHP or WordPress fatal/template errors.
- Theme activation and `theme.json` acceptance in the actual target runtime.
- Gutenberg, Otter, Advanced Post Block, Enhancements, shortcode and Amelia
  rendering compatibility without adding or removing dependencies.
- Representative template rendering, public `fa-IR`/RTL document semantics,
  mixed-direction isolation, mobile/desktop widths, keyboard flow, focus and
  accessibility smoke checks.
- Rank Math title, canonical, robots, description, social metadata and JSON-LD
  comparison; no URL or sitemap regression.
- Browser console/PHP error review and asset/overflow sanity at every baseline
  width.

## Increment boundary

Increment 0.3 is a **compatibility and evidence-gathering gate**. It is not a
visual redesign increment. Only a narrow reusable compatibility correction is
eligible after staging evidence demonstrates a concrete issue. No page redesign,
content rewrite, dependency removal or broad styling overhaul belongs in 0.3.

## Required from Hamed

1. Confirm the hosting provider and whether it can create an access-controlled,
   separately hosted/database-backed disposable clone; include any cost or quota
   constraint.
2. Choose the host-provided clone if it meets the controls, or authorise a
   temporary isolated WordPress instance instead. Do not provide credentials in
   chat.
3. Confirm that WhatsApp, payment, Amelia, Calendar/Meet, webhooks and cron can
   be disabled or sandboxed for that environment.
4. After the manifest evidence exists, obtain CD approval specifically for
   staging installation/activation. That approval has not been granted here.
