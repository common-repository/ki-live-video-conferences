<?php
namespace KiLiveVideoConferences;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
$um_forms=$this->get_um_forms();



?>
<div class="ki-live-video-conferences">
	<h5><?php esc_html_e( 'Doctors and Meetings', 'ki-live-video-conferences' );?></h5>
	<!--<div class="ki-row">
		<a href="#doctors"  class="ki-but show-list-doctors"><?php esc_html_e( 'Doctors', 'ki-live-video-conferences' );?></a>
		<a href="/members/" class="ki-but"><?php esc_html_e( 'Patients', 'ki-live-video-conferences' );?></a>
	</div>-->
	<div class="ki-row list-doctors" >
		<!--<div  class="ki-but show_form_add_doctor"><?php esc_html_e( 'New Doctor', 'ki-live-video-conferences' );?></div>
		<div class="form-add-doctor" style="display: none;">
			<?php echo do_shortcode($um_forms['shortcod_doctor']);?>
		</div>-->
		<div class="doctors-list">
		<?php

		$doctors=get_users(Array('role'=>'doctor'));
		foreach($doctors as $doc){
			$assistant_id=get_user_meta($doc->ID, Settings::get_slug().'_assistant_id',true);
			if($assistant_id!=get_current_user_id())continue;
			?>
			<div class="doc-row">
				<div class="doc-head">
					<div class="doctor-name"><?php echo $doc->display_name;?></div>
					<a href="/user/<?php echo $doc->user_login;?>/">edit</a>
				</div>
				
				<div class="doctor-list-meetings">
				
					<a href="/account/?ki_vc_room=add_room&doctor=<?php echo $doc->ID;?>" class="ki-but"><?php esc_html_e( 'New Room', 'ki-live-video-conferences' );?></a>
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
					'post_type'   => 'zoom_video',
					'suppress_filters' => true
				);
				$meetings = get_posts($params);

				foreach($meetings as $meeting){
					$post_id=$meeting->ID;
					
					$RoomDoctors=get_post_meta( $meeting->ID,'doctors', true );
		
					$arrRoomDoctors=unserialize($RoomDoctors);
					if(is_array($arrRoomDoctors)==false)continue;

					if(array_search($doc->ID,$arrRoomDoctors)===false)continue;
					?>
					<div class="meeting-row">
						<div class="meeting-name"><?php echo $meeting->post_title?></div>
						<div class="room-buttons">
							<a href="/account/?ki_vc_room=<?php echo $post_id ;?>" >Edit</a>
							<a href="<?php the_permalink( $post_id ); ?>" target="_blank" >Show</a>
						</div>
					</div>
					<?php
				}
				
				?>
				</div>
			</div>
			<?php
		}
		?>
		</div>
	</div>

</div>



