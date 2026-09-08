# Delnavazan Production Theme

Authoritative repository for the Delnavazan whole-site production WordPress theme.

Current development version: **0.4.2**
Status: **Increment 0.4.2 source candidate; runtime and visual validation pending; never production**

## Vision

> Build a bespoke, elegant, Persian-first Delnavazan design system and WordPress theme that becomes the stable visual shell for the academy, editorial content and future Delnavazan ecosystem, while Delnavazan Platform remains the independent operational engine underneath it.

## Architecture

- Hybrid classic WordPress theme: PHP template hierarchy plus `theme.json`.
- Existing Gutenberg content renders through `the_content()`.
- Public Persian documents use `fa-IR` and RTL without changing the WordPress/admin locale.
- Editorial header, Turquoise & Pomegranate semantic tokens, a locally bundled Vazirmatn variable webfont, and minimal dependency-free JavaScript.
- Rank Math retains SEO metadata, canonical, social, schema and sitemap ownership.
- The theme contains presentation only: no Platform database, workflow, booking, payment, matching, notification or calendar logic.
- The redesigned homepage is supplied as a Gutenberg core-block pattern; the front-page template continues to render authored content through `the_content()`.
- Regional price display is Theme presentation only: it can suggest or remember a visitor-selected region, but carries no payment, entitlement, pricing-authority or Platform logic.

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

## Font licence

The bundled Vazirmatn font is distributed under the SIL Open Font License 1.1. Its copyright notice and licence are preserved in `theme/assets/fonts/OFL.txt`.

## Safety boundary

This repository is independent from [`Mr-M13/delnavazan-platform`](https://github.com/Mr-M13/delnavazan-platform). Never place theme code in the Platform repository or move domain behaviour into this theme.

No production activation or deployment is authorised by this repository initialization.
