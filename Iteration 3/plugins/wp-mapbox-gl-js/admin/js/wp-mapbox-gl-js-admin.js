(function( $ ) {
	'use strict';

	// Check is post title exists
	$(document).on('click','#publish',function(e) {
		if($('#title').val()==='') {
			e.preventDefault();
			alert('Please ensure you give the map post a title. It will not save properly otherwise.');
		}
	});
	$(document).on('click','#save-post',function(e) {
		if($('#title').val()==='') {
			e.preventDefault();
			alert('Please ensure you give the map post a title. It will not save properly otherwise.');
		}
	});

	// Setting up allowing users to set feature, doing API call to get features
	$(document).on('change', '#wp_mapbox_gl_js_dataset_list', function(e) {
		e.preventDefault();
		$('#wp_mapbox_gl_js_dataset_id_set').val(e.target.value);
		if(e.target.value!=='') {
			$('.wp_mapbox_gl_js_features').show();
			$.ajax({
				url : "https://api.mapbox.com/datasets/v1/"+$('#wp_mapbox_gl_js_username').val()+"/"+e.target.value+"/features?access_token="+$('#wp_mapbox_gl_js_secret_token').val()
			}).done(function(data) {
				// opt for using "name" or "Name" if it exists; otherwise use the first property
				data.features.forEach(function(feature, index) {
					var featureName = '';
					if(featureName==='' && feature.properties.name) {
						featureName = feature.properties.name;
					}
					if(featureName==='' && feature.properties.Name) {
						featureName = feature.properties.Name;
					}
					if(featureName==='') {
						for(var prop in feature.properties) {
							featureName = feature.properties[prop];
						}
					}
					if(featureName==='') {
						featureName = 'Feature '+index;
					}
					$('#wp_mapbox_gl_js_feature_list').append('<option value="'+feature.id+'">'+featureName+'</option>');
				})
			});
		} else {
			$('.wp_mapbox_gl_js_features').hide();
		}
	});

	// Toggling slider edit page
	$(document).on('click','.wp-mapbox-gl-js-advanced-options-button',function(e) {
		e.preventDefault();
		$('.wp-mapbox-gl-js-advanced-options').slideToggle();
	});

	if($('#toggle_wp_mapbox_gl_js_credentials').length) {
		$(document).on('click','#toggle_wp_mapbox_gl_js_credentials',function(e) {
			e.preventDefault();
			$('#wp_mapbox_gl_js_credentials').slideToggle();
		})
	}

	// Export functionality
	$(document).on('click','#wp-mapbox-gl-js-export-button',function(e) {
		function downloadTextFile(text, name) {
		  const a = document.createElement('a');
		  a.href = URL.createObjectURL( new Blob([text], { type:`text/${name.split(".").pop()}` }) );
		  a.download = name;
		  a.click();
		}

		e.preventDefault();
		if($('#wp-mapbox-gl-js-category-export').val()!=='') {
			$.ajax({
				url : $('#wp-mapbox-gl-js-plugins').val()+"/wp-mapbox-gl-js/admin/partials/wp-mapbox-gl-js-print.php?gl_js_maps_category="+$('#wp-mapbox-gl-js-category-export').val(),
				method : 'GET',
				dataType: "json",
				contentType: "application/json;charset=utf-8"
			}).done(function(data) {
				downloadTextFile(JSON.stringify(data), $('#wp-mapbox-gl-js-category-export').val()+'.json');
			});
		}
	});

	// Testing to see if access token is correct
	if(window.location.href.indexOf("wp-mapbox-gl-js-settings") > -1) {

		try {
			var access_token = $('#wp_mapbox_gl_js_access_token').val();
			mapboxgl.accessToken = access_token;
			var map = new mapboxgl.Map({
				style : 'mapbox://styles/mapbox/light-v9',
				container : document.createElement('div')
			});
			$.ajax({
				url : 'https://api.mapbox.com/styles/v1/mapbox/light-v9?access_token='+access_token
			}).done(function(data) {
				$('.dashicons-yes').show();
			}).fail(function(err) {
				$('.dashicons-no').show();
			})
		} catch {
			$('.dashicons-no').show();
		}
	}

	// Dataset map management
	$(document).on('click','#wp-mapbox-gl-js-to-dataset-add',function(e) {
		e.preventDefault();
		if(confirm('Are you sure you want to add these features in Mapbox? \n\n NOTE: it will take up to 10 minutes for Mapbox to show the changes in its dataset.')) {
			var updatedFeatures = [];
			var mapObject = JSON.parse($('#wp_mapbox_gl_js_map_object').val());
			mapObject.mapData.forEach(function(feature) {
				if(feature.id) {
					if(feature.properties.marker_icon_anchor==='bottom'||feature.properties.marker_icon_anchor==='center') {
						delete feature.properties.marker_icon_anchor;
					}
					if(feature.properties.popup_open) {
						delete feature.properties.popup_open;
					}
					if(feature.properties.marker_icon_url.indexOf('black_default.png')>-1) {
						delete feature.properties.marker_icon_url;
					}
					if(feature.properties.marker_title==='Line'||feature.properties.marker_title==='Fill'||feature.properties.marker_title==='Marker') {
						delete feature.properties.marker_title;
					}
					if(feature.properties.color==='#333') {
						delete feature.properties.color;
					}
					if(feature.properties.description==='Description') {
						delete feature.properties.description;
					}
					$.ajax({
						headers : { "Content-Type": "application/json"},
						url : "https://api.mapbox.com/datasets/v1/"+$('#wp_mapbox_gl_js_username').val()+"/"+$('#wp_mapbox_gl_js_dataset_list').val()+"/features/"+feature.id+"?access_token="+$('#wp_mapbox_gl_js_secret_token').val(),
						method : "PUT",
						data : JSON.stringify(feature)
					}).done(function(data) {
						updatedFeatures.push(data);
						if(updatedFeatures.length===mapObject.mapData.length) {
							$('#wp-mapbox-gl-js-from-dataset-update-updated').fadeIn();
							setTimeout(function() {
								$('#publish').trigger('click');
							},1000);
						}
					});
				}
			});
		}
	});

	if($('#wp_mapbox_gl_js_access_token').length && $('#wp-mapbox-gl-js-dataset-map').length) {
		var access_token = $('#wp_mapbox_gl_js_access_token').val();
		mapboxgl.accessToken = access_token;
		var map = new mapboxgl.Map({
			style : 'mapbox://styles/mapbox/light-v9',
			container : 'wp-mapbox-gl-js-dataset-map'
		});

		// Load the features listed in the dataset
		map.on('load',function() {
			var features = [];
			var featureIDs = JSON.parse($('#wp_mapbox_gl_js_features').val());
			var bounds = new mapboxgl.LngLatBounds();
			var count = 0;
			featureIDs.forEach(function(feature, index) {
				$.ajax({
					url : "https://api.mapbox.com/datasets/v1/"+$('#wp_mapbox_gl_js_username').val()+"/"+$('#wp_mapbox_gl_js_dataset').val()+"/features/"+feature+"?access_token="+$('#wp_mapbox_gl_js_secret_token').val()
				}).done(function(data) {
					// features.push(data);
					count += 1;
					if(data.geometry.type==="Point") {
			    	bounds.extend(data.geometry.coordinates);
					}
					if(data.geometry.type==="LineString") {
						data.geometry.coordinates.forEach(function(coord) {
			    		bounds.extend(coord);
						})
					}
					if(data.geometry.type==="Polygon") {
						data.geometry.coordinates[0].forEach(function(coord) {
			    		bounds.extend(coord);
						})
					}
					map.addSource('imported-'+index, {
						type : 'geojson',
						data : {
							type : "FeatureCollection",
							features : [data]
						}
					})
					if(data.geometry.type==='Point') {
						map.addLayer({
							id : 'imported-'+index,
							source : 'imported-'+index,
	    				type: "circle"
						});
					}
					if(data.geometry.type==='LineString'||data.geometry.type==='MultiLineString') {
						map.addLayer({
							id : 'imported-'+index,
							source : 'imported-'+index,
	    				type: "line"
						});
					}
					if(data.geometry.type==='Polygon'||data.geometry.type==='MultiPolygon') {
						map.addLayer({
							id : 'imported-'+index,
							source : 'imported-'+index,
	    				type: "fill"
						});
					}
					map.fitBounds(bounds);
				});
			});
		});

		$(document).on('click','#wp-mapbox-gl-js-to-dataset-delete',function(e) {
			e.preventDefault();
			if(confirm('Are you sure you want to completely delete these features in Mapbox? This cannot be reversed. \n\n NOTE: it will take up to 10 minutes for Mapbox to show the changes in its dataset.')) {
				var updatedFeatures = [];
				var mapObject = JSON.parse($('#wp_mapbox_gl_js_map_object').val());
				mapObject.mapData.forEach(function(feature) {
					if(feature.id) {
						$.ajax({
							headers : { "Content-Type": "application/json"},
							url : "https://api.mapbox.com/datasets/v1/"+$('#wp_mapbox_gl_js_username').val()+"/"+$('#wp_mapbox_gl_js_dataset').val()+"/features/"+feature.id+"?access_token="+$('#wp_mapbox_gl_js_secret_token').val(),
							method : "DELETE"
						}).done(function(data) {
							updatedFeatures.push(data);
							if(updatedFeatures.length===mapObject.mapData.length) {
								$('#wp-mapbox-gl-js-to-dataset-update-deleted').fadeIn();
							}
						});
					}
				});
			}
		});

		$(document).on('click','#wp-mapbox-gl-js-to-dataset-update',function(e) {
			e.preventDefault();
			if(confirm('Are you sure you want to send the features from this WP GL JS post to Mapbox? This is not easily reversed. \n\n NOTE: it will take up to 10 minutes for Mapbox to show the changes in its dataset.')) {
				var updatedFeatures = [];
				var mapObject = JSON.parse($('#wp_mapbox_gl_js_map_object').val());
				mapObject.mapData.forEach(function(feature) {
					if(feature.id) {
						if(feature.properties.marker_icon_anchor==='bottom'||feature.properties.marker_icon_anchor==='center') {
							delete feature.properties.marker_icon_anchor;
						}
						if(feature.properties.popup_open) {
							delete feature.properties.popup_open;
						}
						if(feature.properties.marker_icon_url.indexOf('black_default.png')>-1) {
							delete feature.properties.marker_icon_url;
						}
						if(feature.properties.marker_title==='Line'||feature.properties.marker_title==='Fill'||feature.properties.marker_title==='Marker') {
							delete feature.properties.marker_title;
						}
						if(feature.properties.color==='#333') {
							delete feature.properties.color;
						}
						if(feature.properties.description==='Description') {
							delete feature.properties.description;
						}
						$.ajax({
							headers : { "Content-Type": "application/json"},
							url : "https://api.mapbox.com/datasets/v1/"+$('#wp_mapbox_gl_js_username').val()+"/"+$('#wp_mapbox_gl_js_dataset').val()+"/features/"+feature.id+"?access_token="+$('#wp_mapbox_gl_js_secret_token').val(),
							method : "PUT",
							data : JSON.stringify(feature)
						}).done(function(data) {
							updatedFeatures.push(data);
							if(updatedFeatures.length===mapObject.mapData.length) {
								$('#wp-mapbox-gl-js-to-dataset-update-updated').fadeIn();
							}
						});
					}
				});
			}
		});

		$(document).on('click','#wp-mapbox-gl-js-from-dataset-update',function(e) {
			e.preventDefault();
			if(confirm('Are you sure you want to overwrite the features in this WP GL JS post using the latest data in your Mapbox dataset?')) {
				var mapObject = JSON.parse($('#wp_mapbox_gl_js_map_object').val());
				var currentNumber = parseInt($('#wp-mapbox-gl-js-from-dataset-update-updated-number').text());
				var numberToMatch = 0;
				mapObject.mapData.forEach(function(feature) {
					if(feature.id) {
						numberToMatch += 1;
						$.ajax({
							url : "https://api.mapbox.com/datasets/v1/"+$('#wp_mapbox_gl_js_username').val()+"/"+$('#wp_mapbox_gl_js_dataset').val()+"/features/"+feature.id+"?access_token="+$('#wp_mapbox_gl_js_secret_token').val(),
						}).done(function(data) {
							mapObject.mapData.forEach(function(thisMapData) {
								if(thisMapData.id===data.id) {
									thisMapData.geometry = data.geometry;
									for (var property in data.properties) {
										thisMapData.properties[property] = data.properties[property];
									}
									currentNumber += 1;
									$('#wp-mapbox-gl-js-from-dataset-update-updated').fadeIn();
									$('#wp-mapbox-gl-js-from-dataset-update-updated-number').fadeIn();
									$('#wp-mapbox-gl-js-from-dataset-update-updated-number').text(currentNumber);
								}
							});
							if(currentNumber===numberToMatch) {
								setTimeout(function() {
									$('#wp_mapbox_gl_js_map_object').val(JSON.stringify(mapObject));
									$('#publish').trigger('click');
								},1000);
							}
						});
					}
				})
			}
		});
	}

})(jQuery);
