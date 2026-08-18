# COMMAND route QA ledger

This ledger records routes actually visually and interactively audited. Route discovery alone does not count as QA coverage.

## 2026-08-18 full sweep

- 300/300 discovered routes were opened through the authenticated browser at 1680px.
- Each route received a full-page screenshot, navigation result, console/page-error capture, document-overflow check, visible-control geometry check, and DOM-layout fingerprint.
- A second interaction sweep exercised 192 visible Select2 controls, 121 filter panels, 112 hover previews, and 68 tab strips. Compact Select2 controls were opened and closed with Escape; filter panels were opened then dismissed outside; and representative record action menus were additionally verified for open, outside-click, and Escape dismissal.
- 0 document-level horizontal-overflow failures and 0 uncaught page errors were found.
- One discovered custom-module route, `/custom/mobileshipment/index.php?idmenu=121&mainmenu=mobileshipment&leftmenu=`, returns HTTP 404. It is an unavailable module route rather than a COMMAND-rendering fault.
- 43 distinct representative layout families received a four-width check at 1680, 1280, 900, and 600px. The 600px and 1680px states were captured for visual review.

| Route / representative family | Page type | Inspected | Interactions tested | Issue found | Fix commit | After verified |
| --- | --- | --- | --- | --- | --- | --- |
| `/comm/action/index.php` | Agenda month calendar | Yes | Calendar links, compact disclosure, responsive shell | Individual event rows made busy day cells excessively tall; weekday labels ran together at 600px | `7696f07`, `b8c3fd9` | Yes, screenshots and all four widths |
| `/societe/list.php` and variants | List / filter / table | Yes | Select2, filters, table scroll | No document overflow; wide data tables remain inside their scroll surface | n/a | Yes |
| `/comm/propal/card.php` and `/commande/card.php` | Commerce record | Yes | record links, action menus, tabs | No new shell or overflow regression found | n/a | Yes |
| `/product/*`, `/projet/*`, `/compta/*` | Product, project, billing lists/forms | Yes | filters, Select2, forms | No document overflow or page error found | n/a | Yes |
| `/admin/*` | Settings / data grid | Yes | settings forms, Select2 | No document overflow; long system-report pages are intrinsically tall | n/a | Yes |
| `/adherents/*`, `/contact/*`, `/societe/*` | Entity lists, records, tabs, forms | Yes | tables, filters, entity actions | No document overflow or uncaught page error found | n/a | Yes |
| `/ticket/*`, `/expedition/*`, `/fichinter/*`, custom list routes | Secondary module layouts | Yes | list controls where present | No document overflow or uncaught page error found | n/a | Yes |
| `/product/stock/massstockmove.php?init=1` | Table-select form | Yes | Source warehouse Select2 open/close | A 200px Select2 renderer was inside a 96px table cell and overlapped Target warehouse | `74d9f36` | Yes: cell reserves its visible Select2 width, popup opens from the correct source trigger, no document overflow |

## Shared repair verified after the sweep

- `74d9f36` adds a narrow global safeguard for direct Select2 renderers in `td.nowraponall`: the table cell now reserves the renderer width plus its preceding icon slot. This fixes overlapping table selects without changing normal form sizing.
- The repaired stock-movement source selector was visually rechecked open and closed at desktop. It is independent from Target warehouse, its popover anchors to it, and the page remains free of document-level horizontal overflow.
| --- | --- | --- | --- | --- | --- | --- |
