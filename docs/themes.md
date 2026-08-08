# The five themes

All five share `core/thriveshell/` — the same tables, cards, forms, dialogs and
controls. What differs is the **shell** each emits and the palette it defines.

## command

Fixed top bar, collapsible sidebar tree, `Ctrl` / `⌘ + K` command palette.
Light, low-chroma, keyboard-first.

Its `MenuManager` is `command_menu.php`, which chains to `eldy_menu.php` if the
active theme is anything else.

The top bar is `position: fixed`, not `sticky` — sticky cannot escape its
containing block, and Dolibarr nests the bar inside
`<header id="id-top"><div class="tmenu">`.

## workbench

A 68px icon rail at the window edge plus a section panel. Dark rail, teal accent,
compact rows.

Two things fall out of putting chrome at the left edge that a top-bar theme never
meets: the account menu has to open **upward and to the right** (below the trigger
puts it off-screen), and pages that emit their table as a direct child of `<body>`
slide underneath the rail unless they are offset.

Its accent is `#0F766E`. The original `#0D9488` reads better as a hue but carries
white button text at only 3.74:1.

## aurora

Dark, violet accent, top bar over side groups.

Its accent is deliberately **light** (`#A78BFA`) because the surface is dark — which
inverts the usual button rule. Filled actions take dark ink (7.02:1), not white
(2.72:1). The same inversion applies to text on the dark chrome: `--c-faint`, which
is "not for text" on a light surface, is the readable end of the ramp here.

## editorial

Light, a wide left column, generous type and spacing. The most conventional of the
five, and the closest to a document.

## dense

Compact bar with a horizontal tab strip, tuned for rows per screen. Its tab strip
scrolls rather than wraps, which is worth knowing when auditing: a tab scrolled out
of view still reports its off-screen rect, so a naive overlap check reads the whole
strip as colliding with the toolbar behind it.

## Choosing one

| Want | Take |
|---|---|
| Keyboard-driven, calm | command |
| Maximum screen for data, dark chrome | workbench |
| Dark throughout | aurora |
| Readability over density | editorial |
| Most rows visible at once | dense |
