import zipfile, re
from pathlib import Path
base = Path('docs')
files = ['LAPORAN KEGIATAN PKL RPL (UPI).docx', 'LAPORAN PKL (POLBAN yang dokumen).docx']
for fn in files:
    p = base / fn
    print('FILE:', p)
    with zipfile.ZipFile(p, 'r') as z:
        xml = z.read('word/document.xml').decode('utf-8', errors='ignore')
    text = re.sub(r'<w:instrText[^>]*>.*?</w:instrText>', '', xml, flags=re.DOTALL)
    text = re.sub(r'<[^>]+>', '', text)
    text = text.replace('&nbsp;', ' ').replace('\r', '').replace('\n', ' ')
    text = re.sub(r'\s+', ' ', text)
    for keyword in ['BAB I', 'BAB II', 'BAB III', 'BAB IV', 'BAB V', 'BAB VI']:
        idx = text.upper().find(keyword)
        if idx != -1:
            print('  found', keyword, 'at', idx)
            snippet = text[max(0, idx-200):idx+400]
            print('  ...', snippet)
    print('---')
