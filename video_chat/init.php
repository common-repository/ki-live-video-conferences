<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

define( 'KI_VC_URL', plugin_dir_url( __FILE__ ) );
define( 'KI_VC_DIR', plugin_dir_path( __FILE__ ) );


include dirname( __FILE__ ) . '/functions.php';
include dirname( __FILE__ ) . '/video_chat.php';
include dirname( __FILE__ ) . '/include_um.php';
include dirname( __FILE__ ) . '/settings.php';
include dirname( __FILE__ ) . '/zoom-api.php';
include dirname( __FILE__ ) . '/rainbow.php';
include dirname( __FILE__ ) . '/ajax.php';
include dirname( __FILE__ ) . '/shortcodes.php';
include dirname( __FILE__ ) . '/zoom-init.php';
include dirname( __FILE__ ) . '/post-type.php';
include dirname( __FILE__ ) . '/meta_boxes.php';
include dirname( __FILE__ ) . '/view.php';
include dirname( __FILE__ ) . '/block.php';


