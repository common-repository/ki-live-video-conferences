/* Wait for the page to load */
function InitRainbow() {


	console.log("[DEMO] :: Rainbow Application started!");

	var redirectURL = '';

	let $scope = {};
	let _contacts = {};
	var _conStart = false;
	var ownedBubble;
	var _str = true;
	var _host = null;
	var applicationID = rainbow_config.api_key;
	var applicationSecret = rainbow_config.api_secret;
	var rainbow_token = rainbow_config.token;
	var meeting_id = rainbow_config.meeting_id;


	var conference = null;
	var bubble;
	var avtive_bubble;
	var active_conference;
	var active_account;

	var _mstsrt = true;
	var _count = 0;

	var _ClickShowWebCam = false;
	/* Bootstrap the SDK */

	var rainbow = rainbowSDK.default;

	var tester = true;


	var answerVideo = function () {
		/* Ask the user to authorize the application to access to the media devices */
		navigator.mediaDevices.getUserMedia({audio: true, video: true}).then(function (stream) {


			/*  Get the list of available devices */
			navigator.mediaDevices.enumerateDevices().then(function (devices) {

				/* Do something for each device (e.g. add it to a selector list) */
				devices.forEach(function (device) {
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

			}).catch(function (error) {
				/* In case of error when enumerating the devices */
				console.log("error recognizing media");
			});
		}).catch(function (error) {
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


//***************************************************************************
	/* Callback for handling the event 'RAINBOW_ONREADY' */
	var onReady = function onReady() {
		console.log("[DEMO] :: On SDK Ready !");

		//*******************************************************


		if (rainbow_token != '') {
			//jQuery('.vc-progress').show();
			jQuery('.vc_rainbow_status').html('Auth...');
			/*
			*Type singin
			* rainbow.connection.signin
			* rainbow.connection.signinOnRainbowOfficial
			* rainbow.connection.signinSandBoxWithToken
			* rainbow.connection.signinOnRainbowOfficialWithToken
			*/

			rainbow.connection.signinOnRainbowOfficialWithToken(rainbow_token).then(function (account) {
				jQuery('.vc_rainbow_status').html('Get Account...');


				active_account = account;


				jQuery('.vc_rainbow_status').html(account.userData.displayName);
				jQuery('.vc_rainbow_users_list').show();
				jQuery('.wrpLogout').show();


				//Users List
				_contacts = rainbow.contacts.getAll();

				if (rainbow_action == 'create_conference') {
					StartBubbleAdmin(account);
					ListContacts(_contacts);
				} else {
					if (jQuery('.vc_rainbow_host').html() == account.userData.displayName) {
						console.log(rainbow_config);

						let AllBubbles = rainbow.bubbles.getAllBubbles();
						for (key in AllBubbles) {
							if (AllBubbles[key].jid == meeting_id) {
								vc_log('Active Bubbles');
								avtive_bubble=AllBubbles[key];

							}
						}
						ListContacts(_contacts);

						//
					} else {
						jQuery('.vc_rainbow_users_list .contact-list').html('<div class="attention">You are not a host</div>');
					}
				}

			}).catch(function (err) {

				jQuery('.vc_rainbow_status').html('Login failed!');
				jQuery('.rainbow-join').show();
				console.log(err);
			});
		} else {
			jQuery('.vc_rainbow_status').html('Login failed!');
			jQuery('.rainbow-join').show();
		}







		jQuery('.but-send-inviting').click(function () {
			jQuery('.vc_rainbow_users_list .contact-list div').each(function () {
				if (jQuery(this).find('input').prop("checked")) {
					jQuery(this).hide();
					let contact_key = jQuery(this).attr('data');
					let select_contact = _contacts[contact_key];
					console.log('>>>Inviting contact ===============');
					console.log(select_contact);

					rainbow.bubbles.inviteContactToBubble(select_contact, avtive_bubble).then(function (updatedBubble) {
						avtive_bubble = updatedBubble;
						console.log(ownedBubble);
						// Do something when the invitation has been sent successfully

					}).catch(function (err) {
						// Do something if there is a server issue when sending the invitation
						console.log(err);
						vc_log('=(');

					});
				}

			});

		});




	};


	/* Callback for handling the event 'RAINBOW_ONCONNECTIONSTATECHANGED' */
	var onLoaded = function onLoaded() {
		console.log("[DEMO] :: On SDK Loaded !");

		// Activate full SDK log
		//rainbow.setVerboseLog(true);

		rainbow
				.initialize(applicationID, applicationSecret)
				.then(function () {


					console.log("[DEMO] :: Rainbow SDK is initialized!");

					//console.log(rainbow.webRTC.canMakeAudioVideoCall());

					//console.log(rainbowSDK.bubbles.hasActiveConferenceSession());


				})
				.catch(function (err) {
					console.log("[DEMO] :: Something went wrong with the SDK...", err);
				});
	};


	var ListContacts = function ListContacts(_contacts) {
		if (_contacts) {

			jQuery.each(_contacts, function (key, value) {
				if (tester == true && value.firstname != 'Dima') jQuery('.vc_rainbow_users_list .contact-list').append('<div data="' + key + '"><input type="checkbox" id="chk_' + key + '"/><label for="chk_' + key + '">' + value.firstname + ' ' + value.lastname + '</label></div>');
				if (tester == false) jQuery('.vc_rainbow_users_list .contact-list').append('<div data="' + key + '"><input type="checkbox" id="chk_' + key + '"/><label for="chk_' + key + '">' + value.firstname + ' ' + value.lastname + '</label></div>');
				;
			});
			jQuery('.VCTools').show();
			jQuery('.form-login').hide();

		}
	}

	var StartBubbleAdmin = function StartConferenceAdmin(account) {

		let _id = jQuery('.vc_rainbow_post_id').html();
		let _name = jQuery('.vc_rainbow_post_title').html();
		let _desc = jQuery('.vc_rainbow_post_content').html();


		rainbow.bubbles.createBubble(_name, _desc,false,false,null,true).then(function (bubble) {

			ownedBubble = bubble;

			console.log(bubble);
			if (!rainbow.bubbles.hasActiveConferenceSession()) {
				//alert(bubble.dbId);
				//alert(bubble.jid);
				//StartConference(bubble);
				jQuery('.vc_rainbow_status').html('StartBubble');
				let _data = {
					'action'      : 'ki_live_video_conferences_rainbow_save',
					'post_id'     : _id,
					'meeting_id'  : bubble.jid,
					'host_name'   : account.userData.displayName,
					'host_email'  : account.account.loginEmail,
					'host_user_id': account.account.userId,
				}
				jQuery.ajax({
					url    : window.ajaxurl,
					type   : 'POST',
					data   : _data,
					success: function (res) {
						console.log(res);
						console.log('Create conference ... OK');
						jQuery('.vc_rainbow_status').html(account.userData.displayName);

					}
				});

			} else {
				jQuery('.vc_rainbow_status').html('User busy...');

			}
			// Do something when the bubble has been created

		}).catch(function (err) {
			// Do something if there is a server issue when creating the new bubble
			jQuery('.vc_rainbow_status').html('Error : create new bubble');
			console.log(err);
		});

	}


	var StartConference = function StartConference(bubble) {
		rainbow.bubbles.startOrJoinWebRtcConference(bubble).then(function (bubbleWithWebConf) {

			//Everything went fine, WebRTC conference is launched
			bubble = bubbleWithWebConf;
			active_conference = bubbleWithWebConf;
			//vc_log('☺ ok');
			console.log('*********************thWebConf**********************');
			console.log(bubbleWithWebConf);

			console.log('*********************-----------------**********************');

			//if(_mstsrt)rainbow.bubbles.addMediaToConferenceSession(conference);
			//_mstsrt=false;
		})
				.catch(function (error) {
					vc_log('Error : WebRtcConference');
					console.log(err);
					//Something went wrong, handle the error
				});
	}


	var onWebRTCCallChanged = function onWebRTCCallChanged(event) {


		let call = event.detail

		$scope.callItem = call;
		$scope.callingUser = call.contact;

		/* Listen to WebRTC call state change */
		switch (call.status.value) {
			case 'incommingCall':
				vc_log();
				/* Answer or reject the call */
				if (confirm('Receive a call')) {
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


	var StartConference = function StartConference(bubble) {
		rainbow.bubbles.startOrJoinWebRtcConference(bubble).then(function (bubbleWithWebConf) {

			//Everything went fine, WebRTC conference is launched
			bubble = bubbleWithWebConf;
			avtive_bubble = bubbleWithWebConf;
			//vc_log('☺ ok');
			console.log('*********************thWebConf**********************');
			console.log(bubbleWithWebConf);

			console.log('*********************-----------------**********************');
			jQuery('.ConferenceName').html('Conference: ' + bubbleWithWebConf.name);
			jQuery('.vc-progress').show();
			//if(_mstsrt)rainbow.bubbles.addMediaToConferenceSession(conference);
			//_mstsrt=false;
		})
				.catch(function (error) {
					vc_log('Error : WebRtcConference');
					console.log(err);
					//Something went wrong, handle the error
				});
	}


	var getConferenceObject = function (bubble) {
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
	var onBubbleUpdated = function (event) {


		let bubble = event.detail;

		conference = getConferenceObject(bubble);

		console.log('conference =======================');
		console.log(conference);


	};


	/* Listen to the SDK event RAINBOW_ONREADY */
	document.addEventListener(rainbow.RAINBOW_ONREADY, onReady);

	/* Listen to the SDK event RAINBOW_ONLOADED */
	document.addEventListener(rainbow.RAINBOW_ONLOADED, onLoaded);


	document.addEventListener(rainbow.webRTC.RAINBOW_ONWEBRTCCALLSTATECHANGED, onWebRTCCallChanged);


	document.addEventListener(rainbow.bubbles.RAINBOW_ONBUBBLEUPDATED, onBubbleUpdated);


	/* Load the SDK */
	rainbow.start();
	rainbow.load();


	function vc_log(_text) {
		console.log('<**********LOG*************>');
		console.log(_text);
		console.log('<**************************>');
	}


};

