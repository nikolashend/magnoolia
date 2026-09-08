# MAGNOOLIA — GSC / GOOGLE INDEXATION FINAL QA

Дата проверки: 2026-09-08
Объём: только техническое SEO / GSC. Дизайн, тексты, структура страниц, админка и 21 правка Indrek не затрагивались.

---

## 1. Final status

**`PENDING_MAGNOOLIA_GSC_WAITING_FOR_GOOGLE`**

Техническая часть на стороне сайта проходит проверку полностью — с двумя оговорками, которые обе требуют действия вне моего доступа:

1. Исправление P2 (шесть alias-адресов) и добавление `/arendajast` в sitemap **сделаны в коде, но ещё не выкачены на прод**. До `git pull` эти шесть адресов на проде продолжают отдавать 404.
2. Пункты P7 и P9 (отправка sitemap, URL Inspection, отчёт Pages) выполняются в интерфейсе Search Console, доступа к которому у меня нет.

`PASS_MAGNOOLIA_GSC_QA_10_READY` можно ставить после выката и выполнения действий в GSC. `FAIL` не ставится: ни одного из перечисленных в ТЗ условий отказа не обнаружено.

---

## 2. Executive summary

Проверено 107 адресов из живого sitemap плюс домены, старые хосты, robots и разметка.

**Что уже в порядке:**

- `https://magnoolia.ee/` — финальный production-домен, 200 OK.
- `http → https` и `www → non-www` — 301, цепочки 1–2 перехода.
- Старый шаблон `estlanda.ee/magnoolia` и хост `magnoolia.estlanda.ee` больше не конкурируют: 301 на соответствующие страницы с сохранением пути.
- Sitemap: 107 адресов, все на `https://magnoolia.ee`, валидный XML, без alias / staging / admin.
- **Все 107 адресов из sitemap: 200 OK, canonical совпадает, `noindex` отсутствует, ровно один H1, есть title, description, hreflang и JSON-LD, нет утечек старых доменов.**
- robots.txt корректен.

**Что исправлено в этой работе (ждёт выката):**

- Шесть alias-адресов, отдававших 404, переведены на 301.
- `/arendajast` добавлен в sitemap (ET/RU/EN) — страница живая, но в карте её не было.

**Что найдено и осознанно не исправлено:**

- На пяти страницах разметка `FAQPage` содержит 23 вопроса, которых нет в видимом тексте. Подробности и обоснование — раздел 12.

---

## 3. Domain status

| Проверка | Ожидание | Факт | Статус |
|---|---|---|---|
| `https://magnoolia.ee/` | 200 OK | 200 | ✅ |
| Финальный редирект на `magnoolia.estlanda.ee` | отсутствует | отсутствует | ✅ |
| Финальный редирект на `estlanda.ee/magnoolia` | отсутствует | отсутствует | ✅ |
| Длина цепочки | 1–2 шага | 1–2 | ✅ |
| Сертификат `magnoolia.ee` | покрывает apex + www | `DNS:magnoolia.ee, DNS:www.magnoolia.ee` | ✅ |

---

## 4. Redirect table

| Source URL | Expected | Actual | Status |
|---|---|---|---|
| `http://magnoolia.ee/` | 301 → https | `301 → https://magnoolia.ee/` | ✅ |
| `https://www.magnoolia.ee/` | 301 → non-www | `301 → https://magnoolia.ee/` | ✅ |
| `http://www.magnoolia.ee/` | → https non-www | `301 → https://www.magnoolia.ee/ → 301 → https://magnoolia.ee/` (2 шага) | ✅ |
| `https://estlanda.ee/magnoolia/` | 301 → magnoolia.ee | `301 → https://magnoolia.ee/` | ✅ |
| `http://estlanda.ee/magnoolia/` | 301 | `301 → https://magnoolia.ee/` | ✅ |
| `https://estlanda.ee/magnoolia/kodud-ja-hinnad` | 301 с сохранением пути | `301 → https://magnoolia.ee/kodud-ja-hinnad` | ✅ |
| `https://magnoolia.estlanda.ee/` | 301 → magnoolia.ee | `301 → https://magnoolia.ee/` | ✅ |
| `https://magnoolia.estlanda.ee/kodud-ja-hinnad` | 301 с сохранением пути | `301 → https://magnoolia.ee/kodud-ja-hinnad` | ✅ |
| `https://magnoolia.estlanda.ee/asukoht` | 301 | `301 → https://magnoolia.ee/asukoht` | ✅ |
| `https://magnoolia.estlanda.ee/ru` | 301 | `301 → https://magnoolia.ee/ru` | ✅ |
| `https://magnoolia.ee/asendiplaan` | 301 | `301 → /kodud-ja-hinnad#mg-masterplan` | ✅ |
| `/arhitektuur` | 301 → `/arhitektuur-ja-valisdisain` | **404 на проде**, 301 в коде | ⏳ ждёт выката |
| `/ru/arhitektuur` | 301 | **404 на проде**, 301 в коде | ⏳ |
| `/en/arhitektuur` | 301 | **404 на проде**, 301 в коде | ⏳ |
| `/arendaja` | 301 → `/arendajast` | **404 на проде**, 301 в коде | ⏳ |
| `/ru/arendaja` | 301 | **404 на проде**, 301 в коде | ⏳ |
| `/en/arendaja` | 301 | **404 на проде**, 301 в коде | ⏳ |

