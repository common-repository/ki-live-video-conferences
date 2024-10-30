<?php

namespace KiLiveVideoConferences;

$post_id = KiFunctions::v_get( 'conference-video-room-rainbow' );
$ajaxurl = admin_url( 'admin-ajax.php' );
?>
    <!DOCTYPE HTML>
    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title><?php esc_html_e( 'Rainbow Video Chat', 'ki-live-video-conferences' ) ?></title>
        <script>
					var ki_live_video_conferences_ajax = '<?php echo esc_attr( $ajaxurl ); ?>';
        </script>

		<?php wp_head(); ?>

    </head>
    <body>

    <div id="vc-rainbow" class="">


        <div class="VCTools">


            <div class="ToolBar">


                <span class="ConferenceName"></span>
                <div class="buttons">

                    <div class="buttons-start-conference">
                        <div class="btn ShowWebCam" style="display:none;"><i class="fa fa-video-camera"></i></div>
                        <div class="btn HideWebCam"><i class="fa fa-video-camera"></i></div>
                        <div class="btn MicrophoneOn"><i class="fa fa-microphone" aria-hidden="true"></i></div>
                        <div class="btn MicrophoneOff"><i class="fa fa-microphone-slash" aria-hidden="true"></i></div>
                        <div class="btn btn-users-list"><i class="fa fa-users" aria-hidden="true"></i></div>
                    </div>


                </div>


            </div>
        </div>


        <div class="VC_Chat">
            <div class="VideoScreen">
                <audio id="globalAudioTag" autoplay style="display:none;"></audio>


                <!-- gallery tags, presenting up to 4 distant streams at the time -->
                <div class="ListVideo">
                    <video id="conferenceVideoPip" autoplay muted></video>
                    <video id="largevideo" autoplay style="display: none;"></video>
                    <video id="videoGallery_1" autoplay></video>

                    <video id="videoGallery_2" autoplay></video>

                    <video id="videoGallery_3" autoplay></video>

                    <video id="videoGallery_4" autoplay></video>
                    <!-- local sharing stream -->
                    <video id="conferenceSharingPip" autoplay muted style="display: none;"></video>
                    <!-- global tag, needed for bootstrapping the video streams -->
                    <video id="globalVideoTag" autoplay style="display: none;"></video>

                    <!-- somewhere in your code -->
                    <video id="minivideo" autoplay muted style="display: none;"></video>
                </div>

            </div>
            <div class="list-inviting">
                <div class="pos-close">
                    <div class="close"><?php esc_html_e( 'Close', 'ki-live-video-conferences' ) ?></div>
                </div>

                <div class="contact-list-inviting">
                    <div class="contact-list">
                    </div>
                    <div class="btn btn-success but-send-inviting"><?php esc_html_e( 'Inviting', 'ki-live-video-conferences' ) ?></div>
                </div>
                <div class="conference-participants">
                    <div class="list">
                        <div class="LabelName"><i class="fa fa-user-circle-o" aria-hidden="true"></i>
                            <span class="uName"></span></div>
                    </div>

                </div>
            </div>

        </div>


        <div class="vc-progress"><?php esc_html_e( 'Progress...', 'ki-live-video-conferences' ) ?></div>
        <div class="vc-bg-locked">
            <div class="win-locked"><?php esc_html_e( 'You have to receive an invitation to join.', 'ki-live-video-conferences' ) ?></div>
        </div>
    </div>

    <div class="wp_rainbow" style="display:none;"><?php echo esc_attr( $post_id ) ?></div>

	<?php wp_footer(); ?>
    </body>
    </html>
	<?php exit; ?>
