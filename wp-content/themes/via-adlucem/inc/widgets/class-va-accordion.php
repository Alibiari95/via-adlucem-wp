<?php
/**
 * Numbered accordion with +/− toggles (Archicon services list style).
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

class VA_Accordion_Widget extends Widget_Base {

	public function get_name() {
		return 'va-accordion';
	}

	public function get_title() {
		return 'VA Accordion';
	}

	public function get_icon() {
		return 'eicon-accordion';
	}

	public function get_categories() {
		return array( 'via-adlucem' );
	}

	public function get_keywords() {
		return array( 'accordion', 'faq', 'services', 'via adlucem' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_items', array( 'label' => 'Items' ) );

		$repeater = new Repeater();
		$repeater->add_control(
			'title',
			array(
				'label'       => 'Title',
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => 'Item title',
			)
		);
		$repeater->add_control(
			'content',
			array(
				'label'   => 'Content',
				'type'    => Controls_Manager::WYSIWYG,
				'default' => 'Item content.',
			)
		);

		$this->add_control(
			'items',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array( array( 'title' => 'Item title' ) ),
			)
		);

		$this->add_control(
			'show_numbers',
			array(
				'label'        => 'Show numbers',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = $s['items'] ?? array();
		$uid   = $this->get_id();
		?>
		<div class="va-accordion">
			<?php foreach ( $items as $i => $item ) : ?>
				<?php $open = 0 === $i; ?>
				<div class="va-accordion__item<?php echo $open ? ' is-open' : ''; ?>">
					<h3 class="va-accordion__heading">
						<button type="button" class="va-accordion__toggle" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>" aria-controls="va-acc-<?php echo esc_attr( $uid . '-' . $i ); ?>">
							<?php if ( 'yes' === $s['show_numbers'] ) : ?>
								<span class="va-accordion__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
							<?php endif; ?>
							<span class="va-accordion__title"><?php echo esc_html( $item['title'] ); ?></span>
							<span class="va-accordion__icon" aria-hidden="true"></span>
						</button>
					</h3>
					<div class="va-accordion__panel" id="va-acc-<?php echo esc_attr( $uid . '-' . $i ); ?>"<?php echo $open ? '' : ' hidden'; ?>>
						<div class="va-accordion__body"><?php echo wp_kses_post( $item['content'] ); ?></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
