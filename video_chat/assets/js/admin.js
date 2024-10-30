jQuery( document ).ready(function() {
	let type_form_patient='<a href="javascript:void(0);" data-role="patient" >New Patient</a>';
	let type_form_doctor='<a href="javascript:void(0);" data-role="doctor"  >New Doctor</a>';
	let type_form_create_meeting_request='<a href="javascript:void(0);" data-role="create_meeting_request"  >Create a meeting request</a>';
	jQuery('.um-admin-boxed-links').append(type_form_patient);
	jQuery('.um-admin-boxed-links').append(type_form_doctor);
	jQuery('.um-admin-boxed-links').append(type_form_create_meeting_request);
});