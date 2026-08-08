# Security policy

## Scope

Command is a presentation layer: CSS, a small amount of JavaScript, and PHP that emits
navigation markup. It does not authenticate, authorise, or touch business data.

The parts worth scrutiny are:

- `core/menus/standard/command.lib.php` — builds menu URLs and emits the palette. It
  handles user-influenced strings (menu labels, module-supplied paths).
- `theme/command/command.js` — client-side palette filtering and sidebar state.

Vulnerabilities in Dolibarr core belong upstream:
<https://github.com/Dolibarr/dolibarr/security>

## Supported versions

| Version | Supported |
|---|---|
| 1.0.x | yes |
| < 1.0 | no |

Tracks Dolibarr **24.x**. Older Dolibarr releases are not supported.

## Reporting

Please **do not open a public issue** for a security report.

Use GitHub's private reporting — *Security → Report a vulnerability* on this repo —
or email <royalpinto007@gmail.com>.

Useful to include: the affected file, the Dolibarr version, and a way to reproduce
(a URL shape or a menu entry that triggers it).

You can expect an acknowledgement within **7 days**, and an assessment within **30**.
If a fix is warranted it ships in a patch release and you are credited in the changelog
unless you prefer otherwise.

## Known considerations

Menu labels and module paths reach the DOM through the menu manager. They are escaped
on output, but a module that injects markup into a menu label is outside this theme's
control — that is a Dolibarr-level concern.
