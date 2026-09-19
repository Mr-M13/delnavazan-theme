# Tests

`static/validate-theme.mjs` provides dependency-free checks for required Theme structure, version, JSON parsing, semantic token parity, CSS block balance, prohibited business/domain coupling, the Portal view-model seam, preview isolation, Home + Account architecture and presentation-only interaction boundary. `static/portal-dialog.mjs` executes native-dialog and non-modal disclosure paths against a deterministic DOM double. `render/portal-corrections.php` renders an unknown Lesson state and executes the synthetic fixture to prove fail-safe output and inert contacts.

WordPress runtime, PHP lint, rendering, Gutenberg, responsive, accessibility and Rank Math regression checks belong to the disposable-staging gate documented in `docs/STAGING-STRATEGY.md`.
