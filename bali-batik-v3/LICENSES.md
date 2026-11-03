# Bali Batik V3 — licences and asset sources

Checked: 3 November 2026. This is a record of origin, not legal advice.

## Code

All PHP, CSS and JavaScript in this theme was written for Bali Batik V3. No code from Porto, Shop55 or any other commercial theme is included. Layout conventions (transparent header over a photo, product grids, a moving category row) are common e-commerce patterns; the V2 look was used only as a visual reference for spacing, colours and type sizes, as required by the PRD.

Licence: GPL-2.0-or-later (same as WordPress).

## Icons

UI icons in `inc/icons.php` follow **Lucide** (https://lucide.dev), ISC licence — free for commercial use; the licence text is kept in `assets/icons/LICENSE-lucide.txt`. The WhatsApp, X and TikTok glyphs were drawn for this theme (the WhatsApp mark is used only to label the “Ask on WhatsApp” contact action).

## Fonts

| File | Source | Licence |
| --- | --- | --- |
| `assets/fonts/poppins-400/500/600/700.woff` | Poppins by Indian Type Foundry, subset to Latin from the files shipped with Bali Batik V2 | SIL Open Font License 1.1 — see `assets/fonts/OFL.txt` |

The hero serif uses the visitor's system font stack (Georgia, Times New Roman). No serif font file is bundled.

## Photographs (Unsplash)

| File | Photographer | Source | Licence |
| --- | --- | --- | --- |
| `assets/batik/hero-batik-rack.jpg` | Farida Tania | https://unsplash.com/photos/colorful-patterned-fabrics-hang-on-a-rack-PRHza8Yuv9s | Unsplash License |
| `assets/batik/hero-batik-craft.jpg` | Camille Bismonte | https://unsplash.com/photos/vUc03gxjEY4 | Unsplash License |
| `assets/batik/hero-batik-fabric.jpg` | Arista Indrajaya | https://unsplash.com/photos/five-folded-batik-fabrics-with-nature-motifs-GVxfo8fTrR0 | Unsplash License |

Source record carried over from `bali-batik-v2/assets/batik/SOURCES.md`. Files were re-compressed (JPEG quality 66); pixels were not otherwise changed. The Unsplash License (https://unsplash.com/license) allows free commercial use without attribution, but does not allow selling the photos unaltered or using them to build a competing photo service. Stock photos illustrate batik in general; they do not show Bali Batik's premises, staff or products. Replace them with the business's own photos before launch where possible.

## Product illustrations

`assets/demo/*.jpg` are illustrations created for the Bali Batik project (per the V2 source record). They are demo material only and must not be presented as real stock. Demo products using them are marked “Demo” in the admin product list.

## Generated images

`screenshot.jpg` was composed for this theme from the photographs and illustrations above, with text set in Poppins (OFL). The default share image and favicon are generated on the site (uploads/bb3/) from the hero photo `hero-batik-fabric.jpg` and the business name, using `assets/fonts/poppins-500.ttf` and `poppins-700.ttf` (Latin subsets of Poppins, OFL).

## Before launch

- Replace demo products, prices and illustrations with real products and photos owned by the business.
- Record the owner's permission for the logo, product photos, texts and any testimonials.
- Keep this file and `assets/fonts/OFL.txt` in the theme package.
