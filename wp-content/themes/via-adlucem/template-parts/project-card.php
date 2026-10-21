<?php
/**
 * Project card for the projects page: cover image with date badge, categories, title.
 */

defined( 'ABSPATH' ) || exit;
?>
<article class="va-pcard va-reveal">
	<a class="va-pcard__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'large', array( 'alt' => '', 'loading' => 'lazy' ) );
		}
		va_date_badge();
		?>
	</a>
	<?php va_project_categories( 'va-pcard__cats' ); ?>
	<h2 class="va-pcard__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
</article>
