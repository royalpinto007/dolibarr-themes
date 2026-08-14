import sys, json
sys.path.insert(0, ".")
from shared import screenshots as ss
from playwright.sync_api import sync_playwright

# Any button-like element whose background is neither transparent nor a light
# surface is "filled" and should be showing the configured colour.
JS = """() => {
  const want = getComputedStyle(document.documentElement).getPropertyValue('--c-btn-action').trim();
  const sel = 'input[type=submit], button[type=submit], .butAction, .butActionNew, a.butAction, ' +
              '.ts-settings-action-primary, .ts-command-submit-primary, .ts-record-primary, .ts-record-secondary, input.button';
  const bad = [];
  document.querySelectorAll(sel).forEach(e => {
    const r = e.getBoundingClientRect(); if (r.width < 5) return;
    const bg = getComputedStyle(e).backgroundColor;
    const m = bg.match(/\\d+/g); if (!m) return;
    const [rr,gg,bb] = m.map(Number);
    const light = rr>240 && gg>240 && bb>240;
    const transparent = bg.startsWith('rgba(0, 0, 0, 0');
    if (light || transparent) return;
    if (bg.replace(/\\s/g,'') === want.replace(/\\s/g,'')) return;
    bad.push({cls:(e.className||'').toString().slice(0,40) || e.name || e.tagName, bg});
  });
  return {want, bad: bad.slice(0,4)};
}"""

PAGES = ["/admin/ihm.php?mode=template","/admin/company.php","/societe/card.php?socid=2",
         "/societe/card.php?socid=2&action=edit","/product/card.php?id=1","/comm/propal/card.php?id=2",
         "/commande/card.php?id=2","/compta/facture/card.php?id=2","/societe/list.php",
         "/product/list.php","/ticket/card.php?action=create","/societe/card.php?action=create"]
with sync_playwright() as p:
    b = p.chromium.launch(args=["--no-sandbox"])
    pg = b.new_context(ignore_https_errors=True, viewport={"width":1600,"height":1100}).new_page()
    ss._login(pg)
    total_bad = 0
    for u in PAGES:
        try:
            pg.goto(ss.DOL_URL+u, wait_until="domcontentloaded", timeout=35000); pg.wait_for_timeout(1100)
            r = pg.evaluate(JS)
        except Exception as e:
            print("ERR", u, str(e)[:40]); continue
        if r["bad"]:
            total_bad += len(r["bad"])
            print("MISMATCH %-40s %s" % (u[:40], json.dumps(r["bad"])[:180]))
        else:
            print("OK       %-40s" % u[:40])
    print("want:", r["want"], "| total mismatches:", total_bad)
    b.close()
