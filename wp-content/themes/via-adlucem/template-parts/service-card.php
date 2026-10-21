<?php
/**
 * Service card used on the services archive and under each service ("Other services").
 *
 * @var array $args { @type int $number Position shown as 01, 02… }
 */

defined( 'ABSPATH' ) || exit;

$number = (int) ( $args['number'] ?? 0 );
?>
<a class="va-service-card va-reveal" href="<?php the_permalink(); ?>">
	<span class="va-service-card__media">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'large', array( 'alt' => '', 'loading' => 'lazy' ) );
		}
		?>
	</span>
	<span class="va-service-card__body">
		<?php if ( $number ) : ?>
			<span class="va-service-card__num"><?php echo esc_html( sprintf( '%02d', $number ) ); ?></span>
		<?php endif; ?>
		<span class="va-service-card__title"><?php the_title(); ?></span>
		<?php if ( has_excerpt() ) : ?>
			<span class="va-service-card__text"><?php echo esc_html( get_the_excerpt() ); ?></span>
		<?php endif; ?>
		<span class="va-service-card__more"><?php echo esc_html( va_t( 'learn_more' ) ); ?> <span aria-hidden="true">&rarr;</span></span>
	</span>
</a>
