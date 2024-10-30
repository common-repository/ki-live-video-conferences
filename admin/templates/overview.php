<?php
namespace KiLiveVideoConferences;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
$role_member=KiFunctions::member();
$role_presenter=KiFunctions::presenter();
$Upcoming = array();
$Graphic = array();
$ChartLabel = array();
$ChartData = array();
for ( $i = 30; $i > 0; $i -- ) {
	$iTime             = strtotime( "-" . $i . " day" );
	$iDate             = date( 'Y-m-d', $iTime );
	$Graphic[ $iDate ] = 0;
    $ChartLabel[]=date( 'm-d', strtotime($iDate) );
}


$type_post = Settings::get_service() . '_video';
$params    = Array(
	'numberposts'      => 100,
	'category'         => 0,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'include'          => array(),
	'exclude'          => array(),
	'meta_key'         => '',
	'meta_value'       => '',
	'post_type'        => $type_post,
	'suppress_filters' => true
);
$meetings  = get_posts( $params );

$Calendar = Array();
foreach ( $meetings as $mPost ) {
	$start_time = get_post_meta( $mPost->ID, 'start_time', true );
	if ( $start_time == '' ) {
		$start_time = $mPost->post_date;
	}

	if ( strtotime( $start_time ) > time() ) {
		$Upcoming[ $mPost->ID ] = array( 'name' => $mPost->post_title, 'date' => strtotime( $start_time ) );
	} else {

		$day = date( 'Y-m-d', strtotime( $start_time ) );
		if ( empty( $Graphic[ $day ] ) ) {
			$Graphic[ $day ] = 0;
		}
		$Graphic[ $day ] ++;
	}
	$cTime = date( 'Y-m-d H:i:s', strtotime( $start_time ) );
	array_push( $Calendar, Array(
		'date'  => $cTime,
		'id'    => $mPost->ID,
		'title' => $mPost->post_title,
	) );

}

foreach ( $Graphic as $date => $val ) {
    if ( $val == 0 ) {
        $val = 'NaN';
    }
    $ChartData[]=$val;

}

ksort( $Calendar );


//KiFunctions::e_array($Graphic,false);

$calendar_params = array();
foreach ( $Calendar as $val ) {
	$url               = 'post.php?post=' . $val['id'] . '&action=edit';
	$calendar_params[] = array(
		'date'        => $val['date'],
		'title'       => $val['title'],
		'description' => 'Meeting',
		'url'         => $url,
	);
}


$frmLeading = Array();
$frmSlave   = Array();
if ( Settings::get_option( 'video_chat_select_purpose', 'medicine' ) == 'medicine' ) {
	$frmLeading['title']  = __( 'Doctor', 'ki-live-video-conferences' );
	$frmLeading['role']   = 'doctor';
	$frmLeading['select'] = 'doctor_id';

	$frmSlave['title']  = __( 'Patient', 'ki-live-video-conferences' );
	$frmSlave['role']   = 'patient';
	$frmSlave['select'] = 'patients';
} else {
	$frmLeading['title']  = __( 'Teacher', 'ki-live-video-conferences' );
	$frmLeading['role']   = 'teacher';
	$frmLeading['select'] = 'teacher_id';

	$frmSlave['title']  = __( 'Student', 'ki-live-video-conferences' );
	$frmSlave['role']   = 'student';
	$frmSlave['select'] = 'students';
}


