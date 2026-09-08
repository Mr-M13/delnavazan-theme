# Tests

`static/validate-theme.mjs` provides dependency-free checks for required theme structure, version, JSON parsing, semantic token parity, CSS block balance and prohibited business/domain coupling.

WordPress runtime, PHP lint, rendering, Gutenberg, responsive, accessibility and Rank Math regression checks belong to the disposable-staging gate documented in `docs/STAGING-STRATEGY.md`.

`staging/staging-manifest.example.json` is the non-secret evidence contract for
Increment 0.3. Copy it to `staging/staging-manifest.local.json`, populate it
only after a disposable environment exists, then run:

```sh
node scripts/check-staging-readiness.mjs tests/staging/staging-manifest.local.json
```

The readiness check is intentionally fail-closed: every isolation, side-effect,
data-safety, baseline, plugin-preservation, rollback and CD staging-activation
authorisation condition must be explicit before staging theme activation. Never
put credentials, tokens, personal data or production database details in the
manifest.
