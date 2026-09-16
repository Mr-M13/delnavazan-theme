# NIU WordPress state captured for Delnavazan Production Theme 0.4.6

Captured read-only on 2026-09-16 from the authenticated NIU staging WordPress administration and public WordPress REST responses. No WordPress setting, content, menu, plugin, or Theme file was changed.

## Homepage

- `show_on_front`: `page`
- `page_on_front`: `8` — کلاس‌های آنلاین موسیقی ایرانی برای ایرانیان خارج از کشور
- `page_for_posts`: `0` — no posts page selected
- Page 8 raw Gutenberg source: `page-8-gutenberg.raw.html`
- Raw source size: 34,472 bytes
- Raw source SHA-256: `516909114af0f4fd98c7693a751e625ee0b2253fbfc164a101febd95abd0a2ee`
- Page modified time reported by edit-context preload: `2026-09-16T04:57:31`
- Article Query Loop contains `dzn-home-random-posts` on the inner `core/post-template`.
- The outer `core/query` also retains `namespace: delnavazan/home-random-posts`; the recovered PHP callback does not depend on that namespace.

## Menus and locations

Registered/assigned locations exposed by the active Theme:

- `primary` -> menu ID `14`, فهرست اصلی
- `footer` -> menu ID `13`, پانویس

All captured items are top-level (`parent = 0`) WordPress Page objects.

### Primary menu 14

| Order | Menu item | Navigation title | Object | Object ID | URL |
| ---: | ---: | --- | --- | ---: | --- |
| 1 | 1227 | خانه | page / Front Page | 8 | https://niu-nailhouse.com/ |
| 2 | 170 | ورود هنرجویان | page | 67 | https://niu-nailhouse.com/honarjo/ |
| 3 | 171 | ورود اساتید | page | 65 | https://niu-nailhouse.com/ostad/ |
| 4 | 349 | مقالات | page | 235 | https://niu-nailhouse.com/articles/ |
| 5 | 169 | ثبت نام | page | 91 | https://niu-nailhouse.com/enrol/ |

### Footer menu 13

| Order | Menu item | Navigation title | Object | Object ID | URL |
| ---: | ---: | --- | --- | ---: | --- |
| 1 | 158 | Terms & Conditions | page | 154 | https://niu-nailhouse.com/terms/ |
| 2 | 159 | Privacy Policy | page / privacy-policy | 152 | https://niu-nailhouse.com/privacy-policy/ |
| 3 | 161 | Learner Login | page | 67 | https://niu-nailhouse.com/honarjo/ |
| 4 | 162 | Teacher Login | page | 65 | https://niu-nailhouse.com/ostad/ |

## Theme mods and Customizer

Complete exposed Theme-mod state for `delnavazan-production-theme`:

- `active_theme`: empty Customizer control value
- `custom_logo`: `1255`
- `nav_menu_locations[primary]`: `14`
- `nav_menu_locations[footer]`: `13`

Related site option:

- `site_icon`: `1256`

Exact Additional CSS for `delnavazan-production-theme`:

```css
@media (min-width: 64rem) {
  .menu-toggle { display: none; }
}
```

## Homepage and Theme media dependencies

| Attachment ID | Purpose / slug | Full URL |
| ---: | --- | --- |
| 1255 | Custom logo / `logo-2` | https://niu-nailhouse.com/wp-content/uploads/2026/09/Logo.png |
| 1256 | Site icon / `cropped-logo-png-2` | https://niu-nailhouse.com/wp-content/uploads/2026/09/cropped-Logo.png |
| 1270 | Homepage hero / `web-hero` | https://niu-nailhouse.com/wp-content/uploads/2026/09/WEB-HERO.png |
| 1308 | Footer logo / `logo-footer` | https://niu-nailhouse.com/wp-content/uploads/2026/09/Logo-footer.png |
| 1313 | Instrument artwork / `c-avaz` | https://niu-nailhouse.com/wp-content/uploads/2026/09/C-Avaz.webp |
| 1314 | Instrument artwork / `c-baghlama` | https://niu-nailhouse.com/wp-content/uploads/2026/09/C-Baghlama.webp |
| 1315 | Instrument artwork / `c-daf` | https://niu-nailhouse.com/wp-content/uploads/2026/09/C-Daf.webp |
| 1316 | Instrument artwork / `c-guitar` | https://niu-nailhouse.com/wp-content/uploads/2026/09/C-Guitar.webp |
| 1317 | Instrument artwork / `c-kamancheh` | https://niu-nailhouse.com/wp-content/uploads/2026/09/C-Kamancheh.webp |
| 1318 | Instrument artwork / `c-ney` | https://niu-nailhouse.com/wp-content/uploads/2026/09/C-Ney.webp |
| 1319 | Instrument artwork / `c-piano` | https://niu-nailhouse.com/wp-content/uploads/2026/09/C-Piano.webp |
| 1320 | Instrument artwork / `c-santour` | https://niu-nailhouse.com/wp-content/uploads/2026/09/C-santour.webp |
| 1321 | Instrument artwork / `c-setar` | https://niu-nailhouse.com/wp-content/uploads/2026/09/C-Setar.webp |
| 1322 | Instrument artwork / `c-tanboor` | https://niu-nailhouse.com/wp-content/uploads/2026/09/C-Tanboor.webp |
| 1323 | Instrument artwork / `c-tar` | https://niu-nailhouse.com/wp-content/uploads/2026/09/C-Tar.webp |
| 1324 | Instrument artwork / `c-tombak` | https://niu-nailhouse.com/wp-content/uploads/2026/09/C-Tombak.webp |
| 1325 | Instrument artwork / `c-violin` | https://niu-nailhouse.com/wp-content/uploads/2026/09/C-Violin.webp |
| 1338 | Theme background ornament / `bg-ornoments` | https://niu-nailhouse.com/wp-content/uploads/2026/09/BG-Ornoments.webp |
| 1340 | Footer email action / `email-light` | https://niu-nailhouse.com/wp-content/uploads/2026/09/Email-light.webp |
| 1341 | Footer Instagram action / `insta-light` | https://niu-nailhouse.com/wp-content/uploads/2026/09/Insta-light.webp |
| 1342 | Footer/floating WhatsApp action / `whatsapp-light` | https://niu-nailhouse.com/wp-content/uploads/2026/09/WhatsApp-Light.webp |

