import sys, json
sys.path.insert(0, ".")
from shared import screenshots as ss
from playwright.sync_api import sync_playwright
pages=[l.strip() for l in open("tools/theme-checks/admin_pages.txt") if l.strip()]
JS="""() => ({
  page: document.body.classList.contains('ts-settings-page'),
  skin: document.body.classList.contains('ts-display-settings'),
  cards: document.querySelectorAll('.ts-settings-card').length,
  rows: document.querySelectorAll('.ts-setting').length,
  ctl: document.querySelectorAll('.ts-setting-control input:not([type=hidden]), .ts-setting-control select, .ts-setting-control textarea').length,
  formCtl: document.querySelectorAll('form input:not([type=hidden]), form select, form textarea').length,
  orphan: [...document.querySelectorAll('.ts-setting-control input:not([type=hidden]), .ts-setting-control select, .ts-setting-control textarea')].filter(e=>!e.closest('form')).length,
  ovf: document.documentElement.scrollWidth > document.documentElement.clientWidth
})"""
res=[]
with sync_playwright() as p:
    b=p.chromium.launch(args=["--no-sandbox"])
    pg=b.new_context(ignore_https_errors=True,viewport={"width":1600,"height":1000}).new_page()
    errs={}; cur={"n":""}
    pg.on("pageerror",lambda e:errs.setdefault(cur["n"],[]).append(str(e)[:70]))
    ss._login(pg)
    for f in pages:
        cur["n"]=f
        try:
            r=pg.goto(ss.DOL_URL+"/admin/"+f,wait_until="domcontentloaded",timeout=30000)
            pg.wait_for_timeout(420)
            d=pg.evaluate(JS); d["status"]=r.status if r else 0; d["file"]=f
            d["errs"]=errs.get(f,[])
            res.append(d)
        except Exception as e:
            res.append({"file":f,"status":-1,"err":str(e)[:70],"errs":errs.get(f,[])})
    b.close()
json.dump(res,open('/tmp/sweep_result.json','w'))
comp=[r for r in res if r.get("page") or r.get("skin")]
noop=[r for r in res if not r.get("page") and not r.get("skin")]
bad=[r for r in res if r.get("errs") or r.get("ovf") or r.get("orphan") or (r.get("status") not in (200,-1) and r.get("status") is not None)]
print("TOTAL",len(res),"COMPOSED",len(comp),"NOOP",len(noop))
print("with JS errors:",[r["file"] for r in res if r.get("errs")][:20])
print("with overflow :",[r["file"] for r in res if r.get("ovf")][:20])
print("orphan fields :",[r["file"] for r in res if r.get("orphan")][:20])
print("non-200       :",[(r["file"],r.get("status")) for r in res if r.get("status") not in (200,)][:20])
print("noop WITH form controls (possible misses):",
      [(r["file"],r.get("formCtl")) for r in noop if r.get("formCtl",0)>2][:30])
