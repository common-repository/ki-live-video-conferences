/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */
;(function (window, document, wp) {

	const el = wp.element.createElement;

	const {Fragment} = wp.element;
	const {PanelBody, PanelRow, SelectControl, ToggleControl} = wp.components;
	const {withState} = wp.compose;
	const {withSelect, withDispatch} = wp.data;
	const {InspectorControls} = wp.editor;
	const {__, _x, _n, _nx} = wp.i18n;

	const PostsDropdownControl = wp.compose.compose(
			// withDispatch allows to save the selected post ID into post meta
			withDispatch(function (dispatch, props) {
				return {
					setMetaValue: function (metaValue) {
						dispatch('core/editor').editPost(
								{meta: {[props.metaKey]: metaValue}}
						);
					}
				}
			}),
			// withSelect allows to get posts for our SelectControl and also to get the post meta value
			withSelect(function (select, props) {

				return {
					posts    : select('core').getEntityRecords('postType', ki_live_video_conferences_block.post_type),
					metaValue: select('core/editor').getEditedPostAttribute('meta')[props.metaKey],
				}

			}))(function (props) {

				// options for SelectControl
				var options = [];

				// if posts found
				if (props.posts) {
					options.push({value: 0, label: __('Select meeting', 'ki-live-video-conferences')});
					props.posts.forEach((post) => { // simple foreach loop
						options.push({value: post.id, label: post.title.rendered});
					});
				} else {
					options.push({value: 0, label: __('Loading...', 'ki-live-video-conferences')})
				}

				return el(SelectControl,
						{
							label   : __('Select a meeting id', 'ki-live-video-conferences'),
							options : options,
							onChange: function (value) {
								props.setAttributes({meeting_id: value});
								props.loadContent();
							},
							value   : props.attributes.meeting_id,
						}
				);

			}
	);


	wp.blocks.registerBlockType('ki-live-video-conferences/block', {
		title     : __('Conference video', 'ki-live-video-conferences'),
		icon      : 'video-alt2',
		category  : 'common',
		attributes: {
			meeting_id: {
				type   : 'string',
				default: 0,
			},
			type      : {
				type   : 'string',
				default: '',
			},
			latest    : {
				type   : 'boolean',
				default: 0,
			}
		},
		edit      : function (props) {

			props.setAttributes({type: ki_live_video_conferences_block.post_type});
			props.setAttributes({latest: props.attributes.latest});

			props.loadContent = function () {

				let attributes = this.attributes;

				if (!attributes) {
					return;
				}

				if (attributes && (attributes.meeting_id === undefined || attributes.meeting_id === 0)) {
					return;
				}


				jQuery.ajax({
					url    : window.ajaxurl,
					type   : 'POST',
					context: this,
					data   : {
						'meeting_id': attributes.meeting_id,
						'latest'    : attributes.latest,
						'type'      : attributes.type,
						'action'    : 'ki_live_video_conferences_block',
					},
					success: function (response) {
						if (response) {
							document.getElementById('ki-live-video-conferences-block').innerHTML = response;
						}

					}
				});

			}

			props.loadContent();

			return [
				el("div",
						{
							id: 'ki-live-video-conferences-block',
						},
						__('Your meeting will be added here. Please click here.', 'ki-live-video-conferences')
				),
				el(Fragment, {},
						el(InspectorControls, {},
								el(PanelBody, {},
										el(PanelRow, {},
												el(PostsDropdownControl,
														props
												),
										),
										el(PanelRow, {},
												el(
														ToggleControl,
														{
															label   : __('Always show the latest meeting', 'ki-live-video-conferences'),
															checked : props.attributes.latest,
															onChange: function (value) {
																props.setAttributes({latest: value});
															},
														}
												),
										),
								)
						)
				)
			];
		},
			save      : function (props) {
				return null;
			}
		})
}(window, document, window.wp));
