<?php
namespace KiLiveVideoConferences;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
$um_forms=$this->get_um_forms();
?>
<div class="ki-live-video-conferences">
	<div class="ki-row">
		<a href="/account/" class="ki-but"><?php esc_html_e( '&#9668;', 'ki-live-video-conferences' );?></a>

		<a  href="#add_patient" class="ki-but show_form_add_patient"><?php esc_html_e( 'New Patient', 'ki-live-video-conferences' );?></a>
		<div class="form-add-patient" style="display: none;">
			<?php echo do_shortcode($um_forms['shortcod_patient']);?>
	
		</div>
	</div>
</div>



