<?php
/**
 * Site header: logo, primary menu (with Polylang language switcher), mobile overlay.
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'via-adlucem' ); ?></a>

<header class="va-header" id="va-header">
	<div class="va-header__inner">
		<div class="va-header__brand">
			<?php va_logo( 'dark' ); ?>
			<?php va_logo( 'light' ); ?>
		</div>

		<?php if ( has_nav_menu( 'va-primary' ) ) : ?>
			<nav class="va-nav" aria-label="<?php esc_attr_e( 'Primary', 'via-adlucem' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'va-primary',
						'container'      => false,
						'menu_class'     => 'va-menu',
						'depth'          => 2,
						'link_before'    => '<span class="va-menu__text">',
						'link_after'     => '</span>',
					)
				);
				?>
			</nav>
		<?php endif; ?>

		<button type="button" class="va-burger" aria-expanded="false" aria-controls="va-mobile">
			<span class="va-burger__label"><?php echo esc_html( va_t( 'menu' ) ); ?></span>
			<span class="va-burger__lines" aria-hidden="true"><span></span><span></span></span>
		</button>
	</div>
</header>

<div class="va-mobile" id="va-mobile" hidden>
	<div class="va-mobile__inner">
		<?php
		if ( has_nav_menu( 'va-primary' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'va-primary',
					'container'      => 'nav',
					'menu_class'     => 'va-mobile__menu',
					'depth'          => 2,
				)
			);
		}
		?>
		<div class="va-mobile__contact">
			<a href="mailto:<?php echo esc_attr( va_t( 'email' ) ); ?>"><?php echo esc_html( va_t( 'email' ) ); ?></a>
			<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', va_t( 'phone' ) ) ); ?>"><?php echo esc_html( va_t( 'phone' ) ); ?></a>
		</div>
	</div>
</div>

<main id="content" class="va-main">
