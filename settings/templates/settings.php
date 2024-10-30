<?php
namespace KiLiveVideoConferences;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

?>
<div class="ki_options_page_settings">
    <form method="post">
        <h1>
            <img src="<?php echo esc_url( KI_VC_SETTINGS_URL . 'assets/img/plugin-icon-48.png' ); ?>">
			<?php _e( 'KI Publisher General Settings', 'ki-live-video-conferences' ); ?>
        </h1>
        <div class="resp-vtabs">
            <div class="resp-tabs-container">
                <div>
                    <table class="wp-list-table widefat plugins wpdxb" style="margin-top:10px; border:none;">
                        <tbody>

                        <tr valign="top">
                            <th scope="row" style="width: 50%;">
                                <label><?php _e( 'Select service', 'ki-live-video-conferences' ); ?></label>
                            </th>
                            <td>
                                <select id="video_chat_select_service" name="video_chat_select_service">
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
                                <input type="text" value="<?php echo esc_attr( $zoomApiKey ); ?>" id="zoomApiKey" name="zoomApiKey" />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th colspan="1" style="width: 50%;">
                                <label class="ki-option-title"><?php _e( 'API Secret', 'ki-live-video-conferences' ); ?></label>
                            </th>
                            <td>
								<?php $zoomApiKey = Settings::get_option( 'zoomApiSecret' ); ?>
                                <input type="password" value="<?php echo esc_attr( $zoomApiKey ); ?>" id="zoomApiSecret" name="zoomApiSecret" />
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row" style="width: 50%;">
                                <label for="zoomPublicMeeting" class="ki-option-title"><?php _e( 'Public meeting', 'ki-live-video-conferences' ); ?></label>
                            </th>
                            <td>
                                <input type="checkbox" <?php checked( Settings::get_option( 'zoomPublicMeeting' ) == 1 ) ?> value="1" name="zoomPublicMeeting" id="zoomPublicMeeting" />
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
                                <input type="text" value="<?php echo esc_attr( $rainbowApiKey ); ?>" id="rainbowApiKey" name="rainbowApiKey" />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th colspan="1" style="width: 50%;">
                                <label class="ki-option-title"><?php _e( 'API Secret', 'ki-live-video-conferences' ); ?></label>
                            </th>
                            <td>
								<?php $rainbowApiSecret = Settings::get_option( 'rainbowApiSecret' ); ?>
                                <input type="password" value="<?php echo esc_attr( $rainbowApiSecret ); ?>" id="rainbowApiSecret" name="rainbowApiSecret" />
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
                        <input style="float: right;" type="submit" class="button button-primary" name="wc_submit_options" value="<?php esc_attr_e( 'Save Changes', 'ki-live-video-conferences' ); ?>">
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
        <input type="hidden" name="action" value="update">
    </form>
</div>
