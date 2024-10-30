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

			foreach($appointments as $appointment){
				$post_id=$appointment->ID;
				
				$RoomDoctors=get_post_meta( $appointment->ID,'doctors', true );
				if(empty($RoomDoctors))continue;
				$arrRoomDoctors=unserialize($RoomDoctors);
				$Doctor=$arrRoomDoctors[0];
				
				if(!empty(get_post_meta( $appointment->ID,'room', true )))continue;
			?>
			<div class="row-appointment">
				<div class="appointment-name"><?php echo $appointment->post_title?></div>
				<div class="room-buttons">
					<a href="/account/?ki_vc_room=add_room&doctor=<?php echo $Doctor;?>&appointment=<?php echo $post_id;?>" class="ki-but"><?php esc_html_e( 'Meeting requests', 'ki-live-video-conferences' );?></a>
				</div>
			</div>
			<?php
				
			}
		?>
	</div>
</div>



