/* Wait for the page to load */


$=jQuery;

var rainbow_config='';
jQuery( document ).ready(function() {

		let post_id=jQuery('.wp_rainbow').html();
		let _data={
			'action': 'ki_live_video_conferences_rainbow_config',
			'post_id': post_id,
		}

		jQuery.ajax({
			url:window.ki_live_video_conferences_ajax,
			type:'POST',
			dataType: 'json',
			data: _data,
			success: function(res) {
					rainbow_config=res;
					console.log(rainbow_config);
					if(rainbow_config.token!=''){

						InitRainbow();
					}else{//empty token

					}
			},
			error: function(err,err2){
				console.log(err);
				console.log(err2);
			}
		});


});

function InitRainbow(){

    console.log("[DEMO] :: Rainbow Application started!");


    let $scope = {};
    let _contacts = {};
	var _conStart=false;
	var ownedBubble;
	var _str=true;
	var _host=null;
	var _account=null;
	var _stream=null;
	var applicationID  = rainbow_config.api_key;
	var applicationSecret  = rainbow_config.api_secret;
	var rainbow_token  = rainbow_config.token;
	var meeting_id  = rainbow_config.meeting_id;




	var conference = null;
	var bubble;
	var active_bubble;
	var active_bubble2;
	var active_conference;

	var _mstsrt=true;
	var _count=0;

	var _ClickShowWebCam=false;

	var _unlock=false;

	var tester=true;

    /* Bootstrap the SDK */

	var rainbow = rainbowSDK.default;




    VideoCall = function(_contact){
      //  setTimeout(function(){
            if(rainbow.webRTC.hasACamera()) {
                /* A webcam is available, you can make video call */
                var res = rainbow.webRTC.callInVideo(_contact);
                console.log(res);
                if(res.label === "OK") {
                    /* Your call has been correctly initiated. Waiting for the other peer to answer */
                    console.log("Now you are on the call with your friend");
                }
            } else {
                /* No webcam detected */
                vc_log("There is no webcam detected");
            }
       // }, 1000);
     }




    var answerVideo = function(){
        /* Ask the user to authorize the application to access to the media devices */
        navigator.mediaDevices.getUserMedia({audio: true, video: true}).then(function(stream) {
            /*  Get the list of available devices */
            navigator.mediaDevices.enumerateDevices().then(function(devices){

                /* Do something for each device (e.g. add it to a selector list) */
                devices.forEach(function(device) {
                    switch (device.kind) {
                        case "audioinput":
                            // This is a device of type 'microphone'
                            console.log("calling audioinput : ", device);
                            rainbow.webRTC.useMicrophone(device.deviceId)
                            break;
                        case "audiooutput":
                            // This is a device of type 'speaker'
                            console.log("calling audiooutput : ", device);
                            rainbow.webRTC.useSpeaker(device.deviceId)
                            break;
                        case "videoinput":
                            // This is a device of type 'camera'
                            console.log("calling videoinput : ", device);
                            rainbow.webRTC.useCamera(device.deviceId);
                            break;
                        default:
                            console.log("calling another inputoutput : ", device);
                            break;
                    }
                });

            }).catch(function(error) {
                /* In case of error when enumerating the devices */
                console.log("error recognizing media");
            });
        }).catch(function(error) {
            /* In case of error when authorizing the application to access the media devices */
            console.log("error recognizing media");
        });

        console.log("this is the call item : ", $scope.callItem);
        console.log("answer status : ", rainbow.webRTC.answerInVideo($scope.callItem));
        var video = angular.element("#minivideo");
        video.src = window.URL.createObjectURL(stream);
        rainbow.webRTC.answerInVideo($scope.callItem);
        // rainbow.webRTC.escaladeToVideoCall($scope.callItem);
        rainbow.webRTC.showLocalVideo();
        rainbow.webRTC.showRemoteVideo($scope.callItem);
        console.log("and this is the remote video : ", rainbow.webRTC.showRemoteVideo($scope.callItem));

    }




    var releaseCall = function(){
        console.log("the calling variable : ", $scope.callItem);
        rainbow.webRTC.release($scope.callItem);
        //$scope.streaming.stop();
        if (!$scope.callItem.isInitiator) {
          $("#calling").modal("hide");
        }
        $("#mycalling").modal("hide");
        console.log("the release status : ", rainbow.webRTC.release($scope.callItem));
    }




//***************************************************************************
	/* Callback for handling the event 'RAINBOW_ONREADY' */
    var onReady = function onReady() {
        console.log("[DEMO] :: On SDK Ready !");

		//*******************************************************
		//Login OAuth2
		//let hash=window.location.hash;

		if(rainbow_token!=''){

			jQuery('.vc-progress').show();

			rainbow.connection.signinOnRainbowOfficialWithToken(rainbow_token).then(function(account) {
						_account=account;
						console.log('Account =========================');
						console.log(account);
						/*jQuery('.uID').html(account.account.userId);
						jQuery('.uToken').html(account.token);
						jQuery('.uEmal').html(account.account.loginEmail);*/
						jQuery('.uName').html(account.userData.displayName);
						console.log('=========================');

                        document.addEventListener(rainbow.connection.RAINBOW_ONSTARTED, onStart );


                        _contacts = rainbow.contacts.getAll();
                        if (_contacts) {
							console.log(_contacts);
							jQuery.each( _contacts, function( key, value ) {
								if(tester==true && value.firstname!='Dima')jQuery('.contact-list').append('<div data="'+key+'"><input type="checkbox" id="chk_'+key+'"/><label for="chk_'+key+'"><i class="fa fa-user-circle-o" aria-hidden="true"></i> '+value.firstname+' '+value.lastname+'</label></div>');
								if(tester==false )jQuery('.contact-list').append('<div data="'+key+'"><input type="checkbox" id="chk_'+key+'"/><label for="chk_'+key+'"><i class="fa fa-user-circle-o" aria-hidden="true"></i> '+value.firstname+' '+value.lastname+'</label></div>');
;
							});
							//jQuery('.VCTools').show();
							jQuery('.form-login').hide();

                        }

						var invitationBubbles = rainbow.bubbles.getAllPendingBubbles();
						vc_log('All Bubbles');
						let AllBubbles=rainbow.bubbles.getAllBubbles();
						console.log(rainbow.bubbles.getAllBubbles());
						for(key in AllBubbles){
							if(AllBubbles[key].jid==meeting_id){
								vc_log('Active Bubbles');

								//alert(AllBubbles[key].name);
								active_bubble=AllBubbles[key];
								active_bubble2=active_bubble;
								vc_log(active_bubble);
								vc_log(_account);
								if(active_bubble.ownerContact.loginEmail==_account.account.loginEmail){
									VCStyle('Host');
								}else{
									VCStyle('Invited');
								}

								StartConference(active_bubble);
								//jQuery('.ShowWebCam').show();




								_host=true;

								_unlock=true;
							}
						}
						/*console.log(rainbow.bubbles.getAllInactiveBubbles());
						console.log(rainbow.bubbles.getAllOwnedBubbles());
						console.log(rainbow.bubbles.getAllPendingBubbles());*/
						vc_log('Invitation Bubbles');
						vc_log(invitationBubbles);

						if(invitationBubbles.length>0){

							for(key in invitationBubbles){
								console.log(invitationBubbles[key].jid);
								if(invitationBubbles[key].jid==meeting_id){

								}
							}

						}

						jQuery('.vc-progress').hide();

						setTimeout(function(){
							if(_unlock==false)VCStyle('Locked');
						},1000);

                }).catch(function(err) {

						jQuery('.vc-progress').hide();
						Logout();

                      console.log(err);

                });
		}else{

			Logout();
			//jQuery('.form-login').show();
		}





		jQuery('.rainbowBubbleStop').click(function(){
			//jQuery('.ToolBar .rainbowBubbleStart').show();
			//jQuery('.ToolBar .rainbowBubbleStop').hide();
			VCStyle('');
			rainbow.bubbles.stopWebRtcConference().then(function() {
				jQuery('.ConferenceName').html('');
				vc_log('Conference stopped');
				console.log("WebRTC conference stopped");
			})
			.catch(function(err) {
				console.log(err);
			});
		});


        $('.rainbowVideoCall').on('click',function(){
				let contact_key=jQuery('.contact-list').val();
				let select_contact=_contacts[contact_key];
                VideoCall(select_contact);
         })
		jQuery('.rainbowBubbleStart').click(function(){

			//jQuery('.ToolBar .rainbowBubbleStart').hide();
			//jQuery('.ToolBar .rainbowBubbleStop').show();
			//jQuery('.contact-list-inviting').show();
			//jQuery('.conference-participants').show();

			VCStyle('Host');

			_host=true;
			 jQuery('.vc-progress').show();

				/* Handler called when the user clicks on a contact */
				let _name="Conference_" + Math.floor(Math.random()*100000);
				vc_log('Conference Name : '+_name);
				console.log('Name Room '+_name);

				rainbow.bubbles.createBubble(_name, "The description of my bubble",false,false,null,true).then(function(bubble) {

					ownedBubble = bubble;

					console.log(bubble);
					if (!rainbow.bubbles.hasActiveConferenceSession()) {

						StartConference(bubble);
						//jQuery('.ShowWebCam').show();
					}else{
						vc_log('User busy...');
					}
					// Do something when the bubble has been created

				}).catch(function(err) {
					// Do something if there is a server issue when creating the new bubble
					vc_log('Error : create new bubble');
					console.log(err);
				});
		});



		jQuery('.but-send-inviting').click(function(){
			jQuery('.VC_Chat .list-inviting .contact-list div').each(function(){
				if(jQuery(this).find('input').prop( "checked" )){
					jQuery(this).hide();
					let contact_key=jQuery(this).attr('data');
					let select_contact=_contacts[contact_key];
					console.log('>>>Inviting contact ===============');
					console.log(select_contact);

						rainbow.bubbles.inviteContactToBubble(select_contact, active_bubble).then(function(updatedBubble) {
							active_bubble = updatedBubble;
							console.log(active_bubble);
							// Do something when the invitation has been sent successfully

						}).catch(function(err) {
							// Do something if there is a server issue when sending the invitation
							console.log(err);
							vc_log('=(');

						});
				}

			});

		});

		jQuery('.but-text-send-inviting').click(function(){
			alert();
			let u_id=jQuery('.txtID').val();
			//vc_log(u_id);
			rainbow.contacts.searchById(u_id).then(function(entityFound) {
				console.log(entityFound);
			if(entityFound){
				alert(1);
				jQuery('.InvitingInfo').html('Inviting : '+ entityFound._displayName);
				//vc_log('ok');
				// Do something with the entity found
				vc_log(entityFound);
				vc_log(active_bubble2);
				rainbow.bubbles.inviteContactToBubble(entityFound, active_bubble2).then(function(updatedBubble) {
					active_bubble = updatedBubble;
					console.log(active_bubble);
					// Do something when the invitation has been sent successfully

				}).catch(function(err) {
					// Do something if there is a server issue when sending the invitation
					console.log(err);
					console.log(entityFound);
					console.log(active_bubble2);
					vc_log('=(');

				});

			}else {
				//vc_log(':(');
				// No entity returned
			}

		});
		});


		jQuery('.but-leave').click(function(){
			location.reload();

			rainbow.bubbles.leaveBubble(active_bubble).then(function(leavedBubble) {
				bubble = leavedBubble;
				active_bubble = leavedBubble;
				// Do something when the bubble has been left
				alert('Leave Bubble');

			}).catch(function(err) {
				// Do something if there is an issue when leaving the bubble

			});
		});



		jQuery('.butOAuth').click(function(){
			location.reload();
			//window.open(rainbow_url);
			//window.top.location=rainbow_url;
		});

		jQuery('.ShowWebCam').click(function(){
			_ClickShowWebCam=true;
			_str=true;
			rainbow.bubbles.addMediaToConferenceSession(active_conference);
			rainbow.bubbles.addDistantVideoStreamToConference(active_conference);
			jQuery(this).hide();
			jQuery('.HideWebCam').show();
		});

		jQuery('.HideWebCam').click(function(){
			rainbow.bubbles.removeMediaFromConferenceSession (active_conference);
			jQuery(this).hide();
			jQuery('.ShowWebCam').show();
		});


		jQuery('.MicrophoneOn').click(function(){
			rainbow.bubbles.unmuteConferenceAudio(active_conference);

			jQuery(this).hide();
			jQuery('.MicrophoneOff').show();
		});


		jQuery('.MicrophoneOff').click(function(){
			rainbow.bubbles.muteConferenceAudio(active_conference);
			jQuery(this).hide();
			jQuery('.MicrophoneOn').show();
		});


		jQuery('.btn-users-list').click(function(){

			if (jQuery('.VC_Chat .list-inviting').is(':visible')){
				jQuery('.VC_Chat .list-inviting').hide();
			}else{
				jQuery('.VC_Chat .list-inviting').show();
			}

		});

		jQuery('.VC_Chat .list-inviting .pos-close .close').click(function(){

			jQuery('.VC_Chat .list-inviting').hide();

		});






    };

    var onStart = function onStart() {
      var contacts = rainbow.contacts.getAll();

      contacts = contacts.filter(function (contact) {
        return contact.id !== rainbow.contacts.getConnectedUser().id;
      });

      console.log(contacts);

    };





    /* Callback for handling the event 'RAINBOW_ONCONNECTIONSTATECHANGED' */
    var onLoaded = function onLoaded() {
        console.log("[DEMO] :: On SDK Loaded !");

        // Activate full SDK log
        //rainbow.setVerboseLog(true);

        rainbow
            .initialize(applicationID, applicationSecret)
            .then(function() {


                console.log("[DEMO] :: Rainbow SDK is initialized!");

                //console.log(rainbow.webRTC.canMakeAudioVideoCall());

                //console.log(rainbowSDK.bubbles.hasActiveConferenceSession());






            })
            .catch(function(err) {
                console.log("[DEMO] :: Something went wrong with the SDK...", err);
            });
    };



	var onWebRTCCallChanged = function onWebRTCCallChanged(event ) {


         let call = event.detail

          $scope.callItem = call;
          $scope.callingUser = call.contact;

        /* Listen to WebRTC call state change */
        switch(call.status.value) {
            case 'incommingCall':
				vc_log();
                /* Answer or reject the call */
				if(confirm('Receive a call')){
					rainbow.webRTC.answerInVideo(call);
				}
                break;
            case 'active':
                /* display the local and remote video */

				rainbow.webRTC.showLocalVideo();
				rainbow.webRTC.showRemoteVideo(call);



                break;
            case 'Unknown':
                /* Hiding the local and remote video */
                rainbow.webRTC.hideLocalVideo();
                rainbow.webRTC.hideRemoteVideo(call);
				vc_log('Call completed');

                break;
            default:
                break;
        }


    };


/* Somewhere in your application... */

    var callInAudio = function callInAudio(contact) {
        /* Call this API to call a contact using only audio stream*/
        var res = rainbow.webRTC.callInAudio(contact);
        if(res.label === "OK") {
            /* Your call has been correctly initiated. Waiting for the other peer to answer */
        }
    };

    var callInVideo = function callInVideo(contact) {
        /* Call this API to call a contact using both audio and video streams*/
        var res = rainbow.webRTC.callInVideo(contact);
        if(res.label === "OK") {
            /* Your call has been correctly initiated. Waiting for the other peer to answer */
        }
    };


	var StartConference=function StartConference(bubble){
		rainbow.bubbles.startOrJoinWebRtcConference(bubble).then(function(bubbleWithWebConf) {

			//Everything went fine, WebRTC conference is launched
			bubble = bubbleWithWebConf;
			active_bubble = bubbleWithWebConf;
			//vc_log('☺ ok');
			console.log('*********************thWebConf**********************');
			console.log(bubbleWithWebConf);

			console.log('*********************-----------------**********************');
			jQuery('.ConferenceName').html('<i class="fa fa-play-circle-o" aria-hidden="true"></i> ' + bubbleWithWebConf.name);
			jQuery('.vc-progress').show();

			setTimeout(function(){

				rainbow.bubbles.addMediaToConferenceSession(active_conference);
				jQuery('.vc-progress').hide();

				jQuery('.HideWebCam').show();
				jQuery('.ShowWebCam').hide();
			},500);



			//if(_mstsrt)rainbow.bubbles.addMediaToConferenceSession(conference);
			//_mstsrt=false;
		})
		.catch(function(error) {
			vc_log('Error : WebRtcConference');
			console.log(err);
			//Something went wrong, handle the error
		});
	}

	var DeleteUser = function DeleteUser(contact){
		console.log(contact);
		console.log(active_bubble);

		 rainbow.bubbles.removeContactFromBubble(contact, active_bubble).then(function(bubble) {
			// Do something when the contact has been removed from the bubble
			/*alert('OK');*/
			console.log('##############################');
			console.log(active_bubble);
			console.log(bubble);

		}).catch(function(err) {
			// Do something if there is an issue when removing the contact
			alert('(((');
			console.log(err);
		});
	}



	var VideoSize = function VideoSize(conference){
		let CountVideo=0;
		jQuery('.conference-participants .list').html('');
		jQuery('.conference-participants .list').append('<div class="user" ><i class="fa fa-user-circle-o" aria-hidden="true"></i> '+_account.userData.displayName+'</div>');
		for(key in conference.videoGallery){
			if(conference.videoGallery[key].state=='busy'){
				CountVideo++;
				if(conference.videoGallery[key].displayName!=undefined)jQuery('.conference-participants .list').append('<div class="user" ><i class="fa fa-user-circle-o" aria-hidden="true"></i> '+conference.videoGallery[key].displayName+'<div class="delete" data="'+conference.videoGallery[key].publisherJidIm+'">x</div></div>');

			}



		}
		jQuery('.conference-participants .list .user .delete').click(function(){
			if(confirm('Delete user ?')){
				let data_id=jQuery(this).attr('data');
				console.log(data_id);
				console.log(_contacts);
				for(c in _contacts){
					if(_contacts[c].id==data_id){
						DeleteUser(_contacts[c]);
						jQuery(this).closest(".user").hide();

					}
				}
			}
		});

		jQuery('.VC_Chat .ListVideo').removeClass('vn1');
		jQuery('.VC_Chat .ListVideo').removeClass('vn2');
		if(CountVideo==0)jQuery('.VC_Chat .ListVideo').addClass('vn1');
		if(CountVideo==1)jQuery('.VC_Chat .ListVideo').addClass('vn2');


	}

	var VCStyle = function VCStyle(_style){


		jQuery('#vc-rainbow').attr('class','');
		jQuery('#vc-rainbow').addClass(_style);
	}



	var Logout = function Logout() {


		jQuery.ajax({
			url: window.ki_live_video_conferences_ajax,
			type:'POST',
			data: {'action': 'ki_live_video_conferences_rainbow_logout'},
			success: function(res) {
				window.top.location.reload();
				//location.reload();
			}
		});

    };

var getConferenceObject = function(bubble) {
    // Do something when bubble contains confEndpoints property which is populated
    if (bubble.confEndpoints.length === 0) {
        return null;
    }

    // confEndpoints array contains at least one item
    conferenceId = bubble.confEndpoints[0].confEndpointId;
	console.log('conference ID =======================');
	console.log(conferenceId);
	console.log(bubble.confEndpoints);
    return rainbow.bubbles.getConferenceSessionById(conferenceId);
};

//function executed when the RAINBOW_ONBUBBLEUPDATED is fired.
var onBubbleUpdated = function(event) {


    let bubble = event.detail;

    conference = getConferenceObject(bubble);

	console.log('conference =======================');
	console.log(conference);
	if(conference){

		/*if (conference.hasLocalVideo === true) {
			vc_log('ok');
			rainbow.bubbles.addLocalVideoStreamToConference(conference);
			_str=false;
		};*/
	}


};

 let onNewMessageReceiptReceived = function(event) {
        let type = event.detail.evt;
		console.log('Event Received====');
		console.log(type);
		vc_log(type);
		vc_log(event.detail.message.data);
        switch (type) {
            case "server":
                // Do something when Rainbow received your message
                break;
            case "received":
                // Do something when someone in the bubble received your message
				if(event.detail.message.data=='leaveMsgRoom'){
					console.log(active_bubble);
				}

                break;
            case "read":
                // Do something when someone in the bubble read your message
                break;
            default:
                break;
        }
    };




var onWebConferenceUpdated = function(event) {
				// do something with the conference object
				var conference = event.detail;

				console.log('Conference Event !!! >>>>>>>>>>>>>>>>>');
				console.log(conference);
				if(conference!=null){
					if(conference.active==true){
						VideoSize(conference);
						_count++;
						vc_log('Count'+_count);
						if(_str==true){
							console.log(conference);
							active_conference=conference;
							if (conference.hasLocalVideo === true) {

								rainbow.bubbles.addLocalVideoStreamToConference(conference);
								vc_log('Start loval video...');

								_str=false;
								if(_ClickShowWebCam==true){
									//jQuery('.ShowWebCam').hide();
									_ClickShowWebCam=false;
								}
								jQuery('.vc-progress').hide();
							}else{
								console.log('Object conference >>>>>');
								console.log(conference);

								if(_host){
									/*if(_mstsrt){
										rainbow.bubbles.addMediaToConferenceSession(conference);
										jQuery('.vc-progress').hide();

									}*/
								}else{
									//var conferenceId = conference.id;
									//var sessionId_1 = conference.videoGallery[0].sessionId;
									//rainbow.bubbles.updateMainVideoSession(conferenceId, sessionId_1);

									rainbow.bubbles.addDistantVideoStreamToConference(conference);

									/*if(_mstsrt){
										setTimeout(function(){
											rainbow.bubbles.addMediaToConferenceSession(active_conference);
											jQuery('.vc-progress').hide();

											jQuery('.HideWebCam').show();
											jQuery('.ShowWebCam').hide();
										},500);
									}*/



								}

								jQuery('.vc-progress').hide();
								_mstsrt=false;


							}

						}


					}

					rainbow.bubbles.addDistantVideoStreamToConference(conference);
				}






		};







    var onInvitationReceived = function(event) {
		console.log('Event invited ^^^^^^^^^^^^^^^^');
        bubble = event.detail;
        // Do something when receiving a new invitation to join a bubble
        rainbow.bubbles.acceptInvitationToJoinBubble(bubble).then(function(updatedBubble) {
            // Do something when you have accepted the invitation
            bubble = updatedBubble;
            vc_log('You were invited...');
			console.log('You were invited...');
			console.log(bubble);
			rainbow.bubbles.addDistantVideoStreamToConference(active_conference);
			if (!rainbow.bubbles.hasActiveConferenceSession()) {
						_host=false;
						_mstsrt=true;
						StartConference(bubble);
						//jQuery('.ShowWebCam').show();
						VCStyle('Invited');
					}else{
						vc_log('User busy...');
					}
        }).catch(function() {
            // Do something when there is a server issue during this request

        });
    };





    /* Listen to the SDK event RAINBOW_ONREADY */
    document.addEventListener(rainbow.RAINBOW_ONREADY, onReady);

    /* Listen to the SDK event RAINBOW_ONLOADED */
    document.addEventListener(rainbow.RAINBOW_ONLOADED, onLoaded);



	document.addEventListener(rainbow.webRTC.RAINBOW_ONWEBRTCCALLSTATECHANGED, onWebRTCCallChanged);
	//... somewhere in your code
	document.addEventListener(rainbow.bubbles.RAINBOW_ONWEBCONFERENCEUPDATED, onWebConferenceUpdated);

	document.addEventListener(rainbow.bubbles.RAINBOW_ONBUBBLEUPDATED, onBubbleUpdated);

	document.addEventListener(rainbow.im.RAINBOW_ONNEWIMRECEIPTRECEIVED, onNewMessageReceiptReceived);

	document.addEventListener(rainbow.bubbles.RAINBOW_ONBUBBLEINVITATIONTOJOINRECEIVED, onInvitationReceived);



    /* Load the SDK */
    rainbow.start();
    rainbow.load();




	function vc_log(_text){
		/*let val=jQuery('.vc_log').val();
		if(val!='')val+='\n';
		jQuery('.vc_log').html(val+_text);*/
		console.log('<**********LOG*************>');
		console.log(_text);
		console.log('<**************************>');
	}




};

