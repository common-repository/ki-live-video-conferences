<?php
namespace KiLiveVideoConferences;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
$um_forms=$this->get_um_forms();



?>
<div class="ki-live-video-conferences">
		<div class="ki-row list-appointment" >
	
		<?php 
			$params=Array(
				'numberposts' => 100,
				'category'    => 0,
				'orderby'     => 'date',
				'order'       => 'DESC',
				'include'     => array(),
				'exclude'     => array(),
				'meta_key'    => '',
				'meta_value'  =>'',
				'post_type'   => 'ki_appointment',
				'suppress_filters' => true
			);
			$appointments = get_posts($params);
			if(count($appointments)>0){
			?>
				<div class="ki-row">
					<center><a href="/account/?ki_vc_view=meeting_request" class="ki-but"><?php esc_html_e( 'Create a meeting request', 'ki-live-video-conferences' );?></a></center>
				</div>
			
			<?php
				foreach($appointments as $appointment){
					$post_id=$appointment->ID;
					
					/*$RoomDoctors=get_post_meta( $appointment->ID,'teachers', true );
					$arrRoomDoctors=unserialize($RoomDoctors);
					$Doctor=$arrRoomDoctors[0];*/
					
					
					$patient_id=get_post_meta( $appointment->ID,'student', true );
					
					if(!empty(get_post_meta( $appointment->ID,'room', true )))continue;
					if($patient_id!=get_current_user_id())continue;
				?>
				<div class="row-appointment">
					<div class="appointment-name"><?php echo $appointment->post_title?></div>
					<div class="room-buttons">
						
					</div>
				</div>
				<?php
					
				}
			?>
			
			<?php
			}else{
				?>
				<div class="ki-row">
				<center><?php esc_html_e( "You don't have meetings yet", 'ki-live-video-conferences' );?></center>
				</div
				<div class="ki-row">
					<center><a href="/account/?ki_vc_view=meeting_request" class="ki-but"><?php esc_html_e( 'Create a meeting request', 'ki-live-video-conferences' );?></a></center>
				</div>
				<?php
			}
		?>
		</div>
	
</div>



