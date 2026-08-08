# Dolibarr version compatibility

Verified on **22.0.4** and **24.0.0-beta**.

## How this was established

The themes were developed against 24.0.0-beta. Supporting 22.0.4 was expected to be a
large change; it was not, and the reason is worth recording.

### 1. The theme surface is identical

| Dependency | 22.0.4 | 24.0.0-beta |
|---|---|---|
| `MenuManager::showmenu($mode, $moredata)` | same | same |
| `print_left_eldy_menu(...)` | same signature | same signature |
| Theme variables in `theme_vars.inc.php` | 66 | 66, same names |

A theme that emits its own shell depends on exactly these three things, and none of
them moved between the two releases.

### 2. The CSS class surface barely moved

`eldy` defines 1260 classes in 22.0.4 and 1330 in 24.0.0.

- **3 classes exist in 22.0.4 but not 24** — `classfortooltiponclick`,
  `fa-question-circle`, `linputsearch`. All three are now styled.
- **14 classes are styled here but absent from 22.0.4** — `box_impair`,
  `cal_today_am`, `info-box-createlink` and similar. Those rules are simply inert on
  22.x; an unmatched selector costs nothing.

### 3. One gap only a running install could reveal

22.0.4 requests `theme/<name>/manifest.json.php` on every page — a PWA manifest that
24.x no longer ships in `eldy`. A class-and-variable diff cannot see a **missing file**,
so this only surfaced as a 404 in the server log of a real install. All five themes now
ship one.

### 4. It was verified by running, not by diffing

A static diff is evidence, not proof. A full 22.0.4 install was stood up — its own
schema (`d22_` prefix), admin user and module set — and the audit was run against it
for every theme:

```
command    0 issues / 36 pages
workbench  0 issues / 36 pages
aurora     0 issues / 36 pages
editorial  0 issues / 36 pages
dense      0 issues / 36 pages
```

No PHP notices, no layout overflow, no contrast failures, and every shell rendered
with its navigation intact.

## Adding another version

The check that matters is the three items in section 1. If `showmenu()`'s signature,
`print_left_eldy_menu()`'s parameters and the theme-variable set are unchanged, the
shell will load; everything after that is CSS class coverage, which degrades gracefully
in both directions.
