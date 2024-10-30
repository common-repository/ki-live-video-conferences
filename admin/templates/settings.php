<?php
namespace KiLiveVideoConferences;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
$active = KiFunctions::v_get('active');
if (!empty($active)){
    if ($active == 'learning') $active = 'education';
    if ($active == 'healthcare') $active = 'medicine';
    if ($active == 'video_platform') $active = 'video_platform';
}
?>
<div class="ki_options_page_settings">
    <form method="post" action="options.php">
        <h1>
            <img src="<?php echo esc_url( KI_VC_SETTINGS_URL . 'assets/images/plugin-icon-48.png' ); ?>">
			<?php _e( 'KI Conferences General Settings', 'ki-live-video-conferences' ); ?>
        </h1>
        <div class="resp-vtabs">
            <div class="resp-tabs-container">
                <div>
                    <table class="wp-list-table widefat plugins wpdxb" style="margin-top:10px; border:none;">
                        <tbody>
                        <tr valign="top">
                            <th scope="row" style="width: 50%;">
                                <label><?php _e( 'Select purpose', 'ki-live-video-conferences' ); ?></label>
                            </th>
                            <td>
                                <select id="video_chat_select_purpose" name="<?php echo KI_VC_SLUG; ?>[video_chat_select_purpose]">
									<?php $selected = Settings::get_option( 'video_chat_select_purpose', 'medicine' ); ?>
									<?php  if (!empty($active)) $selected = $active;?>
                                    <option value="medicine" <?php selected( $selected, 'medicine' ); ?>><?php _e( 'Medicine', 'ki-live-video-conferences' ); ?></option>
                                    <option value="education" <?php selected( $selected, 'education' ); ?>><?php _e( 'Education', 'ki-live-video-conferences' ); ?></option>
                                </select>
                            </td>

                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row" style="width: 50%;">
                                <label><?php _e( 'Select service', 'ki-live-video-conferences' ); ?></label>
                            </th>
                            <td>
                                <select id="video_chat_select_service" name="<?php echo KI_VC_SLUG; ?>[video_chat_select_service]">
									<?php $selected = Settings::get_option( 'video_chat_select_service', 'zoom' ); ?>
                                    <option value="zoom" <?php selected( $selected, 'zoom' ); ?>><?php _e( 'Zoom', 'ki-live-video-conferences' ); ?></option>
                                    <option value="rainbow" <?php selected( $selected, 'rainbow' ); ?>><?php _e( 'Rainbow', 'ki-live-video-conferences' ); ?></option>
                                </select>
                            </td>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h2 class="wpd-subtitle"><?php _e( 'Zoom settings', 'ki-live-video-conferences' ); ?></h2>
                    <table class="wp-list-table widefat plugins wpdxb" style="margin-top:10px; border:none;">
                        <tbody>

                        <tr valign="top">
                            <th colspan="1" style="width: 50%;">
                                <label class="ki-option-title"><?php _e( 'API Key', 'ki-live-video-conferences' ); ?></label>
                            </th>
                            <td>
								<?php $zoomApiKey = Settings::get_option( 'zoomApiKey' ); ?>
                                <input type="text" value="<?php echo esc_attr( $zoomApiKey ); ?>" id="zoomApiKey" name="<?php echo KI_VC_SLUG; ?>[zoomApiKey]" />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th colspan="1" style="width: 50%;">
                                <label class="ki-option-title"><?php _e( 'API Secret', 'ki-live-video-conferences' ); ?></label>
                            </th>
                            <td>
								<?php $zoomApiKey = Settings::get_option( 'zoomApiSecret' ); ?>
                                <input type="password" value="<?php echo esc_attr( $zoomApiKey ); ?>" id="zoomApiSecret" name="<?php echo KI_VC_SLUG; ?>[zoomApiSecret]" />
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row" style="width: 50%;">
                                <label for="zoomPublicMeeting" class="ki-option-title"><?php _e( 'Public meeting', 'ki-live-video-conferences' ); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" <?php checked( Settings::get_option( 'zoomPublicMeeting' ) == 1 ) ?> value="1" name="<?php echo KI_VC_SLUG; ?>[zoomPublicMeeting]" id="zoomPublicMeeting" />
                                <label for="zoomPublicMeeting"></label>

                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <h2 class="wpd-subtitle"><?php _e( 'Rainbow settings', 'ki-live-video-conferences' ); ?></h2>

                    <table class="wp-list-table widefat plugins wpdxb" style="margin-top:10px; border:none;">
                        <tbody>

                        <tr valign="top">
                            <th colspan="1" style="width: 50%;">
                                <label class="ki-option-title"><?php _e( 'API Key', 'ki-live-video-conferences' ); ?></label>
                            </th>
                            <td>
								<?php $rainbowApiKey = Settings::get_option( 'rainbowApiKey' ); ?>
                                <input type="text" value="<?php echo esc_attr( $rainbowApiKey ); ?>" id="rainbowApiKey" name="<?php echo KI_VC_SLUG; ?>[rainbowApiKey]" />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th colspan="1" style="width: 50%;">
                                <label class="ki-option-title"><?php _e( 'API Secret', 'ki-live-video-conferences' ); ?></label>
                            </th>
                            <td>
								<?php $rainbowApiSecret = Settings::get_option( 'rainbowApiSecret' ); ?>
                                <input type="password" value="<?php echo esc_attr( $rainbowApiSecret ); ?>" id="rainbowApiSecret" name="<?php echo KI_VC_SLUG; ?>[rainbowApiSecret]" />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th colspan="1" style="width: 50%;">
                                <label class="ki-option-title"><?php _e( 'Hook', 'ki-live-video-conferences' ); ?></label>
                            </th>
                            <td>
								<?php
								echo home_url( '/?ki_rainbow=auth' );
								?>
                            </td>
                        </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <table class="form-table wc-form-table">
            <tbody>
            <tr valign="top">
                <td colspan="4">
                    <p class="submit">
                        <input style="float: right;" type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Changes', 'ki-live-video-conferences' ); ?>">
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
		<?php settings_fields( KI_VC_SLUG ); ?>
    </form>
</div>
