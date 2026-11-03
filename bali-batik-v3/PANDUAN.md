# Panduan Singkat Pengelola — Bali Batik V3

## Produk & kategori
- **Products → Add Product**: isi judul, *Excerpt* (ringkasan), foto utama (*Main product photo*), galeri (maks. 10 foto), harga, motif, bahan, ukuran, warna, perawatan.
- Centang **Our Pick** agar tampil di bagian “Our Picks”. Urutan tampil: *Page Attributes → Order* (angka kecil lebih dulu).
- **Products → Categories**: maksimal 3 tingkat; atur *Display order* dan *Cover image*.
- Produk berlabel **Demo** adalah contoh — ganti atau hapus sebelum rilis.

## Edit tampilan beranda (live)
- **Appearance → Customize → Bali Batik**. Di preview, klik **ikon pensil** di samping bagian mana pun untuk langsung mengedit teks/fotonya; perubahan tampil seketika.
- **Homepage Layout**: seret untuk mengubah urutan bagian, hilangkan centang untuk menyembunyikan.
- **Design & Layout**: warna tombol/aksen, warna header, font judul, bentuk tombol, radius sudut, bentuk foto produk, jumlah kolom katalog, lebar halaman, tinggi & gelap hero.
- **Brand & Contact → Business name / Short tagline**: satu-satunya tempat mengganti nama bisnis. Otomatis berlaku di logo teks, judul halaman & Google, Open Graph/Twitter, schema, footer, pesan WhatsApp, gambar share 1200 × 630 dan favicon (dibuat ulang otomatis).
- Di teks Customizer mana pun boleh menulis **{brand}**, **{tagline}**, **{year}**; di isi halaman pakai **[bb3_brand]** / **[bb3_tagline]**.
- **Brand & Contact**: nomor WhatsApp (tanpa nomor, tombol berubah menjadi “Contact Us”), e-mail, alamat, media sosial.
- Favicon sendiri (opsional): **Customize → Site Identity → Site Icon**; selama kosong, favicon inisial nama dipakai.

## Edit langsung di halaman (Block Editor)
- Buka **Pages → Home – Block Editor** (draf). Ketik langsung di judul hero, banner, judul grid produk.
- Blok tersedia di kategori **Bali Batik**: *Hero banner*, *Promo banner*, *Product grid*, *Homepage section*. Pola siap pakai: *Bali Batik: full homepage*, *three promo banners*, *brand statement*, *about text*.
- Agar halaman ini menjadi beranda: terbitkan halaman → **Settings → Reading → Your homepage displays: A static page** → pilih halamannya → **Customize → Bali Batik → Homepage Layout → Homepage content: Block editor page**.

## SEO & berbagi
- Tiap produk/halaman punya kotak **Search & Sharing**: judul pencarian, deskripsi, gambar berbagi 1200 × 630.
- Gambar berbagi bawaan: **Customize → Bali Batik → Search & Sharing (SEO)**. Kosongkan agar gambar dibuat otomatis dari nama & tagline.
- Jika memasang plugin SEO (Yoast, Rank Math, dll.), tema otomatis menyerahkan meta tag ke plugin.
- Situs pratinjau: untuk mencegah diindeks Google, aktifkan **Settings → Reading → Discourage search engines** (skor SEO PageSpeed akan turun karena itu disengaja).

## Update otomatis
1. Bawaan: tema otomatis mengecek rilis resmi di GitHub `ardanayatraa/bali-batik-v3` — tidak perlu disetel. Untuk mengganti sumber: **Appearance → Bali Batik Setup → Automatic updates** → isi *Update source*:
   - repositori GitHub `owner/repo` (Release berisi `bali-batik-v3-x.y.z.zip`), atau
   - alamat HTTPS file manifest `bali-batik-v3.json`.
2. *Install new theme versions automatically* sudah tercentang secara bawaan pada instalasi baru. Jika sebelumnya dinonaktifkan, centang kembali → **Save and check now**.
3. WordPress mengecek 2× sehari; versi baru muncul di **Dashboard → Updates** dan terpasang otomatis. File diverifikasi dengan SHA-256 bila tersedia. Produk & pengaturan tetap aman.
4. Untuk situs klien bisa dikunci di `wp-config.php`: `define( 'BB3_UPDATE_SOURCE', 'owner/repo' );` dan `define( 'BB3_AUTO_UPDATE', true );`.
5. Jika situs memakai ZIP lama tanpa sumber update, unggah ZIP 3.2.0 dari GitHub Releases sekali. Jika sumber sebelumnya tersimpan kosong atau memakai alamat lama, isi `ardanayatraa/bali-batik-v3` lalu **Save and check now**.

## Merilis versi baru (developer)
1. Ubah kode di branch sendiri, lalu buka PR ke `main` agar pemeriksaan dijalankan. PR diterbitkan setelah di-merge; push langsung ke `main` juga didukung.
2. GitHub: perubahan tema di `main` yang lolos pemeriksaan otomatis menjadi ZIP + Release (dengan sha256). Patch dinaikkan otomatis jika belum diubah. Untuk minor/major, naikkan versi di `style.css` (*Version*) **dan** `functions.php` (*BB3_VERSION*) sendiri. Tag dibuat otomatis; tarik perubahan `main` sebelum mengembangkan lagi agar mendapat commit versi terbaru.
3. Tanpa GitHub: `python tools/release.py --base-url https://domain-anda/updates/` → unggah ZIP + `bali-batik-v3.json` ke folder itu.

## Backup & pembaruan manual
1. Backup situs (hPanel → Backups) sebelum memperbarui tema.
2. **Appearance → Themes → Add New → Upload Theme** → pilih ZIP baru → *Replace current with uploaded*.
3. Produk, kategori, dan pengaturan Customizer tetap tersimpan.
