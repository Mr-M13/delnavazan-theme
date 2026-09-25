# NIU 0.4.6 source recovery candidate

This document records the lossless recovery of the NIU staging Theme source into the authoritative Theme repository. It is a review candidate only: it does not authorise packaging, activation, deployment, or changes to NIU.

## Provenance and byte identity

- Recovery capture date: 2026-09-16 (read-only NIU staging access).
- Recovered runtime directory: `delnavazan-production-theme`.
- Recovered Theme version: `0.4.6`.
- Recovery archive SHA-256: `336423c9bb9733c2a91219d32a3a7221fc5545d0d6775ad943923f3e95aa5513`.
- Source manifest SHA-256: `5e9c7db3c76f13f31fb7f4d59e63600c69d003af8c28033ff12c53d679bdaf99`.
- Manifest entries: 45 runtime files.

The candidate `theme/` directory is a byte-for-byte copy of that recovered runtime directory. Nothing in `theme/` was redesigned, regenerated, reformatted, or otherwise changed during reconciliation. The 0.2.0 repository history remains intact.

Two recovered text assets use CRLF bytes (`theme/inc/assets.php` and `theme/assets/js/pricing-region.js`). The repository attributes preserve those bytes rather than normalising them during Git transport. They also identify the recovered CRLF lines, the one tab-only blank line in `theme/footer.php`, and a license-text trailing space in `theme/assets/fonts/OFL.txt` as intentionally inherited formatting, so `git diff --check` can distinguish them from newly introduced whitespace defects.

## WordPress state reference — non-runtime evidence only

- Raw Page 8 Gutenberg export: [`fixtures/niu-page-8-gutenberg-20260916.raw.html`](fixtures/niu-page-8-gutenberg-20260916.raw.html)
  - SHA-256: `516909114af0f4fd98c7693a751e625ee0b2253fbfc164a101febd95abd0a2ee`
  - Size: 34,472 bytes
- Captured WordPress state: [`fixtures/niu-wordpress-state-20260916.md`](fixtures/niu-wordpress-state-20260916.md)

These are evidence and migration references, not Theme runtime inputs. IDs, menu assignments, media URLs, Customizer values, the static homepage selection, Additional CSS, MU plugins, and the Redis drop-in are environment-specific WordPress state. They must not be fabricated, imported, or hard-coded into the Theme source.

The raw Page 8 fixture is intentionally retained without a header so its source bytes remain exact. Its outer Query block retains older `delnavazan/home-random-posts` metadata, while the recovered runtime callback intentionally identifies the inner `core/post-template` class `dzn-home-random-posts`; the callback has no namespace dependency.

## Reconciliation boundary

- Theme-bundled font/image assets are committed under `theme/assets/`.
- WordPress Media Library attachments remain WordPress-owned and are recorded only as dependencies in the state reference.
- The captured Additional CSS is retained exactly in the state reference and is not duplicated into the Theme stylesheet.
- Header, footer, homepage, final call-to-action, pricing presentation, and random-post behaviour are recovered source, not a new design proposal.
- No diagnostic callback, forced article ID, WordPress database state, secret, token, private key, local staging filesystem path, or production configuration is included.

## Follow-up boundary

Later work may normalise the bundled homepage pattern against current Page 8 content, review environment migration procedure, and make product/design changes through separately approved increments. Those are deliberately out of scope for this lossless 0.4.6 recovery candidate.
