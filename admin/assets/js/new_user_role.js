jQuery(function ($) {

	if(location.hash!=''){
		let _hash = location.hash;
		if(_hash.indexOf('ki-role')>0){
			let _role=_hash.replace('#ki-role=','');
			jQuery('select#role option').each(function(i,e){
				if(jQuery(this).val()==_role)jQuery(this).prop('selected', true);
			});
		}
	}

});



