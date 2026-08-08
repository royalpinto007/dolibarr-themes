# Verification record

What was actually run, and on what.

## Dolibarr 22.0.4

A real install — own schema (`d22_` prefix), admin user, 15 modules activated through
Dolibarr's own module loader, seeded records — audited per theme across 36 pages.

| Theme | Result |
|---|---|
| command | 8 pages, all `TINY_TARGET` |
| workbench | 8 pages, all `TINY_TARGET` |
| aurora | 8 pages, all `TINY_TARGET` |
| editorial | 8 pages, all `TINY_TARGET` |
| dense | 2 pages — 2 `TINY_TARGET`, 1 `OVERLAP` |

No PHP notices, no contrast failures, no input overflow, no content behind chrome.

### An earlier run of this claimed 0 issues and was wrong

The first 22.0.4 audit reported a clean sweep for every theme. It was measuring
**truncated pages**. Three separate faults were stacked:

1. **A PHP fatal killed every page after the header.** Headless Chrome reports the
   legacy `Asia/Calcutta` timezone alias; Dolibarr stores it in the session and modern
   PHP's `DateTimeZone` rejects it. The nav rendered, then output stopped.
2. **Modules enabled by constant have no rights.** Setting `MAIN_MODULE_*` does not
   populate `rights_def`; the module loader has to run. Until it did, list pages had
   nothing to show.
3. **`ALLOW_THEME_JS` was unset**, so no theme JavaScript loaded at all.

"0 issues" meant "nothing to audit". The lesson is the same one that runs through
[verification.md](verification.md): a suspiciously clean result deserves the same
scepticism as a suspiciously alarming one.

Fixing all three surfaced real bugs the clean run had hidden — agenda event blocks and
CKEditor labels unreadable on the dark theme, and the account block sitting underneath
the rail.

## Dolibarr 24.0.0-beta

The development instance, with real data. Audited across 169 pages at desktop, tablet
and emulated mobile, plus a polish scan for small-scale defects.

| Theme | Structural | Polish |
|---|---|---|
| command | 125 / 507 page-viewports | 30 / 169 pages |
| workbench | 120 / 507 | 35 / 169 |
| aurora | 114 / 338 | — |
| editorial | — | 33 / 169 |
| dense | — | 44 / 169 |

Residual findings are dominated by two classes checked by hand and found to be
measurement artefacts rather than defects:

- **`ROW_HEIGHT_SPREAD`** — labels wrapping to two lines. Content, not layout.
- **`FIELD_START_DRIFT`** — a form mixing two-column and four-column rows reports two
  start positions for what the detector treats as one column.

`TINY_TARGET` counts genuine sub-24px controls, mostly icon-only actions on admin
screens. Enlarging the icon-action box cut the menu editor's count from 10 to 2.

## What the checks are worth

Every check is validated against planted defects before a run is believed — a real
overlap it must catch, a clipped one it must ignore, unreadable text it must catch, a
translucent tint it must pass. Seven checker bugs during development produced
confident, wrong output; they are listed in [verification.md](verification.md).