?>
<div class="wrap page-ki-Overview">

    <h1><?php _e( 'Overview', 'ki-live-video-conferences' ); ?></h1>

    <div id="welcome-panel" class="welcome-panel">
        <input type="hidden" id="welcomepanelnonce" name="welcomepanelnonce" value="2e0abf62c1">
        <div class="welcome-panel-content">
            <h2><?php _e( 'Welcome to KI LIVE CONFERENCE', 'ki-live-video-conferences' ); ?></h2>
            <p class="about-description"><?php _e( 'We’ve assembled some links to get you started:', 'ki-live-video-conferences' ); ?></p>
            <div class="welcome-panel-column-container">
                <div class="welcome-panel-column">
                    <h3><?php _e( 'Get Started', 'ki-live-video-conferences' ); ?></h3>
                    <a class="button button-primary button-hero load-customize hide-if-no-customize"
                       href="<?php echo home_url( 'wp-admin/admin.php?page=ki_settings_page' ); ?>">
						<?php _e( 'Customize Your Plugin', 'ki-live-video-conferences' ); ?>
                    </a>
                </div>
                <div class="welcome-panel-column">
                    <h3><?php _e( 'Next Steps', 'ki-live-video-conferences' ); ?></h3>
                    <ul>

                        <li>
                            <a href="<?php echo home_url( 'wp-admin/post-new.php?post_type=' . $type_post ); ?>"
                               class="welcome-icon welcome-add-page">
								<?php _e( 'Add new meeting', 'ki-live-video-conferences' ); ?>
                            </a>
                        </li>

                    </ul>
                </div>
                <div class="welcome-panel-column">
                    <h3><?php _e( 'Audience', 'ki-live-video-conferences' ); ?></h3>
                    <ul>
                        <li>
                            <a href="<?php echo home_url( 'wp-admin/user-new.php#ki-role='.$role_member['role'] ); ?>"  class="welcome-icon welcome-add-page">
								<?php _e( 'New member', 'ki-live-video-conferences' ); ?>
                            </a>
                        </li>

                    </ul>
                </div>
				
                <img class="panel-logo" src="<?php echo esc_url( KI_VC_SETTINGS_URL . 'assets/images/plugin-icon-48.png' ); ?>">
                
            </div>
        </div>
    </div>

    <div id="dashboard-widgets-wrap">

        <div id="dashboard-widgets" class="metabox-holder">

            <div id="postbox-container" class="postbox-container">
                <div id="side-sortables" class="meta-box-sortables ui-sortable">
                    <div id="dashboard_quick_press" class="postbox ">
                        <button type="button" class="handlediv" aria-expanded="true">
                            <span class="screen-reader-text">
                                <?php _e( 'Toggle panel:', 'ki-live-video-conferences' ); ?>
                                <span class="hide-if-no-js">
                                    <?php _e( 'Quick Meeting', 'ki-live-video-conferences' ); ?>
                                </span>
                                <span class="hide-if-js">
                                    <?php _e( 'Your Recent Drafts', 'ki-live-video-conferences' ); ?>
                                </span>
                            </span>
                            <span class="toggle-indicator" aria-hidden="true"></span>
                        </button>
                        <h2 class="hndle ui-sortable-handle">
                            <span>
                                <span class="hide-if-no-js">
									<?php _e( 'Quick Meeting', 'ki-live-video-conferences' ); ?>
                                </span>
                                <span class="hide-if-js">
										<?php _e( 'Quick Meeting', 'ki-live-video-conferences' ); ?>
                                </span>
                            </span>
                        </h2>
                        <div class="inside QuickMeeting">
							<div class="form-quick-meeting">
                          
                                <table>
                                    <tr>
                                        <td><?php esc_attr_e( $frmLeading['title'], 'ki-live-video-conferences' ); ?>
                                        <td>
                                        <td>
                                            <label>
                                                <select name="<?php echo $frmLeading['select']; ?>" class="frm-input">
													<?php
													$patients = get_users( Array( 'role' => $frmLeading['role'] ) );
													foreach ( $patients as $patient ) {
														$user_name = KiFunctions::get_user_name( $patient );
														?>
                                                        <option value="<?php echo esc_attr( $patient->ID ); ?>"><?php echo esc_html( $user_name ); ?></option>
														<?php
													}
													?>
                                                </select>
                                            </label>
                                        <td>
                                    </tr>
                                    <tr>
                                        <td><?php esc_attr_e( $frmSlave['title'], 'ki-live-video-conferences' ); ?>
                                        <td>
                                        <td>
                                            <label>
                                                <select name="<?php echo $frmSlave['select']; ?>" class="frm-input">
													<?php
													$patients = get_users( Array( 'role' => $frmSlave['role'] ) );
													foreach ( $patients as $patient ) {
														$user_name = KiFunctions::get_user_name( $patient );

														?>
                                                        <option value="<?php echo esc_attr( $patient->ID ); ?>"><?php echo esc_html( $user_name ); ?></option>
														<?php
													}
													?>
                                                </select>
                                            </label>
                                        <td>
                                    </tr>

                                    <tr>
                                        <td><?php esc_html_e( 'Start Time', 'ki-live-video-conferences' ); ?>
                                        <td>
                                        <td>
                                            <label>
                                                <input type="text" name="start_time" class="frm-input input-datetime" value="<?php echo date('Y-n-d H:i ',time())?>" autocomplete="off">
                                            </label>

                                        <td>
                                    </tr>
									
									 <tr>
                                        <td><?php esc_html_e( 'Duration', 'ki-live-video-conferences' ); ?>
                                        <td>
                                        <td>
                                            <label>
                                               <input type="number" class="frm-input" name="duration" value="60">
                                            </label>

                                        <td>
                                    </tr>


                                </table>
                                <input type="hidden" name="post_title" class="frm-input" value="<?php esc_attr_e( 'Room_', 'ki-live-video-conferences' ); ?><?php echo time(); ?>">
                                <input type="hidden" name="timezone" class="frm-input" value="UTC">
                     
                                <input type="hidden" name="password" class="frm-input" value="<?php echo rand(); ?>">
           
                                <input type="submit" name="zoom_reg" class="button button-primary but-submit" value="<?php esc_attr_e( 'Submit', 'ki-live-video-conferences' ); ?>">

							</div>
							<div class="quick-meeting-buttons" style="display:none;">
								<div class="room_name"></div>
								<p>
								<a href="" class="but-edit button button-primary" target="_blink"><?php _e( 'Edit', 'ki-live-video-conferences' ); ?></a>
		
								<a href="" class="but-page button button-primary" target="_blink"><?php _e( 'Show', 'ki-live-video-conferences' ); ?></a>
								</p>
							</div>
                        </div>
                    </div>
                    <div id="dashboard_primary" class="postbox ">
                        <button type="button" class="handlediv" aria-expanded="true">
                        <span class="screen-reader-text">
                            <?php _e( 'Toggle panel: WordPress Events and News', 'ki-live-video-conferences' ); ?>
                        </span>
                            <span class="toggle-indicator" aria-hidden="true"></span>
                        </button>
                        <h2 class="hndle ui-sortable-handle">
                            <span><?php _e( 'Graphic', 'ki-live-video-conferences' ); ?></span>
                        </h2>
                        <div class="inside">
                            <canvas id="ki-live-video-chart"></canvas>
                            <div id="ki_live_video_chart_label" data-value='<?php echo esc_attr( json_encode( $ChartLabel ) ); ?>'></div>
                            <div id="ki_live_video_chart_data" data-value='<?php echo esc_attr( json_encode( $ChartData ) ); ?>'></div>

                        </div>
                    </div>
                </div>
            </div>

            <div id="postbox-container-2" class="postbox-container">
                <div id="side-sortables2" class="meta-box-sortables ui-sortable">
                    <div id="dashboard_quick_press" class="postbox ">
                        <button type="button" class="handlediv" aria-expanded="true">
                            <span class="screen-reader-text">
                                <?php _e( 'Toggle panel:', 'ki-live-video-conferences' ); ?>
                                <span class="hide-if-no-js">
                                <?php _e( 'Quick Meeting', 'ki-live-video-conferences' ); ?>
                                </span>
                                <span class="hide-if-js">
                                    <?php _e( 'Your Recent Drafts', 'ki-live-video-conferences' ); ?>
                                </span>
                            </span>
                            <span class="toggle-indicator" aria-hidden="true"></span>
                        </button>
                        <h2 class="hndle ui-sortable-handle">
                            <span>
                                <span class="hide-if-no-js">
                                    <?php _e( 'Meeting calendar', 'ki-live-video-conferences' ); ?></span>
                                <span class="hide-if-js">
                                    <?php _e( 'Meeting calendar', 'ki-live-video-conferences' ); ?>
                                </span>
                            </span>
                        </h2>
                        <div class="inside">
                            <div id="ki_live_video_event_calendar" data-calendar='<?php echo esc_attr( json_encode( $calendar_params ) ); ?>'></div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="postbox-container-1" class="postbox-container">
                <div id="side-sortables1" class="meta-box-sortables ui-sortable">
                    <div id="dashboard_activity" class="postbox">
                        <button type="button" class="handlediv" aria-expanded="true">
                        <span class="screen-reader-text">
                            <?php _e( 'Toggle panel: Activity', 'ki-live-video-conferences' ); ?>
                        </span>
                            <span class="toggle-indicator" aria-hidden="true"></span>
                        </button>
                        <h2 class="hndle ui-sortable-handle">
                            <span><?php _e( 'Upcoming meetings', 'ki-live-video-conferences' ); ?></span></h2>
                        <div class="inside">
                            <div class="upcoming-meetings">
								<?php
								foreach ( $Upcoming as $id => $val ) {
									?>
                                    <div class="item">
                                        <a href="<?php echo home_url( 'wp-admin/post.php?post=' . esc_attr( $id ) . '&action=edit' ); ?>">
											<?php echo esc_html( $val['name'] ); ?>
                                        </a>
                                        <span><?php echo date( 'Y-m-d H:i', $val['date'] ); ?></span>
                                    </div>
									<?php
								}
								?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
			
			
			<div id="postbox-container-1" class="postbox-container">
                <div id="side-sortables1" class="meta-box-sortables ui-sortable">
                    <div id="dashboard_activity" class="postbox">
                        <button type="button" class="handlediv" aria-expanded="true">
                        <span class="screen-reader-text">
                            <?php _e( 'Presenters', 'ki-live-video-conferences' ); ?>
                        </span>
                            <span class="toggle-indicator" aria-hidden="true"></span>
                        </button>
                        <h2 class="hndle ui-sortable-handle">
                            <span><?php _e( 'Presenters', 'ki-live-video-conferences' ); ?></span></h2>
                        <div class="inside">
                            <div class="presenters-list list">
								<?php
								$infoPresenter=KiFunctions::presenter();
								$presenters=get_users(Array('role'=>$infoPresenter['role']));
								foreach ( $presenters as $id => $presenter ) {
									?>
                                    <div class="item">
                                        <a href="<?php echo home_url( 'wp-admin/user-edit.php?user_id=' . $presenter->ID); ?>">
											<?php echo KiFunctions::get_user_name($presenter);?>
                                        </a>

                                    </div>
									<?php
								}
								?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
			
			<div id="postbox-container-1" class="postbox-container">
                <div id="side-sortables1" class="meta-box-sortables ui-sortable">
                    <div id="dashboard_activity" class="postbox">
                        <button type="button" class="handlediv" aria-expanded="true">
                        <span class="screen-reader-text">
                            <?php _e( 'Member', 'ki-live-video-conferences' ); ?>
                        </span>
                            <span class="toggle-indicator" aria-hidden="true"></span>
                        </button>
                        <h2 class="hndle ui-sortable-handle">
                            <span><?php _e( 'Member', 'ki-live-video-conferences' ); ?></span></h2>
                        <div class="inside">
							<?php 
							$infoMember=KiFunctions::member();
							$members=get_users(Array('role'=>$infoMember['role']));
							?>
                            <div class="member-search">
								<input type="text" class="input-member-search" list="member_search" placeholder="<?php _e( 'Search', 'ki-live-video-conferences' ); ?>" value="">
								
								<datalist id="member_search"> 
									<option value=""></option>

								</datalist> 
							</div>
                            <div class="members-list list">
								<?php
								
								foreach ( $members as $id => $member ) {
									?>
                                    <div class="item" >
                                        <a href="<?php echo home_url( 'wp-admin/user-edit.php?user_id=' . $member->ID); ?>"><?php echo KiFunctions::get_user_name($member);?></a>

                                    </div>
									<?php
								}
								?>
                            </div>
							<div class="members-pagination"></div>

                        </div>
                    </div>
                </div>
            </div>

            <div id="postbox-container-3" class="postbox-container">
                <div id="side-sortables3" class="meta-box-sortables ui-sortable">

                </div>
            </div>

            <div class="clear"></div>
        </div>

    </div>

</div>
