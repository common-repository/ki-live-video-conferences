
jQuery( document ).ready(function() {

	let post_id=jQuery('.wp_zoom').html();
	let user_name=jQuery('.wp_zoom_user_name').html();
	_data={
		'action': 'ki_live_video_conferences_zoom_config',
		'post_id': post_id
	}
	jQuery.ajax({
		url: window.ki_live_video_conferences_ajax,
		type:'POST',
		dataType: 'json',
		data: _data,
		success: function(Zoom) {
				console.log(Zoom);

				ZoomMtg.preLoadWasm();
				ZoomMtg.prepareJssdk();

				ZoomMtg.init({
					leaveUrl: 'http://www.zoom.us',
					isSupportAV: true,
					success: function () {

						if(user_name!='')Zoom.user_name=user_name;
						console.log(user_name);
						console.log(Zoom.user_name);
						ZoomMtg.join(
							{
								meetingNumber: Zoom.meeting_id,
								userName: Zoom.user_name,
								signature: Zoom.signature,
								apiKey: Zoom.api_key,
								passWord: Zoom.password,
								success: function(res){
									console.log('join meeting success');
								},
								error: function(res) {
									console.log(res);
									return false;
								}
							}
						);
					},
					error: function(res) {
						console.log(res);
						return false;
					}
				});




		},
		error: function(res) {
            console.log(res.responseText);
        }
	});



});
