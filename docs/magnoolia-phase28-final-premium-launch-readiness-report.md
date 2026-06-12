# Magnoolia Phase 28 — Final Premium Launch Readiness Report

**Status:** `PASS_PHASE28_FINAL_PREMIUM_LAUNCH_READY`  
**Date:** 11.06.2026  
**Tests:** 77 Phase 28 tests passing, 493 assertions  

---

## 1. Baseline & Overview

Phase 28 является завершающей фазой перед публичным запуском. Основная цель — перевод статуса с `YELLOW_ASSET_LIMITED_BUT_CODE_READY` на `PASS_PHASE28_FINAL_PREMIUM_LAUNCH_READY`.

- **19 домов** в базе, все опубликованы
- **Полный тестовый набор** — все Phase 28 тесты проходят
- **Языки:** ET / RU / EN — без утечек между локалями
- **SEO:** sitemap.xml, robots.txt, llms.txt, Schema.org — все на месте

---

## 2. Работа с ассетами

### 2.1 Логотипы (materials/phase28/Taustata.png → WebP)

Из оригинального RGBA PNG (`Taustata.png`, 2059 KB) сгенерирован полный набор оптимизированных WebP файлов:

| Файл | Назначение | Размер |
|------|-----------|--------|
| `magnoolia-light-trim.webp` | Хедер (белый фон, trim от RGBA, q96) | 554×480px |
| `magnoolia-light-trim-320w.webp` | Хедер мобильный 400w | ~400px |
| `magnoolia-footer.webp` | Футер (прозрачный фон, золотые пиксели) | 554×480px |
| `magnoolia-dark-trim.webp` | Тёмный вариант (запасной) | 554×480px |
| `estlanda-*.webp` | Логотипы застройщика Estlanda (6 вариантов) | различные |

**Ключевое открытие:** все видимые пиксели логотипа Magnoolia — золотые (rgb ~200,150,60). Фильтр `brightness(0) invert(1)` в футере удалён — логотип выглядит в золотом цвете на тёмном фоне без искажений.

**Проблема whitespace решена:** оригинальный файл 1536×1024 содержал 60% пустых полей — после alpha-trim получен чёткий 554×480 с только реальным контентом логотипа.

### 2.2 PPTX экстракция

Из `Magnoolia kodud Prestige Sisedisain.pptx` (35 MB) извлечены изображения:
- Скрипт: `scripts/extract_magnoolia_pptx_assets.py`
- Выход: `public/assets/magnoolia/sisedisain/pptx/` — 20+ WebP изображений
- Интегрированы в страницу `/sisedisain`

### 2.3 Excel/материалы

Спецификации из `Hals.xlsx`, `Plaadid maht.xlsx`, `Copy of Mag. tee ker plaadid.xlsx`:
- Скрипт: `scripts/extract_magnoolia_xlsx_content.py`
- Таблица плиток и сантехники интегрирована в `/ehitusinfo`

---

## 3. Контентные улучшения

### 3.1 Хедер
- Логотип Magnoolia с реальным изображением (было: текст "Magnoolia")
- Адаптивные размеры: 72px desktop → 44px tablet → 36px mobile
- `.main-header__logo { overflow: hidden; height: 72px }` — логотип не влияет на высоту хедера

### 3.2 Футер
- Логотип Magnoolia (золотой, прозрачный фон) — без фильтра
- Блок доверия застройщика Estlanda с логотипом
- `margin-bottom: 14px` под логотипом

### 3.3 Доска доступности (homepage availability board)
- Новый компонент: `sections/magnoolia/home-availability-board.blade.php`
- Показывает статус Vaba/Broneeritud/Müüdud для каждого дома по этапам
- Локализован на ET/RU/EN
- CTA с analytics атрибутами
- id="saadavus" для якорной ссылки

### 3.4 Страница Sisedisain
- Новая галерея с PPTX-изображениями
- Дисклеймер об иллюстративности
- Локализован

### 3.5 Страница Ehitusinfo
- Таблица спецификаций плиток из Excel
- Таблица сантехнического оборудования
- Перекрёстные ссылки на Sisedisain

---

## 4. Языковая чистота (ET/RU/EN)

Исправлены утечки языков:
- Хардкодированные "kevad 2027", "I etapp", "II etapp" → `__()` ключи
- "Hind täpsustamisel" в JavaScript → `__('magnoolia.pricing.price_tbc_inline')`
- Stage labels в asendiplaan.blade.php → локализованы
- unit-detail.blade.php → все лейблы спецификаций через `__()`

