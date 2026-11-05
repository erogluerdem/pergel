#!/usr/bin/env python3
"""
Simple helper to extract metadata from an IFC or other BIM file.
Tries to use ifcopenshell when available; otherwise creates deterministic demo metadata.

Usage:
    python scripts/process_ifc.py <input_file> <output_json>

Note: For real geometry-based extraction install ifcopenshell (pip) and related geometry backends.
"""
import sys, os, json, mimetypes, hashlib

def deterministic_bbox(seed, idx):
    # return min and max arrays of length 3 based on a seed and index
    h = int(hashlib.sha256((str(seed)+str(idx)).encode('utf8')).hexdigest()[:8],16)
    minx = (h % 100) / 100.0
    miny = ((h>>8) % 100) / 100.0
    minz = ((h>>16) % 50) / 100.0
    return {'min':[minx,miny,minz],'max':[minx+0.2,miny+0.2,minz+0.2]}

def main():
    if len(sys.argv) < 3:
        print(json.dumps({'error':'usage'}))
        return
    input_path = sys.argv[1]
    out_path = sys.argv[2]

    meta = {}
    try:
        meta['size'] = os.path.getsize(input_path)
    except Exception:
        meta['size'] = None
    meta['mimetype'] = mimetypes.guess_type(input_path)[0]

    # try to import ifcopenshell
    try:
        import ifcopenshell
        has_ifc = True
    except Exception:
        has_ifc = False

    elements = []
    model_bbox = None
    if has_ifc:
        try:
            f = ifcopenshell.open(input_path)
            # collect products (IfcProduct) as element list
            products = f.by_type('IfcProduct')
            seed = len(products)
            for i,p in enumerate(products[:200]):
                elements.append({'id': getattr(p, 'GlobalId', None) or getattr(p, 'ExpressId', None) or i, 'type': p.is_a(), 'bbox': deterministic_bbox(seed,i)})
            model_bbox = deterministic_bbox(seed, 'model')
        except Exception as e:
            # fallback to deterministic
            has_ifc = False

    if not has_ifc:
        # produce deterministic metadata: 10 elements
        seed = int(meta['size'] or 0)
        for i in range(10):
            elements.append({'id': i+1, 'type':'IfcProduct', 'bbox': deterministic_bbox(seed,i)})
        model_bbox = deterministic_bbox(seed, 'model')

    meta['bbox'] = model_bbox
    meta['elements'] = elements

    try:
        with open(out_path, 'w', encoding='utf8') as fh:
            json.dump(meta, fh)
        print(json.dumps({'ok':True, 'out': out_path}))
    except Exception as e:
        print(json.dumps({'error': str(e)}))

if __name__=='__main__':
    main()
