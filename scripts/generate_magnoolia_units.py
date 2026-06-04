import zipfile
import xml.etree.ElementTree as ET
import re
from pathlib import Path

root = Path('..') / 'materials'
xlsx = root / '_Magnoolia hinnatabel 05.05.26.xlsx'
ns = {'m': 'http://schemas.openxmlformats.org/spreadsheetml/2006/main'}

with zipfile.ZipFile(xlsx, 'r') as z:
    shared_strings = []
    if 'xl/sharedStrings.xml' in z.namelist():
        sr = ET.fromstring(z.read('xl/sharedStrings.xml'))
        for si in sr.findall('m:si', ns):
            texts = [t.text or '' for t in si.findall('.//m:t', ns)]
            shared_strings.append(''.join(texts))

    sh = ET.fromstring(z.read('xl/worksheets/sheet1.xml'))
    rows = []
    for row in sh.findall('m:sheetData/m:row', ns):
        d = {}
        for c in row.findall('m:c', ns):
            r = c.get('r', '')
            col = ''.join(ch for ch in r if ch.isalpha())
            t = c.get('t')
            v = c.find('m:v', ns)
            if v is None:
                val = ''
            elif t == 's':
                val = shared_strings[int(v.text)]
            else:
                val = v.text or ''
            d[col] = val
        rows.append(d)


def to_float(value):
    if value in ('', None):
        return None
    try:
        return float(str(value).replace(',', '.'))
    except Exception:
        return None


def to_int(value):
    f = to_float(value)
    return int(f) if f is not None else None


units = []
current_building = None
for d in rows:
    cell_a = (d.get('A') or '').strip()
    if not d.get('B'):
        if re.fullmatch(r'Magnoolia\s+\d+', cell_a):
            current_building = int(cell_a.split()[-1])
        continue

    if not cell_a and current_building is None:
        continue

    if cell_a and re.fullmatch(r'Magnoolia\s+\d+', cell_a):
        current_building = int(cell_a.split()[-1])

    if current_building is None:
        continue

    section_number = to_int(d.get('B'))
    if section_number is None:
        continue

    building_number = current_building
    unit_number = section_number
    stage = 1 if building_number in (1, 3) else 2
    unit_id = f'tee-{building_number}-{unit_number}'
    address = f'Magnoolia tee {building_number}/{unit_number}'

    rooms = to_int(d.get('D'))
    price = to_float(d.get('J'))

    units.append({
        'id': unit_id,
        'address': address.replace('-', '/'),
        'building': f'Magnoolia tee {building_number}',
        'section': f'{building_number}/{unit_number}',
        'stage': stage,
        'completion': 'kevad 2027' if stage == 1 else 'kevad 2028',
        'plan_type': 'type-b' if rooms == 5 else 'type-a',
        'rooms': rooms,
        'net_area': to_float(d.get('C')),
        'terrace_area': to_float(d.get('E')),
        'balcony_area': to_float(d.get('F')),
        'storage_area': to_float(d.get('G')),
        'private_yard_area': to_float(d.get('H')),
        'parking_spaces': 2,
        'parking': 2,
        'price': int(price) if price is not None else None,
        'price_public': stage == 1,
        'status': 'available' if stage == 1 else 'tbc',
        'masterplan_key': f'tee-{building_number}-pos-{unit_number}',
        'floorplan_1_pdf': f'assets/magnoolia/floorplans/M{building_number}_1korrus.pdf',
        'floorplan_2_pdf': f'assets/magnoolia/floorplans/M{building_number}_2korrus.pdf',
        'floorplan_1_image': None,
        'floorplan_2_image': None,
        'source': 'hinnatabel_2026_05_05',
    })

units = sorted(
    units,
    key=lambda u: (
        u['stage'],
        int(u['id'].split('-')[1]),
        int(u['id'].split('-')[2]),
    ),
)

out = Path('config') / 'magnoolia_units.php'
with out.open('w', encoding='utf-8') as f:
    f.write("<?php\n\nreturn [\n")
    for unit in units:
        f.write("    [\n")
        for key, value in unit.items():
            if isinstance(value, str):
                escaped = value.replace("'", "\\'")
                f.write(f"        '{key}' => '{escaped}',\n")
            elif isinstance(value, bool):
                f.write(f"        '{key}' => {'true' if value else 'false'},\n")
            elif value is None:
                f.write(f"        '{key}' => null,\n")
            elif isinstance(value, float):
                f.write(f"        '{key}' => {value:.1f},\n")
            else:
                f.write(f"        '{key}' => {value},\n")
        f.write("    ],\n")
    f.write("];\n")

print(f'Wrote {len(units)} units to {out}')
