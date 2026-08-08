# Verification record

What was actually run, and on what.

## Dolibarr 22.0.4

A real install — own schema (`d22_` prefix), admin user, 17 modules — audited per
theme across 36 pages at desktop width.

| Theme | Result |
|---|---|
| command | 0 issues |
| workbench | 0 issues |
| aurora | 0 issues |
| editorial | 0 issues |
| dense | 0 issues |

No PHP notices, no layout overflow, no contrast failures. Every shell rendered with
its navigation intact.

That install holds little business data, so list and card pages were exercised
largely as empty states; admin and setup pages were covered fully.

## Dolibarr 24.0.0-beta

The development instance, with real data. Audited across 169 pages at desktop,
tablet and emulated mobile, plus a polish scan for small-scale defects.

| Theme | Structural | Polish |
|---|---|---|
| command | 125 / 507 page-viewports | 30 / 169 pages |
| workbench | 120 / 507 | 35 / 169 |
| aurora | 114 / 338 | — |
| editorial | — | 33 / 169 |
| dense | — | see below |

Residual findings are dominated by two classes that were each checked by hand and
found to be measurement artefacts rather than defects:

- **`ROW_HEIGHT_SPREAD`** — driven by labels wrapping to two lines, which is content,
  not layout.
- **`FIELD_START_DRIFT`** — a form mixing two-column and four-column rows reports two
  start positions for what the detector treats as one column.

`TINY_TARGET` counts genuine sub-24px controls, mostly icon-only actions on the menu,
module and translation admin screens.

## What the checks are worth

Every check is validated against planted defects before a run is believed — a real
overlap it must catch, a clipped one it must ignore, unreadable text it must catch, a
translucent tint it must pass. This is not ceremony: seven separate checker bugs during
development produced confident, wrong output. They are listed in
[verification.md](verification.md).
