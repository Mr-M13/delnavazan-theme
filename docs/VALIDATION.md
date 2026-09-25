# Delnavazan Theme — Validation

## Required checks
Before a Theme package is considered release-ready:
- package builds reproducibly;
- PHP/template syntax passes;
- core public templates render without fatal error;
- responsive/mobile layouts are checked;
- Persian RTL and mixed-direction content are checked;
- keyboard/focus/accessibility baseline is checked;
- existing content/URLs are preserved as intended;
- Student Portal surfaces consume Platform data/actions without inventing authority;
- no credentials/secrets are embedded;
- previous known-good package/rollback path exists.

## Evidence
Validation should record:
- package/version or commit;
- test environment;
- checks performed;
- known limitations;
- whether production activation was authorised.

## Current state
Theme is parked while Platform is the critical path. A green validation document does not mean production deployment is authorised.

## Staleness trigger
Update after every material Theme package/release candidate and after any change to validation requirements. Maximum review interval: 45 days while Theme development is active.
