# Delnavazan Theme — Rollout and Migration

## Goal
Move presentation incrementally without coupling rollout to Platform authority changes.

## Rules
- Develop and validate locally/disposable-first.
- Preserve existing content and URLs unless an explicit migration decision says otherwise.
- Introduce template areas incrementally; do not switch the whole site merely to test one surface.
- Theme rollout must never be used to bypass unfinished Platform contracts.
- Keep rollback simple: retain the previous known-good package and avoid destructive content transformations.
- Production activation is a separate explicit gate.

## Suggested rollout order
1. Shared shell/tokens/typography.
2. Content/article/static surfaces.
3. Homepage/navigation.
4. Portal/personalised presentation only after required Platform contracts are stable.
5. Production activation after validation and explicit approval.

## Validation before activation
- visual baseline;
- mobile/RTL;
- accessibility;
- content preservation;
- broken-link/template smoke;
- Platform contract compatibility;
- rollback package available.

## Staleness trigger
Update when rollout order, staging method, content-preservation strategy or rollback method changes, and before any production activation.
