<?php
/**
 * Project details (Archicon single-post style): wide cover with date badge,
 * categories, title, block-editor content, tags + share links, previous/next project.
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( get_the_title() );
	$share = array(
		'Facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
		'LinkedIn' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url,
		'X'        => 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title,
		'WhatsApp' => 'https://wa.me/?text=' . $title . '%20' . $url,
	);
	$prev = get_previous_post();
	$next = get_next_post();
	?>
	<article <?php post_class( 'va-project-single' ); ?>>
		<div class="va-container">
			<?php va_breadcrumb( array( array( va_t( 'projects' ), get_post_type_archive_link( 'va_project' ) ), array( get_the_title(), null ) ) ); ?>

			<figure class="va-project-single__cover">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'full', array( 'alt' => '' ) );
				}
				va_date_badge();
				?>
			</figure>

			<div class="va-project-single__body">
				<?php va_project_categories( 'va-project-single__cats' ); ?>
				<h1 class="va-project-single__title"><?php the_title(); ?></h1>

				<div class="va-entry">
					<?php the_content(); ?>
				</div>

				<footer class="va-project-single__footer">
					<?php
					$tags = get_the_term_list( get_the_ID(), 'va_project_tag', '<p class="va-project-single__tags">', ', ', '</p>' );
					echo $tags && ! is_wp_error( $tags ) ? $tags : '<span></span>'; // phpcs:ignore WordPress.Security.EscapeOutput -- core-escaped links.
					?>
					<p class="va-share">
						<span><?php echo esc_html( va_t( 'share' ) ); ?>:</span>
						<?php
						$links = array();
						foreach ( $share as $label => $href ) {
							$links[] = '<a href="' . esc_url( $href ) . '" target="_blank" rel="noopener">' . esc_html( $label ) . '</a>';
						}
						echo implode( ', ', $links ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
						?>
					</p>
				</footer>

				<?php if ( $prev || $next ) : ?>
					<nav class="va-post-nav" aria-label="<?php echo esc_attr( va_t( 'projects' ) ); ?>">
						<div class="va-post-nav__item va-post-nav__item--prev">
							<?php if ( $prev ) : ?>
								<a href="<?php echo esc_url( get_permalink( $prev ) ); ?>">
									<span class="va-post-nav__label"><span aria-hidden="true">&larr;</span> <?php echo esc_html( va_t( 'prev_project' ) ); ?></span>
									<span class="va-post-nav__title"><?php echo esc_html( get_the_title( $prev ) ); ?></span>
								</a>
							<?php endif; ?>
						</div>
						<div class="va-post-nav__item va-post-nav__item--next">
							<?php if ( $next ) : ?>
								<a href="<?php echo esc_url( get_permalink( $next ) ); ?>">
									<span class="va-post-nav__label"><?php echo esc_html( va_t( 'next_project' ) ); ?> <span aria-hidden="true">&rarr;</span></span>
									<span class="va-post-nav__title"><?php echo esc_html( get_the_title( $next ) ); ?></span>
								</a>
							<?php endif; ?>
						</div>
					</nav>
				<?php endif; ?>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
