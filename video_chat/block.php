<?php
/**
 *
 * @package KiLiveVideoConferences/Block
 */

namespace KiLiveVideoConferences;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


class Block {

	private static $_instance = null;

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 */
	public static function getInstance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * PostType constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	private function __construct() {

		add_action( 'init', array( $this, 'render_block' ) );

		add_action( 'enqueue_block_editor_assets', array( $this, 'assets_block' ) );

		add_action( 'wp_ajax_ki_live_video_conferences_block', array( $this, 'get_shortcode' ) );

	}

	public function assets_block() {
		wp_enqueue_script(
			'ki-live-video-conferences-block',
			KI_VC_URL . 'assets/js/block.js',
			array( 'wp-blocks', 'wp-editor' ),
			'1.0.0',
			true
		);
		wp_localize_script( 'ki-live-video-conferences-block', 'ki_live_video_conferences_block', array(
			'post_type' => Settings::get_service() . '_video'
		) );
	}


	public function get_shortcode( $attributes = array(), $return = false ) {

		if ( ! is_array( $attributes ) ) {
			$attributes = array();
		}

		if ( ! empty( $_POST['meeting_id'] ) ) {
			$attributes['meeting_id'] = sanitize_text_field( $_POST['meeting_id'] );
		}

		if ( ! empty( $_POST['type'] ) && ! empty( $_POST['latest'] ) && $_POST['latest'] !== 'false' ) {
			$attributes['latest'] = true;
			$attributes['type']   = sanitize_text_field( $_POST['type'] );
		}


		if ( ! empty( $attributes['type'] ) && ! empty( $attributes['latest'] ) && $attributes['latest'] !== 'false' ) {

			$latest_meeting = get_posts( 'post_type=' . esc_attr( $attributes['type'] ) . '&numberposts=1' );

			if ( ! empty( $latest_meeting[0] ) && ! empty( $latest_meeting[0]->ID ) ) {
				$attributes['meeting_id'] = $latest_meeting[0]->ID;
			}

		}


		if ( empty( $attributes['meeting_id'] ) ) {
			return '';
		}

		?>
        <style>
            .ki-live-video-block-editor {
                position: relative;
            }

            .ki-live-video-block-editor .block-overlay {
                position: absolute;
                width: 100%;
                height: 100%;
                background: rgba(85, 93, 102, 0.4);
            }
        </style>
        <div class="ki-live-video-block-editor">
            <div class="block-overlay"></div>
			<?php
			if ( ! empty( $attributes['meeting_id'] ) ) {
				echo do_shortcode( '[ki_video_chat meeting_id="' . esc_attr( $attributes['meeting_id'] ) . '"]' );
			}
			?>
        </div>

		<?php

		wp_die();
	}


	function render_callback( $block_attributes ) {

		return $this->get_shortcode( $block_attributes, true );

	}

	function render_block() {

		wp_enqueue_script(
			'ki-live-video-conferences-block',
			KI_VC_URL . 'assets/js/block.js',
			array( 'wp-blocks', 'wp-editor', 'wp-i18n' ),
			KI_VC_BASE_VERSION,
			true
		);
		wp_localize_script( 'ki-live-video-conferences-block', 'ki_live_video_conferences_block', array(
			'post_type' => Settings::get_service() . '_video'
		) );

		register_block_type( 'ki-live-video-conferences/block', array(
			'editor_script'   => 'ki-live-video-conferences-block',
			'render_callback' => array( $this, 'render_callback' )
		) );

	}


}

Block::getInstance();
