import os
import zipfile

base_dir = r"I:\Projeler\pergel"
output_dir = os.path.join(base_dir, "public", "downloads")
os.makedirs(output_dir, exist_ok=True)
zip_path = os.path.join(output_dir, "pergel-community-latest.zip")

include_dirs = [
    "app",
    "bootstrap",
    "config",
    "database",
    "docs",
    "kullanim_rehberi",
    "packages",
    "resources",
    "routes",
    "scripts",
]

include_files = [
    ".editorconfig",
    ".env.example",
    ".gitattributes",
    ".gitignore",
    "artisan",
    "composer.json",
    "package.json",
    "phpunit.xml",
    "README.md",
    "LICENSE",
    "vite.config.js",
]

print(f"Building clean community zip: {zip_path}")

with zipfile.ZipFile(zip_path, 'w', zipfile.ZIP_DEFLATED) as zf:
    # 1. Add specific root files
    for fname in include_files:
        fpath = os.path.join(base_dir, fname)
        if os.path.exists(fpath):
            zf.write(fpath, fname)
            print(f"Added {fname}")

    # 2. Add included directories
    for idir in include_dirs:
        dir_path = os.path.join(base_dir, idir)
        if not os.path.exists(dir_path):
            continue
        for root, dirs, files in os.walk(dir_path):
            # Exclude bootstrap/cache generated files except .gitignore
            if "bootstrap" in root and "cache" in root:
                for file in files:
                    if file == ".gitignore":
                        full_p = os.path.join(root, file)
                        zf.write(full_p, os.path.relpath(full_p, base_dir))
                continue

            for file in files:
                if (
                    file.endswith(".stackdump")
                    or file.endswith(".log")
                    or file.endswith(".sqlite")
                    or file.endswith(".sqlite-journal")
                    or file.endswith(".sqlite-wal")
                    or file.endswith(".sqlite-shm")
                    or file.endswith(".bak")
                    or "_old." in file
                ):
                    continue
                full_p = os.path.join(root, file)
                rel_p = os.path.relpath(full_p, base_dir)
                zf.write(full_p, rel_p)

    # 3. Add public directory (excluding downloads, storage, hot)
    public_dir = os.path.join(base_dir, "public")
    for root, dirs, files in os.walk(public_dir):
        rel_root = os.path.relpath(root, base_dir)
        parts = rel_root.split(os.sep)
        if "downloads" in parts or "storage" in parts or "hot" in parts:
            continue
        for file in files:
            full_p = os.path.join(root, file)
            rel_p = os.path.relpath(full_p, base_dir)
            zf.write(full_p, rel_p)

    # 4. Add empty skeleton for storage and modules
    skeleton_dirs = [
        "storage/app/public",
        "storage/framework/cache/data",
        "storage/framework/sessions",
        "storage/framework/views",
        "storage/logs",
        "modules",
    ]
    for skel in skeleton_dirs:
        gitkeep_path = os.path.join(base_dir, skel, ".gitkeep")
        if os.path.exists(gitkeep_path):
            zf.write(gitkeep_path, os.path.join(skel, ".gitkeep"))
        else:
            # write an empty entry
            zf.writestr(f"{skel}/.gitkeep", "")

size_mb = os.path.getsize(zip_path) / (1024 * 1024)
print(f"DONE: {zip_path} ({size_mb:.2f} MB)")

remote_dir = r"I:\Projeler\erdemeroglu.com.tr\public\downloads\pergel"
if os.path.exists(r"I:\Projeler\erdemeroglu.com.tr"):
    import shutil
    try:
        os.makedirs(remote_dir, exist_ok=True)
        remote_zip = os.path.join(remote_dir, "pergel-community-latest.zip")
        shutil.copy2(zip_path, remote_zip)
        print(f"Copied community release to: {remote_zip}")
    except Exception as e:
        print(f"Could not copy to remote directory: {e}")
