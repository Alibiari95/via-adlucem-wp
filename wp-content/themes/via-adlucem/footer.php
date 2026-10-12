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
			<p class="va-footer__cta-title"><?php echo esc_html( va_t( 'footer_cta' ) ); ?></p>
			<a class="va-btn va-btn--accent" href="<?php echo esc_url( va_page_url_by_role( 'contact' ) ); ?>"><?php echo esc_html( va_t( 'footer_button' ) ); ?></a>
		</div>
	</div>

	<div class="va-container va-footer__grid">
		<div class="va-footer__col va-footer__col--brand">
			<?php va_logo( 'light' ); ?>
			<p><?php echo esc_html( va_t( 'footer_about' ) ); ?></p>
		</div>

		<div class="va-footer__col">
			<h2 class="va-footer__title"><?php echo esc_html( va_t( 'footer_visit' ) ); ?></h2>
			<p><?php echo esc_html( va_t( 'address' ) ); ?></p>
		</div>

		<div class="va-footer__col">
			<h2 class="va-footer__title"><?php echo esc_html( va_t( 'footer_talk' ) ); ?></h2>
			<p>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', va_t( 'phone' ) ) ); ?>"><?php echo esc_html( va_t( 'phone' ) ); ?></a><br>
				<a href="mailto:<?php echo esc_attr( va_t( 'email' ) ); ?>"><?php echo esc_html( va_t( 'email' ) ); ?></a>
			</p>
		</div>

		<?php if ( has_nav_menu( 'va-footer' ) ) : ?>
			<div class="va-footer__col">
				<h2 class="va-footer__title"><?php echo esc_html( va_t( 'footer_nav' ) ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'va-footer',
						'container'      => false,
						'menu_class'     => 'va-footer__menu',
						'depth'          => 1,
					)
				);
				?>
			</div>
		<?php endif; ?>
	</div>

	<div class="va-container va-footer__bottom">
		<p><?php echo esc_html( str_replace( '%s', gmdate( 'Y' ), va_t( 'copyright' ) ) ); ?></p>
		<?php va_social_links(); ?>
		<a class="va-footer__top" href="#va-header"><?php echo esc_html( va_t( 'back_to_top' ) ); ?> &uarr;</a>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
