import importlib.util
from pathlib import Path
import tempfile
import unittest

spec = importlib.util.spec_from_file_location("prepare_release", Path(__file__).with_name("prepare-release.py"))
prepare_release = importlib.util.module_from_spec(spec)
spec.loader.exec_module(prepare_release)


class PrepareReleaseTests(unittest.TestCase):
    def test_unchanged_version_gets_a_patch_release(self):
        self.assertEqual(prepare_release.next_version("3.2.0", "v3.2.0"), "3.2.1")

    def test_explicit_version_is_preserved(self):
        self.assertEqual(prepare_release.next_version("3.3.0", "v3.2.0"), "3.3.0")

    def test_downgrade_and_nonstable_tags_are_rejected(self):
        for current, latest in [("3.1.0", "v3.2.0"), ("3.2.0", "v3.2.1-beta"), ("3.2", "v3.2.0")]:
            with self.subTest(current=current, latest=latest):
                with self.assertRaises(ValueError):
                    prepare_release.next_version(current, latest)

    def test_package_versions_and_changelog_stay_in_sync(self):
        with tempfile.TemporaryDirectory() as directory:
            root = Path(directory)
            theme = root / "bali-batik-v3"
            theme.mkdir()
            (theme / "style.css").write_text("/*\nVersion: 3.2.0\n*/\n", encoding="utf-8")
            (theme / "functions.php").write_text("<?php\ndefine( 'BB3_VERSION', '3.2.0' );\n", encoding="utf-8")
            (theme / "CHANGELOG.md").write_text("# Changelog\n\n## 3.2.0\n\n- Existing notes.\n", encoding="utf-8")
            self.assertEqual(prepare_release.prepare(root, "3.2.0", "v3.2.0"), "3.2.1")
            self.assertIn("Version: 3.2.1", (theme / "style.css").read_text())
            self.assertIn("'BB3_VERSION', '3.2.1'", (theme / "functions.php").read_text())
            self.assertIn("## 3.2.1", (theme / "CHANGELOG.md").read_text(encoding="utf-8"))
            self.assertIn("- Existing notes.", (theme / "CHANGELOG.md").read_text(encoding="utf-8"))
            prepare_release.prepare(root, "3.2.1", "v3.2.0")
            self.assertEqual((theme / "CHANGELOG.md").read_text(encoding="utf-8").count("## 3.2.1"), 1)


if __name__ == "__main__":
    unittest.main()
