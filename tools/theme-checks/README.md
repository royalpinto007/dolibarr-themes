# Theme checks

Three checks that guard the `command` theme. Between them they caught two
regressions before they shipped during the work that produced them, which is
the argument for running them rather than reading them.

Run from the agent checkout, with the Playwright helper importable:

    python tools/theme-checks/admin_settings_sweep.py
    python tools/theme-checks/app_pages_sweep.py
    python tools/theme-checks/url_equivalence_check.py

## admin_settings_sweep.py

Loads all ~146 `/admin/*.php` screens and reports, for each: whether the shared
settings composition applied, how many cards and rows it produced, JS errors,
horizontal overflow, and **fields left outside their `<form>`**.

The last one is the important column. A composition that moves a control out of
its form leaves a setting that silently fails to save, and nothing about the
page looks wrong. A broadened selector did exactly that to five pages; the
`orphan fields` count is what caught it.

Baseline at the time of writing: **79 composed, 67 no-op, 0 orphans, 0 errors,
0 overflow.** A drop in *composed* means a page stopped being handled — that is
how `website.php` was caught after a filter change claimed its markup.

## app_pages_sweep.py

Loads ~46 application pages at 1600 and 900 and reports JS errors and
horizontal overflow.

Note the limitation: its `modern` / `legacy list` columns detect **marker
classes**, so a CSS-only improvement is invisible to it and a class name alone
can mark a page as legacy when it is fine. Treat those columns as hints; the
errors and overflow columns are the reliable signal.

## url_equivalence_check.py

Dolibarr serves the same screen under more than one URL — `/societe/` and
`/societe/index.php` are the same page. Guards keyed to one spelling leave the
other rendering unstyled. Four bugs of this shape appeared in a single session:
a record id read only from the query string (so every POST fell back to the
legacy layout), a list matched only on `table.liste`, a dashboard matched only
on `/index.php`, and module indexes matched only on `.../index.php`.

This loads both forms of each pair and compares the `ts-` body classes.
Expected output ends with `MISMATCHES: 0`.

## action_button_colour_check.py

Display > Skin and colours sets the action button colour. Seven separate rules
in this theme paint a filled button, so wiring the ones you happen to look at
leaves others on the old colour -- which is exactly what happened: the Save
button on the settings page and on edit/create forms stayed indigo after the
record buttons were fixed.

This walks every button-like element on a set of pages, computes its background,
and reports any filled button whose colour is not the configured one. Expected
output ends with .

Run it after touching any button rule. A new rule that hardcodes the accent
shows up here immediately instead of being found by eye months later.

## Two failure modes worth knowing

**Widening a guard needs a replacement boundary, not just removal of the old
one.** Both regressions here came from deleting a restriction without putting a
structural one in its place — `bodyforlist` and the form requirement are those
boundaries.

**A fixed dimension where a minimum belonged** accounted for eight separate
visual bugs: colliding tooltip labels, a reference truncated to two characters,
stacked status badges, a clipped year filter, mismatched date fields, and three
containers found by audit. When something clips or collides, look for a
hard-coded `width` before anything else.
