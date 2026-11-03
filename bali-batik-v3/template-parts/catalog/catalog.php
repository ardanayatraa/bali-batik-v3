<?php
/**
 * Collection and category listing with search, sort, sidebar and pagination.
 *
 * @package BaliBatikV3
 */

defined( 'ABSPATH' ) || exit;

global $wp_query;
$bb3_params = bb3_catalog_params();
$bb3_term   = is_tax( 'bb_product_cat' ) ? get_queried_object() : null;
$bb3_base   = $bb3_term ? (string) get_term_link( $bb3_term ) : bb3_collection_url();
$bb3_total  = (int) $wp_query->found_posts;
$bb3_paged  = max( 1, (int) get_query_var( 'paged' ) );
$bb3_first  = ( $bb3_paged - 1 ) * 12 + 1;
$bb3_last   = min( $bb3_total, $bb3_paged * 12 );
$bb3_title  = $bb3_term ? $bb3_term->name : __( 'The Batik Collection', 'bali-batik-v3' );
$bb3_intro  = $bb3_term && '' !== trim( $bb3_term->description )
	? $bb3_term->description
	: __( 'Batik fabrics, shirts, dresses and scarves. Filter by category, sort by price and save the pieces you love.', 'bali-batik-v3' );
?>
<main id="main" class="bb3-main bb3-catalog">
	<div class="bb3-shell">
		<?php bb3_breadcrumbs_html(); ?>
		<header class="bb3-catalog__head">
			<h1><?php echo esc_html( $bb3_title ); ?></h1>
			<p><?php echo esc_html( $bb3_intro ); ?></p>
		</header>

		<div class="bb3-toolbar">
			<form class="bb3-toolbar__form" method="get" action="<?php echo esc_url( $bb3_base ); ?>">
				<div class="bb3-toolbar__search">
					<label class="screen-reader-text" for="bb3-catalog-q"><?php esc_html_e( 'Search this collection', 'bali-batik-v3' ); ?></label>
					<?php echo bb3_icon( 'search', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<input id="bb3-catalog-q" type="search" name="q" maxlength="80" value="<?php echo esc_attr( $bb3_params['q'] ); ?>" placeholder="<?php esc_attr_e( 'Search by name or motif', 'bali-batik-v3' ); ?>">
				</div>
				<div class="bb3-toolbar__sort">
					<label for="bb3-catalog-sort"><?php esc_html_e( 'Sort by', 'bali-batik-v3' ); ?></label>
					<select id="bb3-catalog-sort" name="sort" data-bb3-autosubmit>
						<?php foreach ( bb3_sort_options() as $bb3_value => $bb3_label ) : ?>
							<option value="<?php echo esc_attr( $bb3_value ); ?>" <?php selected( $bb3_params['sort'], $bb3_value ); ?>><?php echo esc_html( $bb3_label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<button type="submit" class="bb3-btn bb3-btn--dark bb3-toolbar__apply"><?php esc_html_e( 'Apply', 'bali-batik-v3' ); ?></button>
				<button type="button" class="bb3-btn bb3-btn--outline bb3-toolbar__filters" aria-controls="bb3-catalog-filters" aria-expanded="false" data-bb3-filter-toggle><?php echo bb3_icon( 'filter', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Categories', 'bali-batik-v3' ); ?></button>
			</form>

			<?php if ( '' !== $bb3_params['q'] || $bb3_term ) : ?>
				<ul class="bb3-chips" aria-label="<?php esc_attr_e( 'Active filters', 'bali-batik-v3' ); ?>">
					<?php if ( '' !== $bb3_params['q'] ) : ?>
						<?php /* translators: %s: search words. */ ?>
						<li><a href="<?php echo esc_url( bb3_catalog_link( $bb3_base, array( 'q' => '' ) ) ); ?>"><?php echo esc_html( sprintf( __( 'Search: “%s”', 'bali-batik-v3' ), $bb3_params['q'] ) ); ?><?php echo bb3_icon( 'close', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Remove search', 'bali-batik-v3' ); ?></span></a></li>
					<?php endif; ?>
					<?php if ( $bb3_term ) : ?>
						<?php /* translators: %s: category name. */ ?>
						<li><a href="<?php echo esc_url( bb3_catalog_link( bb3_collection_url() ) ); ?>"><?php echo esc_html( sprintf( __( 'Category: %s', 'bali-batik-v3' ), $bb3_term->name ) ); ?><?php echo bb3_icon( 'close', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Remove category', 'bali-batik-v3' ); ?></span></a></li>
					<?php endif; ?>
					<li><a class="bb3-chips__clear" href="<?php echo esc_url( bb3_collection_url() ); ?>"><?php esc_html_e( 'Clear all', 'bali-batik-v3' ); ?></a></li>
				</ul>
			<?php endif; ?>
		</div>

		<div class="bb3-catalog__layout">
			<aside class="bb3-catalog__side" id="bb3-catalog-filters" aria-labelledby="bb3-side-title">
				<h2 id="bb3-side-title"><?php esc_html_e( 'Categories', 'bali-batik-v3' ); ?></h2>
				<ul class="bb3-tree bb3-tree--root">
					<li><a href="<?php echo esc_url( bb3_catalog_link( bb3_collection_url() ) ); ?>"<?php echo $bb3_term ? '' : ' aria-current="page"'; ?>><?php esc_html_e( 'All batik', 'bali-batik-v3' ); ?></a></li>
				</ul>
				<?php echo bb3_category_tree_html(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</aside>

			<div class="bb3-catalog__results">
				<p class="bb3-catalog__count" role="status">
					<?php
					if ( $bb3_total ) {
						/* translators: 1: first item, 2: last item, 3: total items. */
						echo esc_html( sprintf( _n( 'Showing %1$d–%2$d of %3$d piece', 'Showing %1$d–%2$d of %3$d pieces', $bb3_total, 'bali-batik-v3' ), $bb3_first, $bb3_last, $bb3_total ) );
					}
					?>
				</p>
				<?php if ( have_posts() ) : ?>
					<div class="bb3-grid bb3-grid--catalog">
						<?php
						$bb3_n = 0;
						while ( have_posts() ) {
							the_post();
							get_template_part(
								'template-parts/product/card',
								null,
								array(
									'id'      => get_the_ID(),
									'heading' => 'h2',
									'eager'   => $bb3_n++ < 2,
								)
							);
						}
						?>
					</div>
					<?php
					$bb3_add = array_filter(
						array(
							'q'    => $bb3_params['q'],
							'sort' => 'default' !== $bb3_params['sort'] ? $bb3_params['sort'] : '',
						)
					);
					$bb3_pages = paginate_links(
						array(
							'type'      => 'array',
							'add_args'  => $bb3_add ? array_map( 'rawurlencode', $bb3_add ) : false,
							'prev_text' => bb3_icon( 'chevron-left', 16 ) . '<span class="screen-reader-text">' . esc_html__( 'Previous page', 'bali-batik-v3' ) . '</span>',
							'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'bali-batik-v3' ) . '</span>' . bb3_icon( 'chevron-right', 16 ),
						)
					);
					if ( $bb3_pages ) {
						echo '<nav class="bb3-pagination" aria-label="' . esc_attr__( 'Pages', 'bali-batik-v3' ) . '"><ul><li>' . implode( '</li><li>', $bb3_pages ) . '</li></ul></nav>'; // phpcs:ignore WordPress.Security.EscapeOutput
					}
					?>
				<?php else : ?>
					<div class="bb3-empty-state">
						<?php echo bb3_icon( 'search', 36 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<h2><?php esc_html_e( 'No pieces match yet', 'bali-batik-v3' ); ?></h2>
						<p><?php esc_html_e( 'Try a different word, such as a motif (Kawung, Parang) or a color, or clear the filters to see the full collection.', 'bali-batik-v3' ); ?></p>
						<a class="bb3-btn bb3-btn--dark" href="<?php echo esc_url( bb3_collection_url() ); ?>"><?php esc_html_e( 'See all batik', 'bali-batik-v3' ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</main>
