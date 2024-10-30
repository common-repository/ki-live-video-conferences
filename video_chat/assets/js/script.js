jQuery( document ).ready(function() {
	jQuery('.container-authorization .ca_buttons .ca_but').click(function(){
		let _type=jQuery(this).attr('data');
		jQuery('.container-authorization .ca_buttons').hide();
		if(_type=='login'){
			jQuery('.container-authorization .form-login').show();
		}
		if(_type=='login'){
			jQuery('.container-authorization .form-login').show();
		}else{
			jQuery('.container-authorization .form-registration').show();
		}
		
	});
	
	//Rainbow
	jQuery('.rainbow_logout').click(function(){
		jQuery.ajax({
			url: window.ajaxurl,
			type:'POST',
			data: {'action': 'rainbow_logout'},
			success: function(res) {
				location.reload();
			}
		});
	});
	//***********************************************************************
	//Views template
	jQuery('.show_form_add_patient').click(function(){
		jQuery('.form-add-patient').show("slow");
	});
	jQuery('.show_form_add_doctor').click(function(){
		jQuery('.form-add-doctor').show("slow");
	});
	
	jQuery('.show-list-doctors').click(function(){
		jQuery('.list-doctors').show();
	});
	jQuery('.ki-live-video-conferences .doctors-list .doc-row .doctor-name').click(function(){
		jQuery('.ki-live-video-conferences .doctors-list .doc-row .doctor-list-meetings').hide();
		
		let _doc=jQuery(this).closest(".doc-row");
		_doc.find('.doctor-list-meetings').show();
	});
	
	
	jQuery('.show-make_appointment').click(function(){
		jQuery('.form-make_appointment').show();
		return false;
	});
	//***********************************************************************

	//***********************************************************************
	
	
	
	
});
