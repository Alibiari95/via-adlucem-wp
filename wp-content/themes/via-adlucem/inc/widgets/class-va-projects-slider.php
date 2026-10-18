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

		$this->add_control(
			'source',
			array(
				'label'   => 'Show',
				'type'    => Controls_Manager::SELECT,
				'default' => 'latest',
				'options' => array(
					'latest' => 'Latest projects (automatic)',
					'manual' => 'Hand-picked items (below)',
				),
			)
		);
		$this->add_control(
			'count',
			array(
				'label'     => 'Number of projects',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 6,
				'min'       => 1,
				'max'       => 12,
				'condition' => array( 'source' => 'latest' ),
			)
		);

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
				'condition'   => array( 'source' => 'manual' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Cards as array( image, title, category, url ): the newest projects in the current
	 * language, or the hand-picked repeater items.
	 */
	private function get_project_items( $settings ) {
		if ( 'manual' !== ( $settings['source'] ?? 'latest' ) && post_type_exists( 'va_project' ) ) {
			$posts = get_posts(
				array(
					'post_type'        => 'va_project',
					'numberposts'      => max( 1, (int) ( $settings['count'] ?? 6 ) ),
					'orderby'          => 'date',
					'order'            => 'DESC',
					'suppress_filters' => false, // Lets Polylang pick the current language.
				)
			);
			$items = array();
			foreach ( $posts as $post ) {
				$terms   = get_the_terms( $post, 'va_project_category' );
				$items[] = array(
					'image'    => get_the_post_thumbnail_url( $post, 'large' ),
					'title'    => get_the_title( $post ),
					'category' => $terms && ! is_wp_error( $terms ) ? $terms[0]->name : '',
					'url'      => get_permalink( $post ),
				);
			}
			if ( $items ) {
				return $items;
			}
		}
		return array_map(
			fn( $p ) => array(
				'image'    => $p['image']['url'] ?? '',
				'title'    => $p['title'],
				'category' => $p['category'] ?? '',
				'url'      => $p['link']['url'] ?? '',
			),
			(array) ( $settings['projects'] ?? array() )
		);
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = $this->get_project_items( $settings );
		if ( ! $items ) {
			return;
		}
		$archive = 'manual' !== ( $settings['source'] ?? 'latest' ) && post_type_exists( 'va_project' ) ? get_post_type_archive_link( 'va_project' ) : '';
		?>
		<div class="va-projects">
			<div class="va-projects__track" tabindex="0">
				<?php foreach ( $items as $item ) : ?>
					<?php $tag = $item['url'] ? 'a' : 'div'; ?>
					<<?php echo $tag; // phpcs:ignore ?> class="va-project"<?php echo $item['url'] ? ' href="' . esc_url( $item['url'] ) . '"' : ''; ?>>
						<span class="va-project__media">
							<?php if ( $item['image'] ) : ?>
								<img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy" draggable="false">
							<?php endif; ?>
						</span>
						<span class="va-project__meta">
							<?php if ( $item['category'] ) : ?>
								<span class="va-project__cat"><?php echo esc_html( $item['category'] ); ?></span>
							<?php endif; ?>
							<span class="va-project__title"><?php echo esc_html( $item['title'] ); ?></span>
						</span>
					</<?php echo $tag; // phpcs:ignore ?>>
				<?php endforeach; ?>
			</div>
			<div class="va-projects__nav">
				<button type="button" class="va-projects__arrow va-projects__arrow--prev" aria-label="Previous"><span aria-hidden="true">&larr;</span></button>
				<button type="button" class="va-projects__arrow va-projects__arrow--next" aria-label="Next"><span aria-hidden="true">&rarr;</span></button>
				<?php if ( $archive && function_exists( 'va_t' ) ) : ?>
					<a class="va-link va-projects__all" href="<?php echo esc_url( $archive ); ?>"><?php echo esc_html( va_t( 'all_projects' ) ); ?> <span aria-hidden="true">&rarr;</span></a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
