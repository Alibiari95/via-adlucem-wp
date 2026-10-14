<?php
/**
 * Site footer: call to action, contact columns, menu, copyright.
 */

defined( 'ABSPATH' ) || exit;
?>
</main>

<footer class="va-footer">
	<div class="va-footer__cta">
		<div class="va-container">
			<div class="va-footer__cta-copy">
				<p class="va-footer__cta-title"><?php echo esc_html( va_t( 'footer_cta' ) ); ?></p>
				<p class="va-footer__cta-text"><?php echo esc_html( va_t( 'footer_cta_text' ) ); ?></p>
			</div>
			<a class="va-btn va-btn--accent" href="<?php echo esc_url( va_page_url_by_role( 'contact' ) ); ?>"><?php echo esc_html( va_t( 'footer_button' ) ); ?></a>
		</div>
	</div>

	<div class="va-container va-footer__grid">
		<div class="va-footer__col va-footer__col--brand">
			<?php va_logo( 'light' ); ?>
			<p><?php echo esc_html( va_t( 'footer_about' ) ); ?></p>
		</div>

		<?php foreach ( va_contact_groups() as $group ) : ?>
			<div class="va-footer__col">
				<h2 class="va-footer__title"><?php echo esc_html( $group['title'] ); ?></h2>
				<?php echo $group['html']; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in va_contact_groups(). ?>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="va-container va-footer__bottom">
		<p><?php echo esc_html( str_replace( '%s', gmdate( 'Y' ), va_t( 'copyright' ) ) ); ?></p>
		<a class="va-footer__top" href="#va-header"><?php echo esc_html( va_t( 'back_to_top' ) ); ?> &uarr;</a>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
