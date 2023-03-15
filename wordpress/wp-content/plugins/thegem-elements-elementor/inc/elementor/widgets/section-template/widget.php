<?php

namespace TheGem_Elementor\Widgets\Template;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes;

if (!defined('ABSPATH')) exit;


/**
 * Elementor widget for Section Template.
 */
class TheGem_Template extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'thegem-template';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __('Section Template', 'thegem');
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return str_replace('thegem-', 'thegem-eicon thegem-eicon-', $this->get_name());
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return ['thegem_elements'];
	}

	/**
	 * Register the widget controls.
	 *
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_template',
			[
				'label' => esc_html__( 'Template', 'thegem' ),
			]
		);

		$this->add_control(
			'template_id',
			[
				'label' => esc_html__( 'Choose Template', 'thegem' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'options' => thegem_get_section_templates_list(),
			]
		);

		$this->end_controls_section();

	}



	protected function render() {
		$template_id = $this->get_settings( 'template_id' );
		$template_id = intval($template_id);
		if($template_id > 0 && $template = get_post($template_id)) {
			$return_html = Plugin::instance()->frontend->get_builder_content_for_display( $template_id );
			if(\Elementor\Plugin::$instance->preview->is_preview_mode()) {
				$template_link = add_query_arg(array('post' => $template_id, 'action' => 'elementor'), admin_url( 'post.php' ));
				$button = '<a class="thegem-template-edit-button" href="'.$template_link.'" target="_blank">'.esc_html__('Edit Template ', 'thegem').'</a>';
				$return_html = $button.$return_html;
			}
			$return_html = '<div class="thegem-template-wrapper thegem-template-content thegem-template-' . esc_attr($template_id) . '">' . $return_html . '</div>';
		}
	
		echo $return_html;
	}
}

\Elementor\Plugin::instance()->widgets_manager->register(new TheGem_Template());