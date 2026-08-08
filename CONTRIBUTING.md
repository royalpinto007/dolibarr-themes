# Contributing

Thanks for taking a look. This is a Dolibarr theme, so most contributions are CSS with
a little PHP — but the CSS has rules of its own here, and they are the difference
between a fix that holds and one that breaks another page.

## Getting set up

You need a working Dolibarr 24.x install. Symlink rather than copy, so edits are live:

```bash
ln -s "$PWD/htdocs/theme/command"            /path/to/dolibarr/htdocs/theme/command
ln -s "$PWD/htdocs/core/thriveshell"         /path/to/dolibarr/htdocs/core/thriveshell
ln -s "$PWD/htdocs/core/menus/standard/command_menu.php" /path/to/dolibarr/htdocs/core/menus/standard/
ln -s "$PWD/htdocs/core/menus/standard/command.lib.php"  /path/to/dolibarr/htdocs/core/menus/standard/
```

Then select **Command** in Home → Setup → Display, and set the menu manager:

```sql
UPDATE llx_const SET value='command_menu.php' WHERE name='MAIN_MENU_STANDARD';
```

Dolibarr compiles the stylesheet per request, so a browser reload picks up your edit.
There is no build step.

## House rules

**Fix causes, not pages.** A rule scoped to one screen will be wrong on the next one.
If a defect appears on the third-party card, it almost certainly appears on every card.
Put the fix in `core/thriveshell/components.inc.php`.

**Use the tokens.** Never hardcode a colour, radius, shadow or spacing value. If you
need one that does not exist, add it to `palette.inc.php` and say what role it plays.

`--c-faint` is for separators and disabled marks. It is **not** a text colour — it
measures about 2.6–2.9:1 on a surface. Use `--c-muted` for the lightest readable text.

**Emit `rem`, not `em`.** An `em` token compounds against whatever parent it lands in.

**Watch specificity.** Several long-lived bugs here were selectors losing to rules
elsewhere in this same file:

- `:not()` chains add up. A base rule with ten `:not()` clauses scores (0,10,1) and
  beats a `:focus` rule with five (0,6,1) — the focus state simply never applied.
- `:first-child` counts *elements only*. A glyph that follows a text value still
  matches it, so a "leading icon" rule will grab a trailing one.
- Overriding a library means matching each class-qualified variant at its own weight.
  `.treeview li.lastExpandable` (0,2,1) beats `ul.treeview li` (0,1,2).
- `[class*="width25"]` also matches `minwidth250`. Use `[class~="..."]` for whole-token
  matching.

## Before you open a PR

Check the change on **more than the page you fixed**. The pattern you touched almost
certainly appears elsewhere — a two-column form and a four-column form behave
differently, and mobile stacks the rows.

At minimum:

- desktop **and** one narrow width (the mobile breakpoint is 767px)
- a list, a card, an edit form and a settings form
- the interaction states a screenshot misses: focus, hover, an open select2, an open
  date picker, the account dropdown

If you change anything colour-related, confirm text still meets 4.5:1 (3:1 for large).
When measuring, composite alpha — `color-mix()` resolves to `color(srgb r g b / a)`,
and a parser that drops the alpha scores a 15% tint as an opaque fill.

## Commit messages

Say what changed and **why it was wrong**. "fix alignment" ages badly; "field width was
100% of the cell, leaving no room for the trailing info icon, so it wrapped" is still
useful in a year.

## Reporting a bug

Use the issue templates. A screenshot plus the page URL and viewport width resolves
most reports immediately.
