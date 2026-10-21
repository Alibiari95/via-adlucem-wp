<?php
/**
 * Services overview (/services/, /az/services/, /uz/services/).
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="va-banner">
	<div class="va-container">
		<p class="va-banner__label"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
		<h1 class="va-banner__title"><?php echo esc_html( va_t( 'services' ) ); ?></h1>
		<p class="va-banner__text"><?php echo esc_html( va_t( 'services_intro' ) ); ?></p>
	</div>
</section>

<section class="va-block">
	<div class="va-container">
		<?php if ( have_posts() ) : ?>
			<div class="va-service-grid">
				<?php
				$number = 0;
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/service-card', null, array( 'number' => ++$number ) );
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
