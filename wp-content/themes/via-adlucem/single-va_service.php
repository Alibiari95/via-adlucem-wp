<?php
/**
 * Single service: header image banner (featured image + title + excerpt),
 * Elementor content, then links to the other services.
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$current_id = get_the_ID();
	$image      = get_the_post_thumbnail_url( $current_id, 'full' );
	?>
	<section class="va-banner va-banner--image"<?php echo $image ? ' style="--va-banner-image:url(\'' . esc_url( $image ) . '\')"' : ''; ?>>
		<div class="va-container">
			<p class="va-banner__label"><a href="<?php echo esc_url( get_post_type_archive_link( 'va_service' ) ); ?>"><?php echo esc_html( va_t( 'services' ) ); ?></a></p>
			<h1 class="va-banner__title"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="va-banner__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<div class="va-service-content">
		<?php the_content(); ?>
	</div>
	<?php
endwhile;

// All services in this language, in menu order, so each card keeps its number.
$all = get_posts(
	array(
		'post_type'        => 'va_service',
		'numberposts'      => -1,
		'orderby'          => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		'fields'           => 'ids',
		'suppress_filters' => false, // Lets Polylang limit the list to the current language.
	)
);
$others = array_values( array_diff( $all, array( $current_id ) ) );

if ( $others ) :
	?>
	<section class="va-block va-block--surface">
		<div class="va-container">
			<div class="va-block__head">
				<h2 class="va-block__label"><?php echo esc_html( va_t( 'other_services' ) ); ?></h2>
				<a class="va-link" href="<?php echo esc_url( get_post_type_archive_link( 'va_service' ) ); ?>"><?php echo esc_html( va_t( 'services' ) ); ?> <span aria-hidden="true">&rarr;</span></a>
			</div>
			<div class="va-service-grid va-service-grid--3">
				<?php
				global $post;
				foreach ( $others as $other_id ) {
					$post = get_post( $other_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
					setup_postdata( $post );
					get_template_part( 'template-parts/service-card', null, array( 'number' => array_search( $other_id, $all, true ) + 1 ) );
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php
endif;

get_footer();
