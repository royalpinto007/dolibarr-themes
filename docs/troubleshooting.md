# Troubleshooting

## The theme applies but the navigation looks like stock Dolibarr

The stylesheet and the shell come from two different places. Selecting a theme in
**Setup → Display** changes the stylesheet; the navigation DOM comes from the
`MenuManager` named in `MAIN_MENU_STANDARD`.

```sql
-- command
UPDATE llx_const SET value='command_menu.php'     WHERE name='MAIN_MENU_STANDARD';
-- workbench, aurora, editorial, dense
UPDATE llx_const SET value='thriveshell_menu.php' WHERE name='MAIN_MENU_STANDARD';
```

## `?theme=command` in the URL does not switch the shell

It never will. The manager is chosen server-side from the **saved** theme before any
query parameter is considered, so `?theme=` previews CSS only. To compare two themes
properly, save each one.

This matters when auditing: a per-theme sweep driven by `?theme=` measures one shell
repeatedly and reports every theme identical.

## A change to the CSS does not appear

Dolibarr compiles `style.css.php` per request, so there is no build step — but
browsers cache it aggressively. Hard-reload (`Ctrl`/`⌘ + Shift + R`).

If it still does not appear, the rule is probably losing on specificity rather than
missing. See the traps in [CONTRIBUTING.md](../CONTRIBUTING.md); `:not()` chains and
`min-width` beating `max-width` account for most of it.

## Custom-module menu entries 404

Modules under `htdocs/custom` emit URLs without the `/custom` prefix. They must be
resolved with `dol_buildpath($path, 1)`, and the query string has to be split off
first — `dol_buildpath` takes a path, not a path plus query.

## Dolibarr installed at the web root breaks menu links

`DOL_URL_ROOT` is `''` there, and `strpos($url, '')` returns `0` — so an
"is this already absolute?" guard written as `strpos($url, DOL_URL_ROOT) === 0`
is true for every string. Guard with `DOL_URL_ROOT !== '' && …`.

## Content sits underneath the rail (workbench, editorial, aurora)

A few Dolibarr list pages render their table as a direct child of `<body>` rather
than inside `#id-right`. With a top-bar theme that is harmless; with side chrome the
first column becomes unreachable. Each affected theme offsets body-level tables by
its own chrome width.

## A dark theme shows a white block

Some bundled plugins hardcode a background — `jquery.treeview.css` sets
`background-color: white` on its list. Those need explicit neutralising; the shared
layer does this for the treeview.
