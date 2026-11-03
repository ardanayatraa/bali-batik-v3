=== Bali Batik V3 ===
Requires at least: 6.4
Tested up to: 6.8
Requires PHP: 8.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Editorial batik catalogue theme with WhatsApp product inquiries.

== Description ==

* Products (bb_product) and three-level product categories (bb_product_cat), compatible with Bali Batik V2 data.
* Collection at /koleksi/, categories at /koleksi/kategori/<slug>/, products at /produk/<slug>/.
* Search (?q=), sorting (?sort=latest|name|price-asc|price-desc), 12 per page, shareable URLs.
* Product page: gallery with swipe, thumbnails and lightbox; details only when filled in; related products.
* WhatsApp button with the product name, price and link. Without a number it falls back to “Contact Us”.
* Favorites stored in the visitor's browser (key bb3_favorites), no account.
* SEO: titles, meta descriptions, canonical URLs, robots rules, Open Graph, Twitter Card and JSON-LD (Organization, WebSite, BreadcrumbList, CollectionPage, Product). Hands over to Yoast, Rank Math, AIOSEO, SEOPress or The SEO Framework when one is active.
* Everything on the homepage is editable in Appearance → Customize → Bali Batik.
* One business name (WordPress Site Title) drives titles, Open Graph, schema, footer, WhatsApp messages, a generated share image and favicon.
* Automatic updates from GitHub Releases or an HTTPS manifest (Appearance → Bali Batik Setup), with SHA-256 checks.
* No jQuery, Elementor, WooCommerce or page builder needed on the front end.

== Installation ==

1. Back up the site.
2. Appearance → Themes → Add New → Upload Theme → choose bali-batik-v3.zip → Activate.
3. On a site without products, demo content is added automatically once. Otherwise use Appearance → Bali Batik Setup.
4. Settings → Permalinks → Save (only if /koleksi/ shows a 404).
5. Customize → Bali Batik → Brand & Contact: add the WhatsApp number.

== Updating ==

Back up first, then upload the new zip and choose “Replace current with uploaded”. Products, categories and Customizer settings are kept.

== Copyright ==

Bali Batik V3 is distributed under the GPL. Bundled fonts, photos and illustrations are listed in LICENSES.md.