Alias-адреса реализованы через `Route::permanentRedirect` — 301, серверный, не JavaScript. Canonical целевых страниц не менялся, дублирующих страниц не создано, в sitemap alias не добавлены.

---

## 5. Sitemap proof

Источник: `https://magnoolia.ee/sitemap.xml`

| Проверка | Результат |
|---|---|
| HTTP | 200, `Content-Type: application/xml` |
| XML | well-formed (разобран парсером) |
| Количество `<loc>` | 107 (после выката — 110) |
| Хосты | только `https://magnoolia.ee` (107 из 107) |
| `magnoolia.estlanda.ee` | 0 |
| `estlanda.ee/magnoolia` | 0 |
| `localhost` / `127.0.0.1` | 0 |
| `/admin` | 0 |
| alias `/arhitektuur`, `/arendaja` | 0 |
| redirect-адреса внутри sitemap | 0 (`/asendiplaan` намеренно исключён) |
| `lastmod` | 2026-09-08, присутствует у всех 107 |

**Состав:** 3 главные (ET/RU/EN) · 38 страниц домов · 14 SEO-посадочных · остальное — разделы сайта в трёх языках.

**Отсутствовало и добавлено:** `/arendajast`, `/ru/arendajast`, `/en/arendajast` — страницы отдают 200, но в карте их не было. Добавлены с приоритетом 0.5.

---

## 6. Sitemap crawl result

Обойдены все 107 адресов, по каждому снято девять признаков.

| Метрика | Значение |
|---|---|
| Всего адресов | 107 |
| HTTP 200 | **107** |
| HTTP 301 | 0 |
| HTTP 404 | 0 |
| HTTP 5xx | 0 |
| `noindex` | **0** |
| canonical не совпадает с адресом | **0** |
| без hreflang | 0 |
| H1 ≠ 1 | 0 |
| без JSON-LD | 0 |
| без title | 0 |
| без meta description | 0 |
| утечка старого домена / localhost | **0** |
| невыведенные языковые ключи | 0 (проверено на 5 страницах) |
| hreflang-цели, отдающие не 200 | 0 (12 уникальных целей) |

---

## 7. Key pages SEO proof

| URL | HTTP | Canonical | Noindex | Hreflang | H1 | Schema | OG url | Result |
|---|---|---|---|---|---|---|---|---|
| `/` | 200 | ✅ self | нет | 4 | 1 | 1 блок | ✅ | PASS |
| `/kodud-ja-hinnad` | 200 | ✅ self | нет | 4 | 1 | 3 блока | ✅ | PASS |
| `/asukoht` | 200 | ✅ self | нет | 4 | 1 | 3 блока | ✅ | PASS* |
| `/galerii` | 200 | ✅ self | нет | 4 | 1 | 3 блока | ✅ | PASS |
| `/sisedisain` | 200 | ✅ self | нет | 4 | 1 | 3 блока | ✅ | PASS* |
| `/ehitusinfo` | 200 | ✅ self | нет | 4 | 1 | 3 блока | ✅ | PASS* |
| `/arhitektuur-ja-valisdisain` | 200 | ✅ self | нет | 4 | 1 | 3 блока | ✅ | PASS |
| `/arendajast` | 200 | ✅ self | нет | 4 | 1 | 1 блок | ✅ | PASS |
| `/kontakt` | 200 | ✅ self | нет | 4 | 1 | 3 блока | ✅ | PASS |
| `/kkk` | 200 | ✅ self | нет | 4 | 1 | 3 блока | ✅ | PASS* |

`*` — см. раздел 12 (невидимые вопросы в FAQ-разметке). На остальные критерии не влияет.

Все title уникальны (10 из 10). Описания: 9 уникальных из 10. Весь JSON-LD на всех проверенных страницах разбирается парсером без ошибок. Ссылок на `magnoolia.estlanda.ee`, `estlanda.ee/magnoolia` и `localhost` — нет. `/asendiplaan` в таблицу не включён: это 301, а не страница.

---

## 8. GSC inspection status

**Не выполнено — нет доступа к Search Console.** Заполняется владельцем ресурса.

| URL | Google status | Action taken | Result |
|---|---|---|---|
| `/` | | | |
| `/kodud-ja-hinnad` | | | |
| `/asukoht` | | | |
| `/galerii` | | | |
| `/sisedisain` | | | |
| `/ehitusinfo` | | | |
| `/arhitektuur-ja-valisdisain` | | | |
| `/arendajast` | | | |
| `/kontakt` | | | |
| `/kkk` | | | |

`/asendiplaan` инспектировать не нужно — это 301; проверять целевой `/kodud-ja-hinnad`.

Правило из ТЗ: `indexed` — ничего не делать; доступен, но не в индексе — Request indexing; неизвестен Google — live test + Request indexing.

