# DELNAVAZAN THEME — INCREMENT 0.3 PREPARATION

Date: 7 September 2026
Status: preparation complete; staging activation not authorised by this record
Production impact: none

## Outcome

The repository now contains a fail-closed staging-readiness manifest and
validator. No theme runtime file changed. No WordPress site was uploaded to,
configured or activated.

No currently connected environment qualifies as disposable Delnavazan staging:

- WPVibe exposed the Delnavazan production site and an unrelated site only.
- No separate Delnavazan staging site was registered there.
- A focused public search found no indexed staging endpoint. This does not prove
  that a private host-level staging instance does not exist.
- The available Cloud Browser session exposed production WordPress only and was
  not used to enter, modify or configure it.

Therefore the pre-activation gate remains closed.

## Candidate identity

| Field | Accepted value |
| --- | --- |
| Repository | `Mr-M13/delnavazan-theme` |
| Base branch | `main` |
| Accepted foundation commit | `03176f92e2735e49d91709db1ec0899024c6ebed` |
| Theme version | `0.2.0` |
| Rebuilt package checksum | `8b7f35ed2de244da64529940f5a4326140deb18aee33343ca58f458c8a958f7d` |

The checksum identifies the previously rebuilt 0.2.0 package. Rebuild and
record a new checksum from the exact staging-test commit before installation.

## Repository-owned readiness contract

`tests/staging/staging-manifest.example.json` records only non-secret evidence:

- candidate commit, version and package checksum;
- staging hostname and `WP_ENVIRONMENT_TYPE`;
- database, uploads and cache isolation;
- access control and indexing blocks;
- mail, webhook, payment, cron and analytics containment;
- absence of live customer, booking and payment data;
- Neve snapshot, configuration preservation and tested rollback;
- representative page paths and required viewport widths.

`scripts/check-staging-readiness.mjs` rejects an incomplete manifest, a
production hostname, malformed build identifiers, missing representative pages,
missing viewport widths and fields whose names suggest embedded secrets.

## Required next evidence

Before any staging upload or activation, CD or the hosting owner must provide or
authorise a disposable environment and confirm all of the following:

1. The exact staging hostname, database boundary, uploads boundary and cache
   namespace are separate from production.
2. Authentication and `noindex` are both active.
3. Email is routed to a sink; outbound webhooks, production payment callbacks,
   production cron effects and production analytics are blocked.
4. The imported dataset excludes live customer, booking and payment records.
5. Neve 4.2.3 remains installed; a snapshot, Customizer export and menu/widget
   record exist.
6. Rollback to Neve has been exercised successfully before the candidate theme
   is activated.
7. PHP and WordPress versions plus required plugin versions are recorded.

## Activation boundary

Passing the local manifest validator is necessary but not sufficient. The
values must be independently verified against the staging host. A completed
manifest never authorises production work, and it must contain no credentials,
tokens, personal information or database contents.

When the gate is satisfied, Increment 0.3 may install and activate the unchanged
candidate only in that disposable environment, execute the regression plan in
`NEXT-INCREMENT-0.3.md`, and finish by proving Neve rollback again.

Increment 0.3 remains a compatibility and evidence-gathering gate. It is not a
visual redesign increment: page redesign, content rewriting, dependency removal
and broad styling changes remain outside its scope.
