<?php
/**
 * Full-screen hero slider (Archicon-style): big title, small kicker, button, slide counter.
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

class VA_Hero_Slider_Widget extends Widget_Base {

	public function get_name() {
		return 'va-hero-slider';
	}

	public function get_title() {
		return 'VA Hero Slider';
	}

	public function get_icon() {
		return 'eicon-slides';
	}

	public function get_categories() {
		return array( 'via-adlucem' );
	}

	public function get_keywords() {
		return array( 'hero', 'slider', 'slides', 'via adlucem' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_slides', array( 'label' => 'Slides' ) );

		$repeater = new Repeater();
		$repeater->add_control(
			'image',
			array(
				'label'   => 'Background image',
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
			)
		);
		$repeater->add_control(
			'kicker',
			array(
				'label'       => 'Small text above title',
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			)
		);
		$repeater->add_control(
			'title',
			array(
				'label'       => 'Title',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => 'Slide title',
			)
		);
		$repeater->add_control(
			'button_text',
			array(
				'label' => 'Button text',
				'type'  => Controls_Manager::TEXT,
			)
		);
		$repeater->add_control(
			'button_link',
			array(
				'label' => 'Button link',
				'type'  => Controls_Manager::URL,
			)
		);

		$this->add_control(
			'slides',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'title' => 'Slide title', 'kicker' => 'Kicker' ),
				),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'   => 'Autoplay (seconds, 0 = off)',
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 0,
				'max'     => 30,
			)
		);

		$this->add_control(
			'overlay',
			array(
				'label'      => 'Image darkening',
				'type'       => Controls_Manager::SLIDER,
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 0.9, 'step' => 0.05 ) ),
				'default'    => array( 'size' => 0.35 ),
				'selectors'  => array( '{{WRAPPER}} .va-hero' => '--va-hero-overlay: {{SIZE}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$slides = $s['slides'] ?? array();
		if ( ! $slides ) {
			return;
		}
		$total = count( $slides );
		?>
		<section class="va-hero" data-autoplay="<?php echo esc_attr( (int) $s['autoplay'] ); ?>">
			<div class="va-hero__slides">
				<?php foreach ( $slides as $i => $slide ) : ?>
					<div class="va-hero__slide<?php echo 0 === $i ? ' is-active' : ''; ?>" aria-hidden="<?php echo 0 === $i ? 'false' : 'true'; ?>">
						<div class="va-hero__media" style="background-image:url('<?php echo esc_url( $slide['image']['url'] ?? '' ); ?>')"></div>
						<div class="va-hero__content">
							<?php if ( ! empty( $slide['kicker'] ) ) : ?>
								<p class="va-hero__kicker"><?php echo esc_html( $slide['kicker'] ); ?></p>
							<?php endif; ?>
							<?php // First slide title is the page H1; the others are visually identical. ?>
							<<?php echo 0 === $i ? 'h1' : 'h2'; ?> class="va-hero__title"><?php echo nl2br( esc_html( $slide['title'] ) ); ?></<?php echo 0 === $i ? 'h1' : 'h2'; ?>>
							<?php if ( ! empty( $slide['button_text'] ) && ! empty( $slide['button_link']['url'] ) ) : ?>
								<a class="va-btn va-btn--light" href="<?php echo esc_url( $slide['button_link']['url'] ); ?>"<?php echo ! empty( $slide['button_link']['is_external'] ) ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $slide['button_text'] ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( $total > 1 ) : ?>
				<div class="va-hero__nav">
					<button type="button" class="va-hero__arrow va-hero__arrow--prev" aria-label="Previous slide"><span aria-hidden="true">&larr;</span></button>
					<p class="va-hero__count"><span class="va-hero__current">01</span><span class="va-hero__bar"><span></span></span><span class="va-hero__total"><?php echo esc_html( sprintf( '%02d', $total ) ); ?></span></p>
					<button type="button" class="va-hero__arrow va-hero__arrow--next" aria-label="Next slide"><span aria-hidden="true">&rarr;</span></button>
				</div>
			<?php endif; ?>
		</section>
		<?php
	}
}
