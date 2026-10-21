<?php
/**
 * All projects (/projects/) and the category / tag pages: breadcrumb, category
 * filter and a 3-column grid of project cards (Archicon "blog compact" style).
 */

defined( 'ABSPATH' ) || exit;

get_header();

$archive_url = get_post_type_archive_link( 'va_project' );
$current     = is_tax( array( 'va_project_category', 'va_project_tag' ) ) ? get_queried_object() : null;
$categories  = get_terms(
	array(
		'taxonomy'   => 'va_project_category',
		'hide_empty' => true,
	)
);
?>
<section class="va-projects-page">
	<div class="va-container">
		<?php
		va_breadcrumb(
			$current
				? array( array( va_t( 'projects' ), $archive_url ), array( $current->name, null ) )
				: array( array( va_t( 'projects' ), null ) )
		);
		?>
		<h1 class="va-projects-page__title"><?php echo esc_html( $current ? $current->name : va_t( 'projects' ) ); ?></h1>

		<?php if ( $categories && ! is_wp_error( $categories ) && count( $categories ) > 1 ) : ?>
			<nav class="va-filter" aria-label="<?php echo esc_attr( va_t( 'projects' ) ); ?>">
				<a class="va-filter__item<?php echo $current ? '' : ' is-active'; ?>" href="<?php echo esc_url( $archive_url ); ?>"<?php echo $current ? '' : ' aria-current="page"'; ?>><?php echo esc_html( va_t( 'all' ) ); ?></a>
				<?php foreach ( $categories as $category ) : ?>
					<?php $active = $current && $current->term_id === $category->term_id; ?>
					<a class="va-filter__item<?php echo $active ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_term_link( $category ) ); ?>"<?php echo $active ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $category->name ); ?></a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="va-pgrid">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/project-card' );
				}
				?>
			</div>
			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
					'class'     => 'va-pagination',
				)
			);
			?>
		<?php else : ?>
			<p class="va-projects-page__empty"><?php echo esc_html( va_t( 'no_projects' ) ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
