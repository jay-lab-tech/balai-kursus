import zipfile
import re
from pathlib import Path
base = Path('docs')
files = [
    'LAPORAN KEGIATAN PKL RPL (UPI).docx',
    'LAPORAN PKL (POLBAN yang dokumen).docx'
]
for fn in files:
    p = base / fn
    print('FILE:', p)
    if not p.exists():
        print('  MISSING')
        continue
    with zipfile.ZipFile(p, 'r') as z:
        xml = z.read('word/document.xml').decode('utf-8', errors='ignore')
        ps = re.findall(r'<w:p[\s>].*?</w:p>', xml, flags=re.DOTALL)
        print('  Paragraph count:', len(ps))
        for i, para in enumerate(ps[:50], 1):
            text = ''.join(re.findall(r'<w:t[^>]*>(.*?)</w:t>', para))
            if text.strip():
                print(f'  {i}:', repr(text))
        print('---')
