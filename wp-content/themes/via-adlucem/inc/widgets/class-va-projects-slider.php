<?php
/**
 * Draggable row of project cards with arrows (Archicon "portfolio grid slider" style).
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

class VA_Projects_Slider_Widget extends Widget_Base {

	public function get_name() {
		return 'va-projects-slider';
	}

	public function get_title() {
		return 'VA Projects Slider';
	}

	public function get_icon() {
		return 'eicon-posts-carousel';
	}

	public function get_categories() {
		return array( 'via-adlucem' );
	}

	public function get_keywords() {
		return array( 'projects', 'portfolio', 'carousel', 'via adlucem' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_projects', array( 'label' => 'Projects' ) );

		$repeater = new Repeater();
		$repeater->add_control(
			'image',
			array(
				'label'   => 'Image',
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
			)
		);
		$repeater->add_control(
			'title',
			array(
				'label'       => 'Title',
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => 'Project title',
			)
		);
		$repeater->add_control(
			'category',
			array(
				'label' => 'Category / location',
				'type'  => Controls_Manager::TEXT,
			)
		);
		$repeater->add_control(
			'link',
			array(
				'label' => 'Link',
				'type'  => Controls_Manager::URL,
			)
		);

		$this->add_control(
			'projects',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array( array( 'title' => 'Project title' ) ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$projects = $this->get_settings_for_display( 'projects' );
		if ( ! $projects ) {
			return;
		}
		?>
		<div class="va-projects">
			<div class="va-projects__track" tabindex="0">
				<?php foreach ( $projects as $project ) : ?>
					<?php
					$url = $project['link']['url'] ?? '';
					$tag = $url ? 'a' : 'div';
					?>
					<<?php echo $tag; // phpcs:ignore ?> class="va-project"<?php echo $url ? ' href="' . esc_url( $url ) . '"' : ''; ?>>
						<span class="va-project__media"><img src="<?php echo esc_url( $project['image']['url'] ?? '' ); ?>" alt="<?php echo esc_attr( $project['title'] ); ?>" loading="lazy" draggable="false"></span>
						<span class="va-project__meta">
							<?php if ( ! empty( $project['category'] ) ) : ?>
								<span class="va-project__cat"><?php echo esc_html( $project['category'] ); ?></span>
							<?php endif; ?>
							<span class="va-project__title"><?php echo esc_html( $project['title'] ); ?></span>
						</span>
					</<?php echo $tag; // phpcs:ignore ?>>
				<?php endforeach; ?>
			</div>
			<div class="va-projects__nav">
				<button type="button" class="va-projects__arrow va-projects__arrow--prev" aria-label="Previous"><span aria-hidden="true">&larr;</span></button>
				<button type="button" class="va-projects__arrow va-projects__arrow--next" aria-label="Next"><span aria-hidden="true">&rarr;</span></button>
			</div>
		</div>
		<?php
	}
}
