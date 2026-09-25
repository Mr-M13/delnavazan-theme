# Persian Document Semantics

## Theme-level implementation

The theme filters the string produced by WordPress `language_attributes()` only on public, non-Ajax requests. It removes any pre-existing `lang`/`dir` attributes and emits:

```html
<html lang="fa-IR" dir="rtl">
```

It does **not** filter WordPress `locale`, change the Site Language option, change user locale, update the database, or affect wp-admin/Ajax. This preserves the current administration environment.

The template continues to call core hooks and functions: `language_attributes()`, `wp_head()`, `body_class()`, `wp_body_open()` and `wp_footer()`.

## Mixed Persian and Latin content

- Use `<bdi dir="auto">…</bdi>` when the direction is unknown or user/content supplied.
- Use `lang="en-AU" dir="ltr"` around an English phrase or English legal-content section.
- Use `dir="ltr"` or `.dzn-ltr` for deliberately Latin layout.
- Use `.dzn-reference` for order/reference identifiers whose punctuation must remain stable.
- Email, telephone and URL inputs, plus code-like elements, receive LTR direction, left alignment and bidi isolation.
- Do not apply `direction` to every descendant; that breaks embedded widgets and prevents local isolation.

## Typography

Vazirmatn remains the body/display compatibility stack because production already uses it. The theme falls back to Tahoma, Arial and sans-serif. No remote font call or unlicensed file is introduced in v0.2. Local font packaging and typographic visual regression remain staging work.

## Numerals

The theme does not automatically convert Latin digits to Persian digits. Automatic conversion could corrupt URLs, email addresses, telephone numbers, reference IDs, prices and plugin payloads. Content authors may use Persian numerals in prose; machine-readable values remain authored/source values and are isolated as needed.

## Integration boundary

Production Additional CSS forces enrolment page 91 and every descendant LTR, and the loaded Amelia root computes LTR. In staging, test the integration inside the RTL document without removing Amelia or applying a universal override. Any required LTR wrapper must be narrow, explicit and owned by the integration migration—not embedded as a page-ID theme rule.

## Validation cases

1. Persian heading/paragraph with punctuation and linked Latin product name.
2. Email, Australian phone number, Persian/Latin mixed reference and URL.
3. Ordered/unordered Persian lists and block quotes.
4. English legal section nested in a Persian page.
5. Gutenberg editor preview versus front-end output.
6. Screen-reader language announcement and keyboard focus order.
7. Rank Math Open Graph locale/schema review; theme semantics must not overwrite Rank Math ownership.
