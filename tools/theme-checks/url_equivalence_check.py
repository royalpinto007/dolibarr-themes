import sys, json
sys.path.insert(0, ".")
from shared import screenshots as ss
from playwright.sync_api import sync_playwright
# Each pair serves the same page by two URL forms; the theme must compose both
# identically. Four bugs in this theme were guards matching only one form.
PAIRS=[("/","/index.php"),
       ("/societe/","/societe/index.php"),
       ("/product/","/product/index.php"),
       ("/compta/","/compta/index.php"),
       ("/comm/","/comm/index.php"),
       ("/projet/","/projet/index.php")]
JS="""() => [...document.body.classList].filter(c=>c.startsWith('ts-')).sort().join(',')"""
with sync_playwright() as p:
    b=p.chromium.launch(args=["--no-sandbox"])
    pg=b.new_context(ignore_https_errors=True,viewport={"width":1600,"height":1000}).new_page()
    ss._login(pg)
    bad=0
    for a,c in PAIRS:
        r=[]
        for u in (a,c):
            pg.goto(ss.DOL_URL+u,wait_until="domcontentloaded",timeout=40000); pg.wait_for_timeout(1000)
            r.append(pg.evaluate(JS))
        ok = r[0]==r[1]
        if not ok: bad+=1
        print("%-5s %-22s %-22s | %s || %s"%("OK" if ok else "MISMATCH",a,c,r[0][:52],r[1][:52]))
    print("MISMATCHES:",bad)
    b.close()