---

## 9. Pages report issues

**Не выполнено — нет доступа к Search Console.**

| Reason | URL count | Example URLs | Action | Status |
|---|---|---|---|---|
| Not found (404) | | | ожидаются старые alias до выката | |
| Crawled, currently not indexed | | | допустимо, если адрес доступен | |
| Discovered, currently not indexed | | | допустимо | |
| Duplicate without user-selected canonical | | | проверить canonical | |
| Alternate page with proper canonical | | | норма для hreflang-пар | |
| Page with redirect | | | норма, в sitemap их нет | |
| Blocked by robots.txt | | | ожидается только `/admin` | |
| Excluded by `noindex` | | | публичных быть не должно | |

Ожидание по данным сайта: публичных страниц с `noindex` — 0, заблокированных публичных — 0.

---

## 10. Manual Google search proof

Прямого доступа к Google у меня нет. Ниже — **ориентировочная** проверка через веб-поиск, её нужно продублировать вручную.

| Query | Observed | Notes |
|---|---|---|
| `site:magnoolia.ee` | найдены `/`, `/arhitektuur-ja-valisdisain`, `/ridaelamud-harjumaa` | домен в индексе |
| `magnoolia.estlanda.ee` / `estlanda.ee/magnoolia` | в результатах не появляются | старые хосты не конкурируют |
| «Kinnisvara Müük» (старый title) | не найден на доменах проекта | старый шаблон вне индекса |

Сторонние площадки (Citify, City24) ссылаются на `www.magnoolia.ee` — это внешние ссылки, они корректно приводят на 301 → apex.

---

## 11. Remaining Google waiting time

Индексация и пересчёт позиций — процесс асинхронный. После отправки sitemap и запроса индексации Google обычно обходит приоритетные страницы за дни, но полная переиндексация 107 адресов и перенос сигналов со старого домена занимают недели.

Техническая сторона к этому готова: все адреса отдают 200, canonical единообразен, дубликаты закрыты постоянными редиректами, карта сайта чистая.

Ускорить это средствами сайта нельзя — остаётся ждать.

---

## 12. Найдено и не исправлено: невидимые вопросы в FAQ-разметке

**Что именно.** На пяти страницах разметка `FAQPage` содержит вопросы, которых нет в видимом тексте страницы:

| Страница | Вопросов в JSON-LD | Из них невидимых |
|---|---|---|
| `/` | 9 | 7 |
| `/kkk` | 33 | 9 |
| `/asukoht` | 3 | 3 |
| `/ehitusinfo` | 3 | 2 |
| `/sisedisain` | 2 | 2 |
| **Итого** | | **23** |

Источники: общий блок в `resources/views/partials/seo/schema.blade.php` (для `/` и `/kkk`) и собственные блоки в шаблонах `asukoht`, `sisedisain`, `ehitusinfo`.

**Почему это важно.** Требование Google к `FAQPage` — размеченное содержимое должно быть видно пользователю на той же странице. Невидимая разметка в лучшем случае игнорируется, в худшем даёт основание для ручных мер по структурированным данным.

**Почему не исправлено в этой задаче.** Разметка добавлена осознанно в фазах 28 и 34 и закреплена четырьмя наборами тестов (`MagnooliaPhase28SchemaIntegrityTest`, `MagnooliaPhase34SeoAeoSchemaTest`, `MagnooliaPhase342LandingTest`, `MagnooliaPhase36ListsTest`). Удаление отменило бы ранее принятое решение, а список DO NOT в задании прямо защищает прежние фазы. Единственная альтернатива — вывести эти вопросы на страницы — запрещена запретом менять структуру и тексты.

**Рекомендация.** Убрать `FAQPage` там, где вопросы не выводятся, оставив её только на страницах с видимым FAQ (`/kkk` — 24 собственных вопроса; посадочные страницы — все 6 вопросов видимы, проверено). Это отдельная задача с обновлением тестов.

Заметим: на посадочных страницах проблемы нет — `/ridaelamud-harjumaa` даёт 6 вопросов, все видимы.

---

## 13. Что осталось сделать

**На проде (код):**

```bash
git pull
```

Миграций и artisan-команд не требуется. После выката проверить:

```bash
curl -I https://magnoolia.ee/arhitektuur      # ожидается 301 → /arhitektuur-ja-valisdisain
curl -I https://magnoolia.ee/arendaja         # ожидается 301 → /arendajast
curl -s https://magnoolia.ee/sitemap.xml | grep -c "<loc>"   # ожидается 110
```

**В Search Console (ресурс `magnoolia.ee`, тип Domain):**

1. Отправить `https://magnoolia.ee/sitemap.xml`.
2. Пройти URL Inspection по 10 ключевым адресам из раздела 8, заполнить таблицу.
3. Снять отчёт Pages и заполнить раздел 9.
4. Старый ресурс на `magnoolia.estlanda.ee` не удалять — по нему видно перенос сигналов.

**Вне SEO, но открыто:** `/privaatsuspoliitika` отдаёт 404, при этом ссылка на неё стоит в форме контакта рядом с согласием на обработку данных.
