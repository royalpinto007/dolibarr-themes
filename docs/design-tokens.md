# Design tokens

Every component reads from one custom-property contract. A theme is a palette plus a
shell; the components never hardcode a colour.

## Neutral ramp

| Token | Role |
|---|---|
| `--c-ink` | primary text |
| `--c-ink-2` | secondary text, still fully readable |
| `--c-muted` | **lightest step that may carry text** |
| `--c-faint` | separators, disabled marks — *not* for words |
| `--c-hairline` `--c-border` `--c-border-strong` | rules and outlines, increasing weight |
| `--c-canvas` `--c-surface` `--c-sunken` | page, card, recessed |

The `--c-muted` / `--c-faint` boundary is load-bearing. `--c-faint` on a surface
measures roughly 2.6–2.9:1 — fine for a hairline, a WCAG failure for a label. Several
contrast bugs in this theme's history were `--c-faint` used as text.

## Accent

`--c-accent`, `--c-accent-hover`, `--c-accent-ink`, `--c-accent-soft`, `--c-accent-ring`

`-soft` and `-ring` are `color-mix(… , transparent)` tints. They compute to
`color(srgb r g b / a)` — **any contrast check must composite that alpha**, or a 15%
tint is scored as an opaque fill.

## Status

`--c-success` `--c-warning` `--c-danger` `--c-info`

Badges keep the pure hue for the tint and the dot, and mix the *text* toward ink:

```css
background: color-mix(in srgb, var(--c-success) 15%, transparent);
color:      color-mix(in srgb, var(--c-success) 52%, var(--c-ink));
```

## Spacing and shape

`--sp-1 … --sp-6`, `--r-sm --r --r-lg`, `--sh-sm --sh --sh-md --sh-lg`, `--t` (transition).

## Dolibarr's own contract

Dolibarr has documented theme variables of its own (`--colorbackbody`,
`--inputbordercolor`, `--colorbacktitle1`, …). `theme_vars.inc.php` re-points those at
the `--c-*` ramp, so core markup that references them stays consistent.

## Units

Tokens emitted into unknown contexts use **`rem`, never `em`**. An `em` token compounds
against whatever parent it lands in — the same `0.94em` rendered a record banner at
22px and made another string invisible inside a `font-size: 0` parent.
