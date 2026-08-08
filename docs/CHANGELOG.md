# Changelog

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
