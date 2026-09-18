# Tests

`static/validate-theme.mjs` provides dependency-free checks for required Theme structure, version, JSON parsing, semantic token parity, CSS block balance, prohibited business/domain coupling, the Portal view-model seam, preview gate, Home + Account architecture and presentation-only interaction boundary.

WordPress runtime, PHP lint, rendering, Gutenberg, responsive, accessibility and Rank Math regression checks belong to the disposable-staging gate documented in `docs/STAGING-STRATEGY.md`.
