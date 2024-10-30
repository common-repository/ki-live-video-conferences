
$=jQuery;//fix jquery

var rainbow_config='';
var rainbow_action='';


jQuery( document ).ready(function() {
	if(jQuery('.vc_rainbow_init').html()){

		rainbow_action = jQuery('.vc_rainbow_init').html();
		jQuery('.vc_rainbow_status').html('Get Token...');
		let post_id=jQuery('.vc_rainbow_post_id').html();
		let _data={
			'action': 'ki_live_video_conferences_rainbow_config',
			'post_id': post_id,
		}
		jQuery.ajax({
			url: window.ajaxurl,
			type:'POST',
			dataType: 'json',
			data: _data,
			success: function(res) {
					rainbow_config=res;
					console.log(rainbow_config);
					if(rainbow_config.token!=''){
						jQuery('.vc_rainbow_status').html('Token received');
						InitRainbow();
					}else{//empty token
						jQuery('.wrpLogin').show();
						jQuery('.vc_rainbow_status').html('Log in please');
					}
			},
			error: function(err,err2){
				console.log(err);
				console.log(err2);
			}
		});
	}

	jQuery('.rainbow-join').click(function(){
			let url=jQuery('.vc_rainbow_page_url').text();
			window.location.replace(url);
	});
	jQuery('.winRainbowOAuth .close').click(function(){
			jQuery('.winRainbowOAuth').hide();
	});

	//Rainbow
	jQuery('.rainbow_logout').click(function(){
		jQuery.ajax({
			url: window.ajaxurl,
			type:'POST',
			data: {'action': 'ki_live_video_conferences_rainbow_logout'},
			success: function(res) {
				location.reload();
			}
		});
	});


});