Добавлены недостающие ET ключи в `lang/et/magnoolia.php`:
`buyer_note`, `buyer_note_link`, `count_all_pre`, `count_suffix`, `buyer_tip_pre`, и др.

---

## 5. SEO / AI / Schema

| Файл | Статус |
|------|--------|
| `public/sitemap.xml` | ✅ все 19 домов, hreflang ET/RU/EN |
| `public/robots.txt` | ✅ корректный, разрешает всё |
| `public/llms.txt` | ✅ Phase 28 данные: A-класс, Aet Piel контакт, цены TBD |
| Schema.org | ✅ FAQPage, BreadcrumbList, Organization, WebSite |

---

## 6. Тестирование

### Phase 28 тесты (77 passing, 493 assertions):

| Тест-файл | Что проверяет |
|-----------|---------------|
| `MagnooliaPhase28AssetIngestionTest` | WebP логотипы, PPTX изображения, баннер |
| `MagnooliaPhase28HomepageAvailabilityBoardTest` | Доска доступности, 19 домов, CTA analytics |
| `MagnooliaPhase28StageCounterTruthTest` | Нет "0 kodu", корректные счётчики этапов |
| `MagnooliaPhase28LanguagePurityStrictTest` | Нет утечек ET→RU, RU→ET, EN→ET |
| `MagnooliaPhase28LogoIntegrationTest` | Логотип в хедере/футере, alt text, размеры |
| `MagnooliaPhase28SeoAiLaunchFilesTest` | sitemap, robots.txt, llms.txt валидность |
| `MagnooliaPhase28AnalyticsEventIntegrityTest` | CTA с data-mg-analytics атрибутами |
| `MagnooliaPhase28NoVisualResidueStrictTest` | Нет price_cents, OneDrive, lorem |
| `MagnooliaPhase28ContactConversionTest` | Diana Tali: телефон, email, форма |
| `MagnooliaPhase28EhitusinfoSourceIntegrationTest` | Excel данные на странице |
| `MagnooliaPhase28SisedisainPptxIntegrationTest` | PPTX изображения на странице |
| `MagnooliaPhase28SchemaIntegrityTest` | JSON-LD Schema.org |
| `MagnooliaPhase28DianaAssetIntegrationTest` | Корректная обработка отсутствия фото Diana |

---

## 7. Мобильные исправления (post-launch QA)

Серия исправлений мобильного интерфейса, проведённых после основной фазы:

### 7.1 Хедер
- **Логотип маленький** → обрезан alpha-trim от RGBA PNG, `height: 72px` (было 40px)
- **Хедер не на всю ширину** → убран `padding: 15px` с `.main-header .container-fluid`; перемещён в `.main-header__inner { padding: 16px }`

### 7.2 Hero секция
- **Текст прилипал к краям** → `padding-left/right: 20px` для `.main-slider-two__content` на `≤576px`
- **Кнопки не стекались ровно** → `margin-left: 0 !important` для второй кнопки на мобайле

### 7.3 About секция (блок с фото)
- **Cam004 (первое фото) выходило за экран** → `width: 100% !important`, убраны фиксированные `409px`
- **Cam005 (about-two) width:1075px** → `width: 100% !important; min-height: 260px`
- **Второе фото (Valmib 2027 interior) не на всю ширину** → `width: 100% !important` на `.about-one__image__item-two`

### 7.4 Contact секция
- **Вертикальная полоска фона** → `.mg-contact-bg-accent { display: none }` на `≤767px`
- **Trust chips скрыты под sticky кнопкой** → `padding-bottom: 96px` на `.mg-contact-trust-chips`

### 7.5 Scroll-to-top счётчик
- **Перекрывался sticky CTA баром** → `.scroll-top.active { bottom: 96px }` на `≤767px`

### 7.6 Футер логотип
- **Фильтр `brightness(0) invert(1)`** → удалён; создан `magnoolia-footer.webp` с прозрачным фоном (золотые пиксели видны без фильтра)
- **Нет нижнего отступа** → `margin-bottom: 14px`

---

## 8. Итоговый статус

```
✅  19/19 домов активны
✅  77 Phase 28 тестов: PASS (493 assertions)
✅  Логотипы: реальные WebP, без белых полей, без фильтров
✅  Языки ET/RU/EN: чистые, без утечек
✅  SEO: sitemap + robots.txt + llms.txt + Schema.org
✅  Analytics: все ключевые CTA с data-атрибутами
✅  Мобильный: хедер полная ширина, кнопки стекаются, изображения адаптивные
✅  Scroll-to-top: виден над sticky CTA баром
✅  Футер: золотой логотип без фильтров, с отступом
```

**PHASE 28: PASS — PREMIUM LAUNCH READY**
