import os
import zipfile
import shutil

base_dir = r"I:\Projeler\pergel"
packages_dir = os.path.join(base_dir, "packages")
local_downloads_dir = os.path.join(base_dir, "public", "downloads", "modules")
os.makedirs(local_downloads_dir, exist_ok=True)

remote_downloads_dir = r"I:\Projeler\erdemeroglu.com.tr\public\downloads\pergel\modules"

modules = ["pergel-field", "pergel-compliance", "pergel-pro"]

for mod in modules:
    pkg_path = os.path.join(packages_dir, mod)
    if not os.path.exists(pkg_path):
        print(f"Skipping {mod}, directory not found.")
        continue
    
    zip_filename = f"{mod}.zip"
    local_zip_path = os.path.join(local_downloads_dir, zip_filename)
    
    with zipfile.ZipFile(local_zip_path, 'w', zipfile.ZIP_DEFLATED) as zf:
        for root, dirs, files in os.walk(pkg_path):
            for file in files:
                full_path = os.path.join(root, file)
                rel_path = os.path.relpath(full_path, pkg_path)
                zf.write(full_path, rel_path)
    
    print(f"Created {local_zip_path} ({os.path.getsize(local_zip_path)} bytes)")

    try:
        os.makedirs(remote_downloads_dir, exist_ok=True)
        remote_zip_path = os.path.join(remote_downloads_dir, zip_filename)
        shutil.copy2(local_zip_path, remote_zip_path)
        print(f"Copied to {remote_zip_path}")
    except Exception as e:
        print(f"Could not copy to remote directory: {e}")

print("Module packaging completed!")
