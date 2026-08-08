# Changelog

## 1.0.3

### Fixed
- Account avatar rendered at 80px in the top bar on Dolibarr 22.x, overlapping the
  chrome. 24.x marks that image `.dropdown-user-image`, but 22.x reuses the same
  `.photouserphoto` classes as a card portrait, so the card's 80px placeholder rule
  won. Now scoped by where the image sits rather than what it is called, which holds
  on both versions.

### Docs
- Troubleshooting: a page that renders the menu and then stops. Dolibarr stores the
  browser's timezone unvalidated and later feeds it to `DateTimeZone`; Chrome still
  reports legacy names (`Asia/Calcutta`, `Asia/Katmandu`, `Europe/Kiev`), so the page
  fatals after the header at HTTP 200 for users in those regions only.

## 1.0.2

Corrects the Dolibarr 22.0.4 verification and fixes the bugs it was hiding.

### The 22.0.4 numbers in 1.0.1 were wrong
1.0.1 reported 0 issues across 36 pages for every theme. That run was auditing
truncated pages — a timezone fatal killed each one after the header, modules enabled
by `MAIN_MODULE_*` constant had no `rights_def` entries, and `ALLOW_THEME_JS` was
unset so no theme JavaScript loaded. "0 issues" meant "nothing to audit".
Real numbers: 8/36 for command, workbench, aurora and editorial; 2/36 for dense.

### Fixed
- Agenda events unreadable on aurora — Dolibarr paints event blocks `#F0F0F0`, so the
  themed link colour measured 1.6:1
- CKEditor labels invisible on aurora — the editor ships its own `#484848` labels and
  a black resizer
- Account block unreachable under the side chrome when theme JS does not load
- `theme/<name>/manifest.json.php` now shipped; 22.x requests it, 24.x does not

### Install
- `ALLOW_THEME_JS` documented as a required step — without it Dolibarr serves no
  `theme/<name>/<name>.js` at all

## 1.0.1

Polish and accessibility pass across all five themes.

### Accessibility
- workbench accent `#0D9488` measured 3.74:1 under white text; moved to `#0F766E`
  (5.47:1) in the same hue family
- aurora is dark with a deliberately light accent — the shared button rule painted
  white on it at 2.72:1; dark ink gives 7.02:1
- The treeview plugin hardcodes `background-color: white`, punching a white block into
  the dark themes and dragging link contrast to 2.9:1
- Icon-only row actions were a bare 13-17px glyph; their box now reaches 24px without
  changing the glyph

### Layout
- Company name wraps in editorial, given real width in dense, instead of truncating
- Dashboard widget "more" links were 12px of bare text; now proper pill targets
- dense needed its own picto gutter offset — it packs rows tighter, so the shared 22px
  offset sat 6px low on every picto row
- Body-level tables offset past side chrome in aurora, editorial and dense

## 1.0.0

Five themes, verified on Dolibarr 22.0.4 and 24.0.0-beta.

### Shells
- Custom `MenuManager` per theme, replacing Dolibarr's inherited navigation DOM
- command: fixed top bar, collapsible sidebar, `Ctrl/⌘ K` palette
- workbench: 68px icon rail plus section panel
- aurora / editorial / dense: side groups, wide column, compact tab strip

### Design system
- `--c-*` token contract shared by a theme-agnostic component layer
- Dark mode as a token override rather than a second stylesheet

### Accessibility
- WCAG 2.1 AA text contrast on all five themes, alpha composited
- Visible focus border **and** ring on every control
- Tap targets raised on list checkboxes, tree carets and row actions

### Fixes worth recording
- `DOL_URL_ROOT === ''` made an absolute-URL guard match every string
- Custom-module menu URLs need `dol_buildpath($path, 1)`, path without query
- `input[type="text"]` matches nothing — Dolibarr omits `type`
- `:first-child` counts elements only — a glyph *after* a value still matches
- `min-width` beats `max-width`: a 190px select2 floor overflowed narrow cells
- A fixed left rail needs body-level tables offset, or their first column is
  unreachable — invisible in a top-bar theme
- Auto-layout tables let one long cell stretch a whole column
