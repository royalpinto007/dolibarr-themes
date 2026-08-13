# Theme checks

## url_equivalence_check.py

Dolibarr serves the same page under more than one URL -- `/societe/` and
`/societe/index.php` are the same screen. The command theme gates its
composition on `window.location.pathname`, so a guard written for one form
silently leaves the other rendering unstyled Dolibarr.

Four separate bugs of exactly this shape were found in one session:

- the events tab read its record id only from the query string, so every
  filtered view (a POST) fell back to the legacy layout;
- the agenda list matched only `table.liste`, missing the identical markup on
  `table.noborder`;
- the dashboard matched only `/index.php`, so entering Dolibarr at `/` gave
  the unstyled page;
- several module indexes matched only `.../index.php`, missing the directory
  form.

This check loads both forms of each pair and compares the `ts-` body classes.
Any mismatch means a guard is keyed to a URL spelling rather than to the
structure it needs.

Run it from the agent checkout with the Playwright helper on the path:

    python tools/theme-checks/url_equivalence_check.py

Expected output ends with `MISMATCHES: 0`.
