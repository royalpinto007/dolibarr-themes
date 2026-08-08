# Architecture

## The shell is emitted, not inherited

Dolibarr picks a **`MenuManager`** class server-side, from the `MAIN_MENU_STANDARD`
constant. That class decides the navigation DOM — `eldy_menu.php` produces the stock
top bar and left column. A theme that only ships CSS is restyling someone else's markup.

Command ships its own manager:

```
command_menu.php   class MenuManager { loadMenu(); showmenu($mode); }
command.lib.php    tree building, palette markup, URL resolution
```

`showmenu()` is called with `'top'`, `'left'`, `'topnb'` or `'jmobile'`. Command emits
a single `.cmd-bar` (brand, search trigger, tools) plus a `.cmd-nav` sidebar for the
tree, and returns nothing for the modes it does not use.

### Chaining

Managers chain so one install can host several themes:

```
command_menu.php  →  (theme is not 'command')  →  eldy_menu.php
```

The active theme is read from `$conf->theme`. This is why `?theme=x` previews a
stylesheet but not a shell — the manager already ran.

## URL resolution

Two traps, both fixed in `command_abs_url()`:

1. **`DOL_URL_ROOT` is `''`** when Dolibarr is installed at the web root. An
   "is this already absolute?" guard written as `strpos($url, DOL_URL_ROOT) === 0`
   is then true for *every* string, because `strpos` with an empty needle returns `0`.
   Guard with `DOL_URL_ROOT !== '' && …`.

2. **Modules under `htdocs/custom`** emit menu URLs without the `/custom` prefix.
   Dolibarr resolves them with `dol_buildpath($path, 1)`. Prepending `DOL_URL_ROOT`
   yields a 404 for every custom-module entry. Split the query string off first —
   `dol_buildpath` takes a path, not a path plus query.

## The component layer

`core/thriveshell/` is shared, not theme-specific. Every rule consumes the `--c-*`
custom properties (see [design-tokens.md](design-tokens.md)), so a new theme supplies
a palette and inherits the whole component set.

Load order matters, and is set in `command.inc.php`:

```
theme_vars  →  darkmode  →  utilities  →  [theme base]  →  palette
            →  components  →  navtree  →  select2
```

`darkmode` comes early so a saved dark preference redefines tokens before anything
consumes them; `select2` comes last because the library's own stylesheet is loaded
after the theme's and has to be overridden on source order.

## select2

Dolibarr converts most `<select>` elements to select2 at runtime. Styling native
selects therefore styles an element nobody sees. select2 also keeps the original
`<select>` in the DOM at 1×1 and parks it off-screen at roughly `-9999px` — worth
knowing when auditing tap targets or alignment, since it pollutes both.
