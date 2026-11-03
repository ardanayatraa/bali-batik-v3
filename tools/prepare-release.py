"""Prepare a main-branch release, bumping the patch if its version is unchanged."""
import argparse
from datetime import datetime, timedelta, timezone
from pathlib import Path
import re

from release import ROOT, read_version


def version_parts(value: str) -> tuple[int, int, int]:
    if not re.fullmatch(r"\d+\.\d+\.\d+", value):
        raise ValueError(f"Expected a stable x.y.z version, received {value!r}")
    return tuple(int(part) for part in value.split("."))


def next_version(current: str, latest: str) -> str:
    current_parts = version_parts(current)
    latest_parts = version_parts(latest.removeprefix("v"))
    if current_parts < latest_parts:
        raise ValueError("Theme version is older than the latest release; sync main first")
    if current_parts == latest_parts:
        major, minor, patch = current_parts
        return f"{major}.{minor}.{patch + 1}"
    return current


def prepare(root: Path, current: str, latest: str) -> str:
    version = next_version(current, latest)
    theme = root / "bali-batik-v3"
    style_path = theme / "style.css"
    functions_path = theme / "functions.php"
    style = style_path.read_text(encoding="utf-8")
    functions = functions_path.read_text(encoding="utf-8")
    if not re.search(rf"^Version:\s*{re.escape(current)}\s*$", style, re.M):
        raise ValueError("style.css version does not match the release input")
    if f"'BB3_VERSION', '{current}'" not in functions:
        raise ValueError("BB3_VERSION does not match style.css")
    if version != current:
        style = re.sub(r"^Version:[^\r\n]*", f"Version: {version}", style, count=1, flags=re.M)
        functions = functions.replace(f"'BB3_VERSION', '{current}'", f"'BB3_VERSION', '{version}'", 1)
        style_path.write_text(style, encoding="utf-8", newline="\n")
        functions_path.write_text(functions, encoding="utf-8", newline="\n")
    changelog_path = theme / "CHANGELOG.md"
    changelog = changelog_path.read_text(encoding="utf-8")
    if not re.search(rf"^## {re.escape(version)}(?:\s|$)", changelog, re.M):
        if not changelog.startswith("# Changelog\n"):
            raise ValueError("CHANGELOG.md must start with '# Changelog'")
        today = datetime.now(timezone(timedelta(hours=8))).date()
        entry = f"\n## {version} — {today}\n\n- Theme changes published from main; automatic patch release.\n"
        changelog = changelog.replace("# Changelog\n", "# Changelog\n" + entry, 1)
        changelog_path.write_text(changelog, encoding="utf-8", newline="\n")
    return version


def main() -> None:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--latest", required=True, help="Latest published tag, e.g. v3.2.0")
    parser.add_argument("--github-output", type=Path)
    args = parser.parse_args()
    try:
        version = prepare(Path(ROOT), read_version(), args.latest)
    except ValueError as error:
        parser.exit(1, f"{error}\n")
    if args.github_output:
        with args.github_output.open("a", encoding="utf-8") as output:
            output.write(f"version={version}\n")
    print(f"Release version: {version}")


if __name__ == "__main__":
    main()
