<?php
/**
 * Showup Loader Elementor widget.
 *
 * @package ElementorShowupLoader
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

class ESL_Showup_Loader_Widget extends Widget_Base {

	public function get_name() {
		return 'showup-loader';
	}

	public function get_title() {
		return esc_html__( 'Showup Loader', 'elementor-showup-loader' );
	}

	public function get_icon() {
		return 'eicon-loading';
	}

	public function get_categories() {
		return array( 'general' );
	}

	public function get_keywords() {
		return array( 'loader', 'loading', 'gsap', 'animation', 'showup' );
	}

	public function get_style_depends() {
		return array( 'esl-showup-loader' );
	}

	public function get_script_depends() {
		return array( 'esl-showup-loader' );
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_image_controls();
		$this->register_style_controls();
		$this->register_animation_controls();
	}

	private function register_content_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Content', 'elementor-showup-loader' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'loading_text',
			array(
				'label'       => esc_html__( 'Main Loading Text', 'elementor-showup-loader' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Showup', 'elementor-showup-loader' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'final_title',
			array(
				'label'       => esc_html__( 'Final Text', 'elementor-showup-loader' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Showup', 'elementor-showup-loader' ),
				'label_block' => true,
			)
		);

		$this->end_controls_section();
	}

	private function register_image_controls() {
		$placeholder_image = Utils::get_placeholder_image_src();

		$this->start_controls_section(
			'image_section',
			array(
				'label' => esc_html__( 'Animation Images', 'elementor-showup-loader' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$image_repeater = new Repeater();
		$image_repeater->add_control(
			'image_label',
			array(
				'label'       => esc_html__( 'Image Label', 'elementor-showup-loader' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Image', 'elementor-showup-loader' ),
				'label_block' => true,
			)
		);
		$image_repeater->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Image', 'elementor-showup-loader' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => $placeholder_image,
				),
			)
		);
		$image_repeater->add_control(
			'alt_text',
			array(
				'label'       => esc_html__( 'Alt Text', 'elementor-showup-loader' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);

		$this->add_control(
			'animation_images',
			array(
				'label'       => esc_html__( 'Images', 'elementor-showup-loader' ),
				'description' => esc_html__( 'Images play in order. The last image remains visible when the effect finishes.', 'elementor-showup-loader' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $image_repeater->get_controls(),
				'title_field' => '{{{ image_label || "Image" }}}',
				'default'     => array(
					array(
						'image_label' => esc_html__( 'Image 1', 'elementor-showup-loader' ),
						'image'       => array( 'url' => $placeholder_image ),
					),
					array(
						'image_label' => esc_html__( 'Image 2', 'elementor-showup-loader' ),
						'image'       => array( 'url' => $placeholder_image ),
					),
					array(
						'image_label' => esc_html__( 'Image 3', 'elementor-showup-loader' ),
						'image'       => array( 'url' => $placeholder_image ),
					),
					array(
						'image_label' => esc_html__( 'Image 4', 'elementor-showup-loader' ),
						'image'       => array( 'url' => $placeholder_image ),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	private function register_style_controls() {
		$this->start_controls_section(
			'style_section',
			array(
				'label' => esc_html__( 'Showup Loader', 'elementor-willem-loader' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'elementor-willem-loader' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#E8E8A2',
				'selectors' => array( '{{WRAPPER}} .esl-showup-header' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'elementor-willem-loader' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f4f4f4',
				'selectors' => array( '{{WRAPPER}} .esl-showup-header' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'loader_text_color',
			array(
				'label'     => esc_html__( 'Loader Text Color', 'elementor-willem-loader' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#201d1d',
				'selectors' => array( '{{WRAPPER}} .esl-showup-loader' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'final_text_color',
			array(
				'label'     => esc_html__( 'Final Text Color', 'elementor-willem-loader' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f4f4f4',
				'selectors' => array( '{{WRAPPER}} .esl-showup-header__bottom .esl-showup__h1' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'final_text_position',
			array(
				'label'   => esc_html__( 'Final Text Position', 'elementor-willem-loader' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'center' => esc_html__( 'Center', 'elementor-willem-loader' ),
					'left'   => esc_html__( 'Left', 'elementor-willem-loader' ),
					'right'  => esc_html__( 'Right', 'elementor-willem-loader' ),
					'bottom' => esc_html__( 'Bottom', 'elementor-willem-loader' ),
				),
			)
		);

		$this->add_responsive_control(
			'main_text_alignment',
			array(
				'label'     => esc_html__( 'Main Text Alignment', 'elementor-willem-loader' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'elementor-willem-loader' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'elementor-willem-loader' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'elementor-willem-loader' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'selectors' => array(
					'{{WRAPPER}} .esl-showup-loader' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'final_text_alignment',
			array(
				'label'     => esc_html__( 'Final Text Alignment', 'elementor-willem-loader' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'elementor-willem-loader' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'elementor-willem-loader' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'elementor-willem-loader' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'selectors' => array(
					'{{WRAPPER}} .esl-showup-header__bottom' => 'width: 100%; justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'label'    => esc_html__( 'Heading Typography', 'elementor-willem-loader' ),
				'selector' => '{{WRAPPER}} .esl-showup__h1',
				'exclude'  => array( 'font_size' ),
			)
		);

		$this->add_responsive_control(
			'heading_font_size',
			array(
				'label'      => esc_html__( 'Responsive Heading Size', 'elementor-willem-loader' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'vw' ),
				'range'      => array(
					'px' => array( 'min' => 24, 'max' => 300 ),
					'em' => array( 'min' => 2, 'max' => 20, 'step' => 0.1 ),
					'vw' => array( 'min' => 5, 'max' => 30, 'step' => 0.1 ),
				),
				'default'    => array( 'unit' => 'em', 'size' => 12.5 ),
				'tablet_default' => array( 'unit' => 'em', 'size' => 9 ),
				'mobile_default' => array( 'unit' => 'em', 'size' => 5.5 ),
				'selectors'  => array( '{{WRAPPER}}' => '--esl-heading-font-size: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'section_height',
			array(
				'label'      => esc_html__( 'Section Height', 'elementor-willem-loader' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh', 'dvh' ),
				'range'      => array(
					'px'  => array( 'min' => 300, 'max' => 1600 ),
					'vh'  => array( 'min' => 40, 'max' => 120 ),
					'dvh' => array( 'min' => 40, 'max' => 120 ),
				),
				'default'    => array( 'unit' => 'dvh', 'size' => 100 ),
				'selectors'  => array( '{{WRAPPER}} .esl-showup-header, {{WRAPPER}} .esl-showup-header__content' => 'min-height: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'section_padding',
			array(
				'label'      => esc_html__( 'Padding', 'elementor-willem-loader' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top' => 3, 'right' => 3, 'bottom' => 3, 'left' => 3,
					'unit' => 'em', 'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .esl-showup-header__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function register_animation_controls() {
		$this->start_controls_section(
			'animation_section',
			array(
				'label' => esc_html__( 'Animation', 'elementor-willem-loader' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'enable_animation',
			array(
				'label'        => esc_html__( 'Enable Loading Animation', 'elementor-willem-loader' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'elementor-willem-loader' ),
				'label_off'    => esc_html__( 'No', 'elementor-willem-loader' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'animation_duration',
			array(
				'label'      => esc_html__( 'Animation Duration', 'elementor-willem-loader' ),
				'type'       => Controls_Manager::NUMBER,
				'min'        => 0.1,
				'max'        => 10,
				'step'       => 0.05,
				'default'    => 2,
				'condition'  => array( 'enable_animation' => 'yes' ),
			)
		);

		$this->add_control(
			'letter_stagger',
			array(
				'label'      => esc_html__( 'Letter Stagger Speed', 'elementor-willem-loader' ),
				'type'       => Controls_Manager::NUMBER,
				'min'        => 0,
				'max'        => 1,
				'step'       => 0.005,
				'default'    => 0.08,
				'condition'  => array( 'enable_animation' => 'yes' ),
			)
		);

		$this->add_control(
			'image_grow_duration',
			array(
				'label'      => esc_html__( 'Image Grow Duration', 'elementor-willem-loader' ),
				'type'       => Controls_Manager::NUMBER,
				'min'        => 0.1,
				'max'        => 10,
				'step'       => 0.05,
				'default'    => 6,
				'condition'  => array( 'enable_animation' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Split text into Unicode characters.
	 *
	 * @param string $text Text to split.
	 * @return array
	 */
	private function split_characters( $text ) {
		$characters = preg_split( '//u', $text, -1, PREG_SPLIT_NO_EMPTY );
		return is_array( $characters ) ? $characters : str_split( $text );
	}

	/**
	 * Render characters as animation spans.
	 *
	 * @param array  $characters Characters to render.
	 * @param string $class Span class.
	 */
	private function render_letters( $characters, $class ) {
		foreach ( $characters as $character ) {
			$is_space = preg_match( '/^\s$/u', $character );
			printf(
				'<span class="%1$s%2$s">%3$s</span>',
				esc_attr( $class ),
				$is_space ? ' is--space' : '',
				$is_space ? '&nbsp;' : esc_html( $character )
			);
		}
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$loading_text = '' !== trim( $settings['loading_text'] ) ? $settings['loading_text'] : 'Showup';
		$loading_chars = $this->split_characters( $loading_text );
		$split_at = (int) ceil( count( $loading_chars ) / 2 );
		$start_chars = array_slice( $loading_chars, 0, $split_at );
		$end_chars = array_slice( $loading_chars, $split_at );
		$final_chars = $this->split_characters( $settings['final_title'] );
		$longest_text_length = max( 1, count( $loading_chars ), count( $final_chars ) );
		$fit_font_size = min( 20, max( 2, 90 / $longest_text_length ) );
		$images = ! empty( $settings['animation_images'] ) ? $settings['animation_images'] : array();
		$main_image = array_pop( $images );
		$wrapper_id = 'esl-showup-' . $this->get_id();
		$animation_enabled = 'yes' === $settings['enable_animation'];
		$allowed_positions = array( 'center', 'left', 'right', 'bottom' );
		$saved_position = $settings['final_text_position'] ?? 'center';
		$final_text_position = in_array( $saved_position, $allowed_positions, true )
			? $saved_position
			: 'center';
		$config = array(
			'enabled'      => $animation_enabled,
			'duration'     => max( 0.1, (float) $settings['animation_duration'] ),
			'stagger'      => max( 0, (float) $settings['letter_stagger'] ),
			'imageDuration'=> max( 0.1, (float) $settings['image_grow_duration'] ),
		);
		?>
		<section
			id="<?php echo esc_attr( $wrapper_id ); ?>"
			class="esl-showup-header esl-final-position--<?php echo esc_attr( $final_text_position ); ?> is--hidden<?php echo $animation_enabled ? ' is--loading' : ' is-animation-disabled'; ?>"
			data-esl-config="<?php echo esc_attr( wp_json_encode( $config ) ); ?>"
			style="--esl-fit-font-size-vw: <?php echo esc_attr( $fit_font_size ); ?>vw; --esl-fit-font-size-cqw: <?php echo esc_attr( $fit_font_size ); ?>cqw;"
		>
			<div class="esl-showup-loader" aria-hidden="true">
				<div class="esl-showup__h1">
					<div class="esl-showup__h1-start">
						<?php $this->render_letters( $start_chars, 'esl-showup__letter' ); ?>
					</div>
					<div class="esl-showup-loader__box">
						<div class="esl-showup-loader__box-inner">
							<div class="esl-showup__growing-image">
								<div class="esl-showup__growing-image-wrap">
									<?php foreach ( $images as $index => $image_item ) : ?>
										<?php
										$image_url = ! empty( $image_item['image']['url'] ) ? $image_item['image']['url'] : '';
										if ( ! $image_url ) {
											continue;
										}
										?>
										<img
											class="esl-showup__cover-image-extra"
											src="<?php echo esc_url( $image_url ); ?>"
											alt="<?php echo esc_attr( $image_item['alt_text'] ?? '' ); ?>"
											loading="eager"
											style="z-index: <?php echo esc_attr( count( $images ) - $index + 1 ); ?>;"
										>
									<?php endforeach; ?>
									<?php if ( ! empty( $main_image['image']['url'] ) ) : ?>
										<img
											class="esl-showup__cover-image"
											src="<?php echo esc_url( $main_image['image']['url'] ); ?>"
											alt="<?php echo esc_attr( $main_image['alt_text'] ?? '' ); ?>"
											loading="eager"
										>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
					<div class="esl-showup__h1-end">
						<?php $this->render_letters( $end_chars, 'esl-showup__letter' ); ?>
					</div>
				</div>
			</div>

			<div class="esl-showup-header__content">
				<div class="esl-showup-header__bottom">
					<div class="esl-showup__h1" aria-label="<?php echo esc_attr( $settings['final_title'] ); ?>">
						<?php $this->render_letters( $final_chars, 'esl-showup__letter-white' ); ?>
					</div>
				</div>
			</div>
			<button class="esl-showup-replay" type="button" aria-label="<?php echo esc_attr__( 'Replay loading animation', 'elementor-showup-loader' ); ?>">
				<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
					<path d="M20 11a8 8 0 1 0-2.34 5.66M20 4v7h-7"></path>
				</svg>
			</button>
		</section>
		<?php
	}
}
