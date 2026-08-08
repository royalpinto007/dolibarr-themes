# Verification

The theme was checked with a headless-Chrome harness rather than by eye alone. The page
list is generated from the **live menu tree** — hand-picking pages misses exactly the
screens where defects hide.

## What is checked, per page per viewport

| Check | Looks for |
|---|---|
| `PHP` / `DBERR` | notices, warnings, fatals, SQL errors in rendered output |
| `HSCROLL` | document wider than the viewport |
| `OVERLAP` | interactive elements covering each other |
| `TINY_TARGET` | icon-sized controls under 24px |
| `INPUT_OVERFLOW` | a field wider than the cell holding it |
| `CONTRAST` | WCAG 2.1 AA, with alpha compositing |
| `UNDER_CHROME` | content behind the fixed bar |
| `BROKEN_IMG` / `UNSTYLED_BTN` | failed images, native-looking buttons |

Viewports: desktop 1600×1000, tablet 900×1000, mobile 390×844 (via
`Emulation.setDeviceMetricsOverride` — headless Chrome silently refuses window widths
below ~500px, so `set_window_size(390, …)` actually tests 500px).

## Checks must be tested before they are trusted

Every check is validated against planted defects before a run is believed:

- a **real** unclipped overlap — must be caught
- the **same** overlap inside `overflow: hidden` — must be ignored
- `#eee` text on white — must be caught
- a 15% `color-mix` tint with dark text — must **pass**

This is not ceremony. Four separate checker bugs produced confident, wrong output during
development:

1. **Variant never switched.** `?theme=x` swaps the stylesheet but not the shell, so
   five "per-theme" passes re-measured one theme five times and reported all five clean.
2. **Rects without clipping.** An element scrolled out of an `overflow:auto` ancestor
   still returns its off-screen rect. Comparing raw rects counted every tab scrolled out
   of a nav strip as colliding — 350 false positives on one theme.
3. **Dropped alpha.** `color-mix()` computes to `color(srgb r g b / a)`; a parser that
   ignored `/ a` scored a 15% tint as an opaque fill and reported a badge at 1.6:1 that
   renders at 7.1:1.
4. **Scope mismatch.** A fix verified with `td > input` reported zero overflows while
   the audit — checking every `input` against its parent — still found five per page.
   The fix never reached fields wrapped in a `span`.

The rule that caught all four: **when a check reports something suspiciously clean or
suspiciously alarming, verify the check before acting on it.**

## Polish detector

A second, narrower probe looks for the defects a structural audit cannot see: adjacent
actions with no gap between them, a leading icon off its control's centre, field start
positions drifting within one form, uneven row and header heights, unstyled empty
states, action-row buttons off a shared baseline.

It reports box geometry, so read its output with two limits in mind: two padded links
whose boxes touch still *look* separated, and a page title is supposed to be a
different height from a column header.
