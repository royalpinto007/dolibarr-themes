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

## A page renders the menu and then stops

The top bar is there, the content is not, and the HTML ends just after `</header>`.
The status is **200**, so nothing looks wrong to a monitor, and with `display_errors`
off the response carries no error text.

This is not a theme bug — stock Dolibarr does it too — but the themes make it easy to
misread as broken chrome, so it is recorded here.

Dolibarr takes the timezone from the browser at login (`tz_string`), stores it in the
session **without validating it**, and later passes it to `new DateTimeZone()` in
`dol_print_date()`. If the PHP build's tzdata does not know the identifier it throws,
after the header has already been flushed.

Chrome still reports **legacy** zone names: it sends `Asia/Calcutta` even when the OS
is set to `Asia/Kolkata`, and likewise `Asia/Katmandu`, `Europe/Kiev`. Current tzdata
ships only the modern spellings. So the page breaks for users in those regions and
looks perfectly healthy to everyone else.

Confirm it without reading a log — log in once per zone and compare response sizes:

```
Asia/Calcutta   18792 bytes  truncated     Asia/Kolkata    168332 bytes  ok
Asia/Katmandu   18792 bytes  truncated     Asia/Kathmandu  168334 bytes  ok
Europe/Kiev     18792 bytes  truncated     Europe/Kyiv     168331 bytes  ok
```

Reproducing with `curl` **hides** the bug: hand-writing `tz_string` you naturally type
the modern name and the page renders. Only a real browser sends the legacy alias.

Patch both places. In `htdocs/main.inc.php`, where `$dol_tz_string` is built at login:

```php
if (!empty($dol_tz_string)) {
    $aliases = array('Asia/Calcutta' => 'Asia/Kolkata', 'Asia/Katmandu' => 'Asia/Kathmandu',
        'Asia/Rangoon' => 'Asia/Yangon', 'Asia/Saigon' => 'Asia/Ho_Chi_Minh',
        'America/Buenos_Aires' => 'America/Argentina/Buenos_Aires', 'Europe/Kiev' => 'Europe/Kyiv');
    if (isset($aliases[$dol_tz_string])) { $dol_tz_string = $aliases[$dol_tz_string]; }
    try { new DateTimeZone($dol_tz_string); } catch (Exception $e) { $dol_tz_string = ''; }
}
```

And again at the point of use in `htdocs/core/lib/functions.lib.php`, falling back to
`'UTC'` and rewriting `$_SESSION['dol_tz_string']`. The second guard is not optional:
without it, sessions established before the patch keep the bad value and stay broken
until the user logs out.
