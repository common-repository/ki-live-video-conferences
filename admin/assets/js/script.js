jQuery(function ($) {

	function ki_graphic() {
        let ChartLabel=jQuery('#ki_live_video_chart_label').data('value');
        let ChartData=jQuery('#ki_live_video_chart_data').data('value');
		var color = Chart.helpers.color;
		var config = {
			type   : 'line',
			data   : {
				labels  : ChartLabel,
				datasets: [{
					label          : 'Meetings',
					labels         : ['2', '66', '77'],
					backgroundColor: '255, 206, 86, 0.8',
					borderColor    : '255, 206, 86, 0.8',
					fill           : false,

					data: ChartData,
				}]
			},
			options: {
				title     : {
					text: 'Chart.js Time Scale'
				},
				responsive: true,
				tooltips  : {
					mode: 'index'
				},

			}
		};

		window.myLine = new Chart(
				$('#ki-live-video-chart').get(0).getContext('2d'),
				config
		);

	}

	ki_graphic();

	let $calendar = $('#ki_live_video_event_calendar');
	$calendar.eventCalendar({
		jsonData            : $calendar.data('calendar'),
		jsonDateFormat      : 'human',
		startWeekOnMonday   : false,
		openEventInNewWindow: true,
		dateFormat          : 'dddd DD-MM-YYYY',
		showDescription     : true,
		

	});

	
	jQuery('.input-member-search').keyup(function(event){
		var _search=jQuery(this).val();
		if(_search.length>0){
			if(event.keyCode == 13){
				let _data_search={
					'action': 'ki_live_video_conferences_admin_member_search',
					'search': _search
				};
				jQuery.ajax({
					url: window.ajaxurl,
					type:'POST',
					dataType: 'json',
					data: _data_search,
					success: function(res) {
						jQuery('.page-ki-Overview .members-list').html('');

						for(key in res){
							let _html='<div class="item" >';
							_html+=' <a href="'+res[key].href+'">'+res[key].name+'</a>';
							_html+='</div>';

							jQuery('.page-ki-Overview .members-list').append(_html);
						}
					},
					error: function(res) {
						console.log('Error: ');
						console.log(res.responseText);
						console.log(res);
					}
				});
			}else{
				let _data={
					'action': 'ki_live_video_conferences_admin_member_autocomplete',
					'search': _search
				};
				jQuery.ajax({
					url: window.ajaxurl,
					type:'POST',
					dataType: 'json',
					data: _data,
					success: function(res) {
						jQuery('.page-ki-Overview datalist#member_search').html('');
						jQuery('.page-ki-Overview datalist#member_search').show();
						for(key in res){
							let _html='<option value="'+res[key]+'"></option>';
							jQuery('.page-ki-Overview datalist#member_search').append(_html);
						}
						
					},
					error: function(res) {
						console.log('Error: ');
						console.log(res.responseText);
						console.log(res);
					}
				});
			}
		}
	});
	
	jQuery('.QuickMeeting  .form-quick-meeting .but-submit').click(function(){
		jQuery('.QuickMeeting  .quick-meeting-buttons').hide();
		let _data={
			'action': 'ki_live_video_conferences_quick_meeting',
		};
		jQuery('.QuickMeeting  .form-quick-meeting .frm-input').each(function(){
			let _name=jQuery(this).attr('name');
			let _val=jQuery(this).val();
			_data[_name]=_val;
			
		});
		jQuery.ajax({
			url: window.ajaxurl,
			type:'POST',
			dataType: 'json',
			data: _data,
			success: function(res) {
				console.log(res);
				jQuery('.QuickMeeting  .quick-meeting-buttons .room_name').html(res.post_title);
				jQuery('.QuickMeeting  .quick-meeting-buttons .but-edit').attr('href',res.href);
				jQuery('.QuickMeeting  .quick-meeting-buttons .but-page').attr('href',res.page);
				jQuery('.QuickMeeting  .quick-meeting-buttons').show();
			},
			error: function(res) {
				console.log('Error: ');
				console.log(res.responseText);
				console.log(res);
			}
		});
	});
	
	jQuery('.input-datetime').datetimepicker({ dateFormat: 'yy-mm-dd' });
	
	/*function member_pagination(){
		let _count_page=5;
		let _count=jQuery('.page-ki-Overview .members-list .item').length;
		alert(_count);
	}
	
	
	member_pagination();*/
	



});



