# Changelog

## 3.2.1 — 2026-11-03

- Publish theme updates automatically after checks pass on pushes or merged PRs to main. Unmerged PRs run checks without publishing.
- Automatically increment the patch version when unchanged, synchronize both version declarations, create the tag, and attach the installable ZIP and SHA-256 checksum.
- Clarify that automatic installation and its setup checkbox are enabled by default on new installs; preserve an owner's explicit opt-out.

## 3.2.0 — 2026-11-03

- One business name everywhere: the WordPress Site Title (Customize → Bali Batik → Brand & Contact) now drives the logo text, page titles, meta descriptions, Open Graph/Twitter, JSON-LD, footer, WhatsApp messages and patterns. Old theme-stored names are moved to the Site Title once.
- Placeholders {brand}, {tagline} and {year} in every Customizer text and in SEO fields; shortcodes [bb3_brand] / [bb3_tagline] for page content. Demo About/Contact pages use them.
- Default share image (1200 × 630) and favicon are generated from the current name, tagline and colours, and regenerated when they change. The bundled images with a baked-in name were removed.
- Automatic updates out of the box from the official GitHub channel (ardanayatraa/bali-batik-v3), or another GitHub repo / HTTPS JSON manifest, shown in Dashboard → Updates, optional unattended install, SHA-256 verification, settings under Appearance → Bali Batik Setup (or BB3_UPDATE_SOURCE / BB3_AUTO_UPDATE in wp-config.php).
- Release tooling outside the theme: tools/release.py and .github/workflows/release.yml.

## 3.1.0 — 2026-11-03

- Icons redrawn on a consistent 24 px grid (Lucide, ISC); WhatsApp glyph on every WhatsApp action.
- Customizer live editing: every homepage section, the announcement bar and the footer refresh instantly and show a pencil shortcut in the preview.
- Homepage Layout: drag to reorder sections, untick to hide (PRD CMS-02), optional transparent header.
- Design & Layout options: accent, dark, bar, ticker and soft colours; hero and heading fonts; button shape; corner radius; product photo shape; collection columns; page width; hero height and darkness.
- Block editor: Bali Batik blocks (Hero banner, Promo banner, Product grid, Homepage section) with text edited directly on the canvas; block styles for core blocks; patterns incl. a full homepage.
- Homepage can be a block-editor page (Customize → Bali Batik → Homepage Layout → Block editor page). A draft “Home – Block Editor” page is created to try it.
- Performance: inline CSS, WebP image sizes, 360 px card size for phones, explicit lazy loading, hero preload; pages are revalidated instead of cached for a week.

## 3.0.0 — 2026-11-03

First V3 release for the preview site.

- New theme written from scratch; keeps the V2 layout and look (transparent header, serif hero, green square CTA, moving category row, 600:789 product cards, 15 homepage sections in V2 order).
- English copy throughout; no invented claims (no founding year, ratings, discounts or delivery promises).
- Built-in SEO: titles, descriptions, canonical, robots, Open Graph with image size and alt, Twitter Card, JSON-LD graph; per-page overrides; hands over to SEO plugins.
- Accessible category carousel: pause/play, previous/next, pauses on hover, focus, touch, hidden tab and off-screen; respects reduced motion; clones hidden from screen readers.
- Favorites use their own storage key (`bb3_favorites`) and mark removed products as “No longer available”.
- CTA green deepened from #4CB96C to #2F8548 so white button text meets WCAG AA contrast. Change `--bb3-green` in `assets/css/theme.css` to revert.
- Demo importer: idempotent, flags demo items, never overwrites edited content; removal only trashes untouched demo items.
