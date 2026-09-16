# Delnavazan Production Theme

Authoritative repository for the Delnavazan whole-site production WordPress theme.

Current recovered candidate: **0.4.6**
Status: **review candidate; inactive and not deployed to staging or production**

## Vision

> Build a bespoke, elegant, Persian-first Delnavazan design system and WordPress theme that becomes the stable visual shell for the academy, editorial content and future Delnavazan ecosystem, while Delnavazan Platform remains the independent operational engine underneath it.

## Architecture

- Hybrid classic WordPress theme: PHP template hierarchy plus `theme.json`.
- Existing Gutenberg content renders through `the_content()`.
- Public Persian documents use `fa-IR` and RTL without changing the WordPress/admin locale.
- Editorial header, Turquoise & Pomegranate semantic tokens, and minimal dependency-free JavaScript.
- Rank Math retains SEO metadata, canonical, social, schema and sitemap ownership.
- The theme contains presentation only: no Platform database, workflow, booking, payment, matching, notification or calendar logic.

## Repository layout

- `theme/` — installable WordPress theme source.
- `docs/` — architecture, decisions, production reconnaissance and migration/release records.
- `tests/` — dependency-free static validation.
- `scripts/` — validation and package helpers.
- `dist/` — ignored local build output.

## Local checks

```sh
./scripts/check-theme.sh
./scripts/build-package.sh
```

`check-theme.sh` performs dependency-free static checks and runs `php -l` when PHP is available. PHP lint and WordPress runtime validation remain mandatory staging gates.

The 0.4.6 recovery candidate preserves an exact NIU staging Theme capture in `theme/`. Its Page 8 Gutenberg export and environment-specific WordPress state are non-runtime references under `docs/fixtures/`; see [NIU 0.4.6 recovery](docs/NIU-0.4.6-RECOVERY.md).

## Safety boundary

This repository is independent from [`Mr-M13/delnavazan-platform`](https://github.com/Mr-M13/delnavazan-platform). Never place theme code in the Platform repository or move domain behaviour into this theme.

No production activation or deployment is authorised by this repository initialization.
