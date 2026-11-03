# Bali Batik V3

Tema katalog WordPress dengan produk, kategori, favorit, pertanyaan melalui WhatsApp, dan SEO bawaan.

## Instalasi dan update

Unduh **bali-batik-v3-x.y.z.zip** dari bagian **Assets** di [GitHub Releases](https://github.com/ardanayatraa/bali-batik-v3/releases/latest), lalu unggah melalui **Appearance → Themes → Add New → Upload Theme** dan aktifkan tema.

Tema mulai versi 3.2.0 menggunakan `ardanayatraa/bali-batik-v3` sebagai sumber update bawaan. **Install new theme versions automatically** sudah tercentang pada instalasi baru. Pengaturannya ada di **Appearance → Bali Batik Setup → Automatic updates**; pemilik situs tetap bisa menonaktifkannya. Situs yang sebelumnya menonaktifkan update mempertahankan pilihannya.

Situs yang masih memakai ZIP lama perlu memasang rilis ini sekali. Jika sumber update pernah disimpan kosong atau berisi alamat lama, isi `ardanayatraa/bali-batik-v3` pada pengaturan tersebut. Produk dan pengaturan tersimpan di database; pengeditan langsung pada file tema akan tertimpa saat update.

Lihat [panduan pengelola](bali-batik-v3/PANDUAN.md) dan [changelog](bali-batik-v3/CHANGELOG.md).

## Membuat rilis

1. Buat perubahan di branch sendiri, lalu buka PR ke `main`. GitHub Actions memeriksa PHP, updater, dan pembuatan paket. PR yang belum digabung belum diterbitkan ke situs.
2. Merge PR ke `main`, atau push langsung ke `main` dengan akses kolaborator. Perubahan tema, tooling, atau workflow memicu rilis otomatis setelah pemeriksaan lolos.
3. Jika versi tema masih sama dengan rilis terakhir, workflow menaikkan patch otomatis (misalnya `3.2.1` → `3.2.2`), menyamakan kedua file versi, dan mencatat changelog. Untuk versi minor/major, naikkan `Version` di `style.css` dan `BB3_VERSION` di `functions.php` sendiri serta tulis changelog.
4. Workflow menyimpan versi ke `main`, membuat tag, membangun ZIP, dan menerbitkan Release beserta checksum SHA-256. Tidak perlu membuat tag manual. Sebelum melanjutkan pengembangan, tarik perubahan terbaru dari `main` agar mendapat commit versi otomatis.
5. WordPress mengambil rilis baru sesuai jadwal pemeriksaannya; update bukan seketika setelah push. Perubahan README saja tidak membuat rilis. Workflow juga dapat dijalankan manual pada `main` untuk membuat rilis patch.

Pemeriksaan lokal: `php tools/test-updater.php`, `python -m unittest discover -s tools -p "test_*.py"`, lalu `python tools/release.py`.

Folder `dist/` dan dokumen perencanaan lokal tidak disertakan dalam repositori. ZIP yang dipasang WordPress hanya berisi folder `bali-batik-v3/`.