The page-8 raw block source directly references attachments 1270 and 1313-1325. The Theme PHP hard-codes IDs 1308 and 1340-1342. The Theme CSS references the public URLs for 1270 and 1338. The active Theme mod references 1255; the WordPress site-icon option references 1256.

## Plugin state

Ordinary plugins: all 17 installed ordinary plugins are inactive. Therefore no ordinary plugin currently supplies Query Loop, homepage-block, Additional CSS, menu/header/footer, or Theme-presentation behaviour.

Automatically active Must-Use plugins:

- `delnavazan-staging-guard.php` — Delnavazan Staging Safety Guard (staging-only safety controls)
- `wp-nc-easywp.php` — EasyWP Plugin 2.2.0 (hosting cache/monitoring integration)

Active drop-in:

- `object-cache.php` — Redis Object Cache Drop-In 2.4.2

The EasyWP MU plugin and Redis drop-in can affect caching. The staging guard affects staging safety, not Theme presentation. No active plugin is responsible for the random Query Loop callback; that callback is in the active Theme.

## Runtime source observations

- Active stylesheet: `assets/css/theme.css` via handle `delnavazan-theme`, filemtime version.
- Active scripts: `assets/js/navigation.js` via `delnavazan-navigation`, filemtime version; `assets/js/pricing-region.js` via `delnavazan-pricing-region`, Theme-versioned and localised with `dznThemePricing`.
- Random callback: `functions.php`, function `dzn_home_random_article_query()`, filter `query_loop_block_query_vars`.
- Callback matches a block whose class contains `dzn-home-random-posts`, then sets `posts_per_page = 3`, `orderby = rand`, and `ignore_sticky_posts = true`.
- No forced `post__in`, post-ID-235 forcing, `DZN FUNCTIONS PHP IS ACTIVE` diagnostic, or namespace-dependent workaround exists in the recovered Theme source.

## Recovery comparison classification

- A — Theme source: 27 paths differ from repository 0.2.0: 11 modified and 16 added; 6,919 insertions and 200 deletions. This includes Theme 0.4.6 PHP, CSS, JS, font/image assets, pricing presentation, and pattern registration.
- B — Gutenberg content: page 8 is live WordPress content, preserved separately as raw block markup. Its live Query Loop structure differs from the bundled `patterns/homepage-editorial.php`, including the required inner marker.
- C — WordPress configuration: static homepage ID 8; menu IDs 14/13 and their assignments/items; custom logo 1255; site icon 1256; and Additional CSS are WordPress state, not Theme source.
- D — Media: the attachment dependency set is recorded above. Theme-bundled media and WordPress Media Library attachments are distinct sources even where imagery corresponds.
- E — Plugins: no ordinary plugin is active. EasyWP/Redis provide hosting cache behaviour; the NIU staging guard provides staging safety.
- F — Temporary/superseded: the outer Query block still carries the older namespace metadata, but current runtime logic targets the inner marker and does not need it. Temporary source diagnostics and post forcing are absent.

Remote file modification timestamps were not preserved by the SFTP download, so the transfer alone cannot reliably attribute individual changed files to manual editor actions. The exact recovered bytes and their comparison to repository 0.2.0 are preserved; no unsupported authorship claim is made.
