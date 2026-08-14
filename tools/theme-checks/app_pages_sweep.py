import sys, json
sys.path.insert(0, ".")
from shared import screenshots as ss
from playwright.sync_api import sync_playwright
URLS=[
 "/index.php","/societe/list.php","/societe/card.php?socid=2","/comm/card.php?socid=2",
 "/societe/contact.php?socid=2","/contact/list.php","/contact/card.php?id=1",
 "/product/list.php","/product/card.php?id=1","/product/index.php",
 "/projet/list.php","/projet/card.php?id=1","/projet/index.php",
 "/comm/propal/list.php","/comm/propal/card.php?id=2","/comm/index.php",
 "/commande/list.php","/commande/card.php?id=2",
 "/compta/facture/list.php","/compta/facture/card.php?id=2","/compta/index.php",
 "/fourn/facture/list.php","/fourn/commande/list.php","/fourn/index.php",
 "/ticket/list.php","/ticket/index.php","/user/list.php","/user/card.php?id=1",
 "/user/group/list.php","/adherents/list.php","/adherents/index.php",
 "/compta/bank/list.php","/compta/bank/index.php","/don/list.php",
 "/expensereport/list.php","/holiday/list.php","/hrm/index.php",
 "/contrat/list.php","/fichinter/list.php","/expedition/list.php",
 "/product/stock/list.php","/comm/action/index.php","/comm/action/list.php",
 "/categories/index.php?type=product","/admin/index.php","/support/index.php",
]
JS="""() => ({
  ovf: document.documentElement.scrollWidth > document.documentElement.clientWidth,
  sw: document.documentElement.scrollWidth, cw: document.documentElement.clientWidth,
  modern: !!document.querySelector('.ts-list-composition, .ts-settings-card, .ts-thirdparty-record-shell, .ts-pagehead, .ts-cust-stack'),
  legacyTable: !!document.querySelector('table.liste:not(.ts-latest-table)') && !document.querySelector('.ts-list-composition')
})"""
res=[]
with sync_playwright() as p:
    b=p.chromium.launch(args=["--no-sandbox"])
    for w in [1600, 900]:
        pg=b.new_context(ignore_https_errors=True,viewport={"width":w,"height":1000}).new_page()
        errs={}; cur={"n":""}
        pg.on("pageerror",lambda e:errs.setdefault(cur["n"],[]).append(str(e)[:80]))
        ss._login(pg)
        for u in URLS:
            cur["n"]=u
            try:
                r=pg.goto(ss.DOL_URL+u,wait_until="domcontentloaded",timeout=30000); pg.wait_for_timeout(500)
                d=pg.evaluate(JS); d.update({"u":u,"w":w,"st":r.status if r else 0,"e":errs.get(u,[])})
                res.append(d)
            except Exception as ex:
                res.append({"u":u,"w":w,"st":-1,"e":[str(ex)[:60]]})
        pg.close()
    b.close()
json.dump(res,open('/tmp/wide.json','w'))
bad=[r for r in res if r.get("e")]
ovf=[r for r in res if r.get("ovf")]
leg=[r for r in res if r.get("legacyTable")]
nom=[r for r in res if not r.get("modern") and r.get("st")==200]
print("TOTAL checks",len(res))
print("JS ERRORS  :", json.dumps([(r["u"],r["w"],r["e"][:1]) for r in bad][:12], indent=0)[:900])
print("OVERFLOW   :", [(r["u"],r["w"],r["sw"],r["cw"]) for r in ovf][:12])
print("LEGACY LIST:", sorted(set(r["u"] for r in leg))[:14])
print("NO MODERN  :", sorted(set(r["u"] for r in nom))[:14])
print("non200     :", [(r["u"],r["st"]) for r in res if r.get("st") not in (200,)][:10])
