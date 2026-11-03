"""Build an installable Bali Batik V3 package.

    python tools/release.py                      # dist/bali-batik-v3-<version>.zip + .sha256
    python tools/release.py --base-url https://example.com/updates/
                                                 # also dist/bali-batik-v3.json (update manifest)

The version is read from bali-batik-v3/style.css ("Version:"). Bump it there
(and BB3_VERSION in functions.php) before every release, otherwise sites will
not see an update.
"""
import argparse
import hashlib
import json
import os
import re
import sys
import zipfile

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
THEME = os.path.join(ROOT, "bali-batik-v3")
DIST = os.path.join(ROOT, "dist")
SKIP_DIRS = {".git", ".github", "node_modules", "_private"}
SKIP_FILES = (".zip", ".log", ".env", ".DS_Store")


def read_version() -> str:
    with open(os.path.join(THEME, "style.css"), encoding="utf-8") as fh:
        match = re.search(r"^Version:\s*([0-9.]+)\s*$", fh.read(), re.M)
    if not match:
        sys.exit("Version not found in style.css")
    version = match.group(1)
    with open(os.path.join(THEME, "functions.php"), encoding="utf-8") as fh:
        if f"'BB3_VERSION', '{version}'" not in fh.read():
            sys.exit(f"BB3_VERSION in functions.php does not match style.css ({version})")
    return version


def build(version: str) -> str:
    os.makedirs(DIST, exist_ok=True)
    out = os.path.join(DIST, f"bali-batik-v3-{version}.zip")
    count = 0
    with zipfile.ZipFile(out, "w", zipfile.ZIP_DEFLATED) as zf:
        for root, dirs, files in os.walk(THEME):
            dirs[:] = [d for d in dirs if d not in SKIP_DIRS and not d.startswith(".")]
            for name in files:
                if name.startswith(".") or name.endswith(SKIP_FILES):
                    continue
                path = os.path.join(root, name)
                arc = "bali-batik-v3/" + os.path.relpath(path, THEME).replace(os.sep, "/")
                zf.write(path, arc)
                count += 1
    print(f"{out}  ({count} files, {os.path.getsize(out) // 1024} KB)")
    return out


def sha256(path: str) -> str:
    digest = hashlib.sha256()
    with open(path, "rb") as fh:
        for chunk in iter(lambda: fh.read(1 << 16), b""):
            digest.update(chunk)
    return digest.hexdigest()


def main() -> None:
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("--base-url", help="HTTPS folder where the ZIP will be uploaded (writes a JSON manifest)")
    args = parser.parse_args()

    version = read_version()
    package = build(version)
    checksum = sha256(package)
    with open(package + ".sha256", "w", encoding="utf-8") as fh:
        fh.write(f"{checksum}  {os.path.basename(package)}\n")
    print(f"sha256: {checksum}")

    if args.base_url:
        base = args.base_url.rstrip("/") + "/"
        if not base.startswith("https://"):
            sys.exit("--base-url must start with https://")
        manifest = {
            "version": version,
            "package": base + os.path.basename(package),
            "sha256": checksum,
            "url": base + "CHANGELOG.md",
            "requires": "6.4",
            "requires_php": "8.0",
        }
        path = os.path.join(DIST, "bali-batik-v3.json")
        with open(path, "w", encoding="utf-8") as fh:
            json.dump(manifest, fh, indent=2)
        print(f"manifest: {path}")


if __name__ == "__main__":
    main()
