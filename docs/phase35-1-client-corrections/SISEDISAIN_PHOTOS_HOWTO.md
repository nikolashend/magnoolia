# Kuidas asendada SISEDISAIN lehe musta taustaga fotosid

**Lühivastus: praegu ei saa neid administraatoriliideses vahetada.** Need ei ole meediateegi pildid, vaid genereeritakse lähtefailidest ehitusprotsessi käigus. Vahetamiseks on vaja arendajat — täpne kord allpool. Phase 36 ettepanek selle muutmiseks on kirjas kõige lõpus.

---

## Millised pildid need on

Iga kategooria juures on üks musta taustaga "tõendusleht":

| Lehel nähtav plokk | Avalik fail | Lähtefail |
|---|---|---|
| Elektri- ja nutiseadmed | `electrical-overview.webp` | `materials/phase31/pistikud display.jpg` |
| Sanitaartehnika | `sanitary-overview.webp` | `materials/phase31/sanitaartehnika.jpg` |
| Plaadid ja vannitoa viimistlus | `tiles-overview.webp` | `materials/phase31/seina voi porandaplaat.jpg` |
| Siseviimistlus | `finish-overview.webp` | `materials/phase31/sisedisain.jpg` |
| Lisavalikud lisatasu eest | `paid-options-overview.webp` | `materials/phase31/valikud lisatasu eest.jpg` |

Lisaks kaks suurt interjöörifotot lehe ülaosas:

| Plokk | Avalik fail | Lähtefail |
|---|---|---|
| Päevane vaade | `interior-living-day.webp` | `materials/phase31/valmis tuba.jpg` |
| Õhtune vaade | `interior-living-evening.webp` | `materials/phase31/valmis tuba2.jpg` |

Avalikud failid asuvad kaustas `app/public/assets/magnoolia/siseviimistlus/`.

---

## Kuidas pilti vahetada (arendaja samm-sammult)

**1. Asenda lähtefail.** Pane uus pilt kausta `materials/phase31/` **täpselt sama nimega** nagu tabelis. Nimi peab jääma samaks — teegi seob pildid nime järgi.

```
materials/phase31/pistikud display.jpg   ← uus pilt, sama nimi
```

Soovitus: sama kuvasuhe kui vanal (praegu 1635 × 922), muidu plokk muudab kõrgust.

**2. Genereeri veebiversioonid.**

```bash
cd app
npm run magnoolia:generate:interior
```

Käsk teeb igast lähtefailist kolm WebP-varianti (täissuurus + 1400 px + 768 px) ja kirjutab raporti `optimization.md`. See on idempotentne — muutmata pildid jäävad samaks.

**3. Vaata üle ja avalda.**

```bash
git add -A && git commit -m "Sisedisain: uued viimistluse fotod"
git push
```

Serveris:

```bash
git pull
php artisan view:clear
```

`config:cache` **ei ole vaja** — pildid ei sõltu konfiguratsiooni vahemälust.

> **NB:** need pildid ei käi läbi administraatori "Publish Website Changes" voo. Nad lähevad live'i kohe pärast `git pull`-i, sest tegemist on failidega, mitte andmebaasi sisuga.

---

## Kui pilt peab olema teise nimega

Failinimed on kirjas kahes kohas:

- `app/config/magnoolia_interiors.php` — iga kategooria `overview` väli
- `app/scripts/magnoolia-generate-interior-assets.mjs` — `JOBS` massiiv (lähtefail → väljundfail)

Mõlemad peavad kokku langema. Lihtsam on hoida vanad nimed.

---

## Miks seda administraatoris teha ei saa

Administraatori sisuredaktor katab 34 kindlaksmääratud tekstiplokki — pealkirjad, sissejuhatused, jalus. Piltide sidumine lehe plokkidega ei ole selles nimekirjas: iga pilt on koodis failinime järgi, mitte viide meediateegi kirjele.

**Phase 36 ettepanek — "Sisedisain section image editor":** siduda iga plokk meediateegi kirjega, nii et pildi vahetus käiks üles laadides ja plokile määrates, ilma arendajata ja koos tavapärase eelvaate/avaldamise vooga. Sama lahendus kataks ka avalehe kaardipildid, arhitektuuri renderdused ja alamlehtede päisefotod — need kannatavad täpselt sama piirangu all.
