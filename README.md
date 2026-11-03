# Bali Batik V3

Tema katalog WordPress dengan produk, kategori, favorit, pertanyaan melalui WhatsApp, dan SEO bawaan.

## Instalasi dan update

Unduh **bali-batik-v3-3.2.0.zip** dari [GitHub Releases](https://github.com/ardanayatraa/bali-batik-v3/releases/latest), lalu unggah melalui **Appearance → Themes → Add New → Upload Theme**. Untuk versi berikutnya, pilih ZIP tema yang dilampirkan pada rilis tersebut.

Tema mulai versi 3.2.0 menggunakan `ardanayatraa/bali-batik-v3` sebagai sumber update bawaan. Pengaturannya ada di **Appearance → Bali Batik Setup → Automatic updates**. Aktifkan **Install new theme versions automatically**, lalu **Save and check now**.

Situs yang masih memakai ZIP lama perlu memasang rilis ini sekali. Jika sumber update pernah disimpan kosong atau berisi alamat lama, isi `ardanayatraa/bali-batik-v3` pada pengaturan tersebut. Produk dan pengaturan tersimpan di database; pengeditan langsung pada file tema akan tertimpa saat update.

Lihat [panduan pengelola](bali-batik-v3/PANDUAN.md) dan [changelog](bali-batik-v3/CHANGELOG.md).

## Membuat rilis

1. Naikkan `Version` di `bali-batik-v3/style.css` dan `BB3_VERSION` di `bali-batik-v3/functions.php`, lalu tulis changelog.
2. Jalankan `php tools/test-updater.php` dan `python tools/release.py` untuk memeriksa updater dan membuat ZIP lokal.
3. Commit dan push ke `main`, lalu push tag sesuai versi, misalnya `git tag v3.2.1` dan `git push origin v3.2.1`.
4. GitHub Actions memeriksa versi, memvalidasi PHP, menguji updater, membangun ZIP, dan menerbitkan Release dengan checksum SHA-256. Periksa workflow selesai sebelum membagikan rilis.

Folder `dist/` dan dokumen perencanaan lokal tidak disertakan dalam repositori. ZIP yang dipasang WordPress hanya berisi folder `bali-batik-v3/`.
