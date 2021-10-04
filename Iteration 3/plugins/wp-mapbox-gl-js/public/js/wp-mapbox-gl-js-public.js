(function($) {
  /* map initialization */
  $(document).ready(function() {
    if($('.wp-mapbox-gl-js-map').length) {
      var access_token = $('.wp-mapbox-gl-js-map').first().data('token');
      mapboxgl.accessToken = access_token;

      var allMaps = {};
      $('.wp-mapbox-gl-js-map').each(function() {
         var data = $(this).data();
         var map = false;
        //  console.log(data)
        //  window._data = data;
         var mapStyle = {
           'container' : $(this).attr('id'),
           'style' : data.style
         };
         var baseSettings = ['center','zoom','pitch','bearing',];
         baseSettings.forEach(function(setting) {
           if(data[setting]!=='') {
             if(setting==='center') {
               mapStyle[setting] = data[setting].split(',');
             } else {
               mapStyle[setting] = parseFloat(data[setting]);
             }
           }
         });
         // Change size of the div if store locator turned on
         if(data.controls.locationSidebar) {
           var originalWidth = $(this).width();
           var newDivWrap = document.createElement('div');
           $(this).attr('style', function(i, style) {
              return style && style.replace(/width[^;]+;?/g, '');
           });
           $(this).css('width',originalWidth*0.8);
           $(this).css('float','right');
           $(this).wrap(newDivWrap);
           var locationSidebarDiv = document.createElement('div');
           locationSidebarDiv.className = 'wp-mapbox-gl-js-location-sidebar';
           locationSidebarDiv.style.width = originalWidth*0.19+'px';
           locationSidebarDiv.style.height = $(this).height()+'px';
           locationSidebarDiv.style.float = 'left';
           $(locationSidebarDiv).append('<p><strong>Locations</strong></p>');
           var thisLocationUL = document.createElement('ul');
           data.mapdata.forEach(function(feature, index) {
             var bounds = getBoundingBox(feature);
             var thisLocationLI = document.createElement('li');
             thisLocationLI.setAttribute('data-bounds', JSON.stringify(bounds));
             thisLocationLI.textContent = feature.properties.marker_title;
             thisLocationLI.addEventListener('click', function() {
               if($(this).data().bounds[0][0]===$(this).data().bounds[1][0]&$(this).data().bounds[0][1]===$(this).data().bounds[1][1]) {
                 map.flyTo({
                   center : $(this).data().bounds[0],
                   zoom : 13
                 });
               } else {
                 map.fitBounds($(this).data().bounds)
               }
             });
             thisLocationUL.appendChild(thisLocationLI);
           });
           $(locationSidebarDiv).append(thisLocationUL);
           $(this).parent().prepend(locationSidebarDiv)
         }

         map = new mapboxgl.Map(mapStyle);
         allMaps[$(this).attr('id')] = map;

         map.on('load',function() {

          // console.log('DATA.CONTROLS', data.controls)
            // Add controls
           if(data.controls.navigation) {
             var navControl = new mapboxgl.NavigationControl();
             map.addControl(navControl);
           }
           if(data.controls.geocoder) {
             var geocoder = new MapboxGeocoder({
                 accessToken: access_token
             });
             map.addControl(geocoder,'top-left');
           }
           if(data.controls.scale) {
             var scale = new mapboxgl.ScaleControl({
               maxWidth: 80,
               unit: 'metric'
             })
             map.addControl(scale,'top-left');
           }
           if(data.controls.fullscreen) {
             var fullscreen = new mapboxgl.FullscreenControl();
             map.addControl(fullscreen,'top-right');
           }
           if(data.controls.directions) {
             var mapboxDirections = new MapboxDirections({
              accessToken: mapboxgl.accessToken,
              interactive: false
             });

             window['_mapboxDirections'] = mapboxDirections;
             map.addControl(mapboxDirections, 'top-left');
           }
          if(data.controls.directions && data.controls.preFillInput && window['_mapboxDirections']) {
            window['_mapboxDirections'].setOrigin(data.mapOrigin);
            window['_mapboxDirections'].setDestination(data.mapDestination);
          }

           // scrollZoom setting
           if (data.scrollZoom === false) {
             map.scrollZoom.disable();
           } else if (data.scrollZoom === true) {
             map.scrollZoom.enable();

           }

           if (data.controls.geolocaterControl) {
            const geolocaterControl = new mapboxgl.GeolocateControl({
              positionOptions: { enableHighAccuracy: true },
              trackUserLocation: true
            });
            map.addControl(geolocaterControl);
           }

          //  Layer filter
          if (data.controls.layerFilter) {
            const layerControl = new CustomLayerControl({
             layers : data.mapLayersFilter
            });
            map.addControl(layerControl, 'top-left');
          }

          //  Category filter
          if (data.controls.categoryFilter) {
             const categoryControl = new CustomCategoryControl({
              categories : data.mapCategories,
              mapData : data.mapdata
             });
             map.addControl(categoryControl, 'top-left');
           }

           // Map data add
           data.mapdata.forEach(function(feature, index) {
             var featureCollection = {
               "type" : "FeatureCollection",
               "features" : [feature]
             }
             // If only a marker icon or color has changed
             if(typeof map.getSource(feature.id)==='undefined') {
               if(typeof feature.properties.opacity === 'undefined') {
                 feature.properties.opacity = 0.4;
               }
               map.addSource(feature.id, {
                 "type": "geojson",
                 "data": featureCollection
               });
               if(feature.geometry.type==='Point') {
                 // Add the icon image, then add layer
                 if(!map.hasImage(feature.properties.marker_icon_url)) {
                   if(feature.properties.marker_icon_url.indexOf('http') === -1) {
                     map.loadImage($('#wp_mapbox_gl_js_plugin_url').val()+'/wp-mapbox-gl-js/admin/wp-mapmaker/public/img/'+feature.properties.marker_icon_url, function(error, image) {
                       if (error) throw error;
                       map.addImage(feature.properties.marker_icon_url, image);
                       // default_marker.svg
                       var lineLayer = map.addLayer({
                         'id': feature.id,
                         'type': 'symbol',
                         'source' : feature.id,
                         'layout': {
                           'icon-image': feature.properties.marker_icon_url,
                           'icon-anchor' : 'bottom',
                           'icon-size' : 0.2
                         }
                       })
                       if(feature.properties.popup_open) {
                         var popup = new mapboxgl.Popup({
                           offset : 20
                         });
                         popup.setLngLat({lat: feature.geometry.coordinates[1], lng: feature.geometry.coordinates[0]})
                           .setHTML(
                             '<div>'+
                               '<div>'+feature.properties.description+'</div>'+
                             '</div>'
                           ).addTo(map);
                       }
                     });
                   } else {
                     var canvas = document.createElement('canvas');
                     var ctx = canvas.getContext('2d');
                     var img  = document.createElement('img');
                     img.onload = function() {
                       var sizingOfImage = feature.properties.marker_icon_url.split('-wp_mapbox_gl_js_sizing-')[1];
                       var widthHeight = sizingOfImage ? sizingOfImage.split('-') : [100,100];
                       canvas.width  = widthHeight[0];
                       canvas.height = widthHeight[1];
                       ctx.drawImage(img, 0, 0, widthHeight[0], widthHeight[1]);
                       map.addImage(feature.properties.marker_icon_url, ctx.getImageData(0, 0, widthHeight[0], widthHeight[1]) );
                       var markerOverlap = true;
                       if(typeof data.controls.markerNoDisappear !== 'undefined') {
                         markerOverlap = data.controls.markerNoDisappear;
                       }
                       var lineLayer = map.addLayer({
                         'id': feature.id,
                         'type': 'symbol',
                         'source' : feature.id,
                         'layout': {
                           'icon-image': feature.properties.marker_icon_url,
                           'icon-anchor' : feature.properties.marker_icon_anchor,
                           'icon-size' : 0.2,
                           'icon-allow-overlap' : markerOverlap
                         }
                       })
                       if(feature.properties.popup_open) {
                         var popup = new mapboxgl.Popup({
                           offset : 20,
                           className: 'wp_mapbox_gl_js_frontend_popup'
                        });
                         popup.setLngLat({lat: feature.geometry.coordinates[1], lng: feature.geometry.coordinates[0]})
                           .setHTML(
                             '<div>'+
                               '<div>'+feature.properties.description+'</div>'+
                             '</div>'
                           ).addTo(map);

                          popup._container.style.maxWidth = "66%";
                        }
                     };
                     img.src = feature.properties.marker_icon_url.split('-wp_mapbox_gl_js_sizing-')[0];
                   }
                 } else {
                   var lineLayer = map.addLayer({
                     'id': feature.id,
                     'type': 'symbol',
                     'source' : feature.id,
                     'layout': {
                       'icon-image': feature.properties.marker_icon_url,
                       'icon-anchor' : 'bottom',
                       'icon-size' : 0.2
                     }
                   })
                 }
                 if(feature.properties.description!=='') {
                   map.on('click',feature.id,function(e) {
                     var popup = new mapboxgl.Popup({
                      offset : 20,
                      className: 'wp_mapbox_gl_js_frontend_popup'
                    })
                     popup.setLngLat({lat: feature.geometry.coordinates[1], lng: feature.geometry.coordinates[0]})
                       .setHTML(
                         '<div>'+
                           '<div>'+feature.properties.description+'</div>'+
                         '</div>'
                       ).addTo(map);

                       popup._container.style.maxWidth = "66%";
                      })
                 }
               } else if(feature.geometry.type==='Polygon') {
                 if(feature.fillType) {
                   var lineLayer = map.addLayer({
                     'id': feature.id,
                     'type': 'fill-extrusion',
                     'source' : feature.id,
                     'paint': {
                       'fill-extrusion-color' : feature.properties.color,
                       'fill-extrusion-opacity' : feature.properties.opacity,
                       'fill-extrusion-height' : feature.properties.height,
                       'fill-extrusion-base' : feature.properties.base_height
                     }
                   })
                 } else {
                   var lineLayer = map.addLayer({
                     'id': feature.id,
                     'type': 'fill',
                     'source' : feature.id,
                     'paint': {
                       'fill-color' : feature.properties.color,
                       'fill-opacity' : feature.properties.opacity
                     }
                   })
                 }
               } else if(feature.geometry.type==='LineString') {
                 var lineLayer = map.addLayer({
                   'id': feature.id,
                   'type': 'line',
                   'source' : feature.id,
                   'paint': {
                     'line-color' : feature.properties.color
                   }
                 })
               }
             }
          });
        });
      });

      $(document).on('click','.wp-mapbox-gl-js-map-menu input',function() {
        allMaps[$(this).data('map-id')].setStyle($(this).attr('id'));
      });
      $(document).on('click','#wp_mapbox_gl_js_set_directions',function(e) {
        e.preventDefault();
        var coords = $(this).data('lngLat');
        window._mapboxDirections.setDestination(coords);
      });
    }

    // Custom Layer Control Class
    class CustomLayerControl {
      // Commented b/c it was throwing errors
      // _options: {
      //   layers: []
      // };

      constructor(options) {
        const _options = { layers: [] };

        this._options = Object.assign({}, _options, options)
      }

      addHeading() {
        var heading = document.createElement('h3');
        heading.appendChild(document.createTextNode('Layer Filter'));
        return heading;
      }

      onAdd(map){
        this.map = map;
        this.container = document.createElement('div');
        this.container.className = 'mapboxgl-ctrl wp-mapbox-gl-js-custom-layer-control';
        this.container.appendChild(this.addHeading());

        var thisCategoryDiv = this._addLayers(this._options.layers);
        this.container.appendChild(thisCategoryDiv);
        return this.container;
      }

      onRemove(){
        this.container.parentNode.removeChild(this.container);
        this.map = undefined;
      }

      _addLayers(layers) {
        const el = window.document.createElement('div')
        layers.forEach(function(layer) {
          const innerElement = window.document.createElement('div')
          const input = window.document.createElement('input')
          input.type = "checkbox";
          input.checked = "checked";
          input.name = layer;
          input.value = layer;
          input.id = 'wp-mapbox-gl-js-'+layer;
          input.addEventListener('change',(e)=>{
            if(e.target.checked) {
              this.map.setLayoutProperty(layer,'visibility','visible');
            } else {
              this.map.setLayoutProperty(layer,'visibility','none');
            }
          });
          innerElement.appendChild(input)

          var label = document.createElement('label')
          label.htmlFor = 'wp-mapbox-gl-js-'+layer;;
          label.appendChild(document.createTextNode(layer));
          innerElement.appendChild(label)

          el.appendChild(innerElement);
        }.bind(this));
        return el;
      }

      updateLayers(layers) {
        this.container.innerHTML = '';
        this.container.appendChild(this.addHeading());
        var thisLayerDiv = this._addLayers(layers);
        this.container.appendChild(thisLayerDiv);
      }

    }

    // Custom Category Control Class
    class CustomCategoryControl {
      // _options: {
      //   categories: [],
      //   mapData : []
      // };

      constructor(options) {
        const _options = { categories: [], mapData : [] };

        this._options = Object.assign({}, _options, options);
      }

      addHeading() {
        var heading = document.createElement('h3');
        heading.appendChild(document.createTextNode('Filter'));
        return heading;
      }

      onAdd(map){
        this.map = map;
        this.container = document.createElement('div');
        this.container.className = 'mapboxgl-ctrl wp-mapbox-gl-js-custom-category-control';
        this.container.appendChild(this.addHeading());

        var thisCategoryDiv = this._addCategories(this._options.categories);
        this.container.appendChild(thisCategoryDiv);
        return this.container;
      }

      onRemove(){
        this.container.parentNode.removeChild(this.container);
        this.map = undefined;
      }

      _addCategories(categories) {
        const el = window.document.createElement('div')
        categories.forEach(function(category) {
          const innerElement = window.document.createElement('div')
          const input = window.document.createElement('input')
          input.type = "checkbox";
          input.checked = "checked";
          input.name = category;
          input.value = category;
          input.id = 'wp-mapbox-gl-js-'+category;
          input.addEventListener('change',(e)=>{
            if(e.target.checked) {
              this._options.mapData.forEach(function(feature) {
                if(feature.properties.category===e.target.value) {
                  this.map.setLayoutProperty(feature.id,'visibility','visible');
                }
              }.bind(this));
            } else {
              this._options.mapData.forEach(function(feature) {
                if(feature.properties.category===e.target.value) {
                  this.map.setLayoutProperty(feature.id,'visibility','none');
                }
              }.bind(this));
            }
          });
          innerElement.appendChild(input)

          var label = document.createElement('label')
          label.htmlFor = 'wp-mapbox-gl-js-'+category;;
          label.appendChild(document.createTextNode(category));
          innerElement.appendChild(label)

          el.appendChild(innerElement);
        }.bind(this));
        return el;
      }

      updateCategories(categories) {
        this.container.innerHTML = '';
        this.container.appendChild(this.addHeading());
        var thisCategoryDiv = this._addCategories(categories);
        this.container.appendChild(thisCategoryDiv);
      }

      updateMapData(mapData) {
        this._options.mapData = mapData;
      }
    }
  });

  function getBoundingBox(data) {
    var bounds = {}, coords, point, latitude, longitude;

    coords = data.geometry.coordinates;

    if(!isNaN(coords[0])) {
      longitude = coords[0];
      latitude = coords[1];
      bounds = []
      bounds.xMin = bounds.xMin < longitude ? bounds.xMin : longitude;
      bounds.xMax = bounds.xMax > longitude ? bounds.xMax : longitude;
      bounds.yMin = bounds.yMin < latitude ? bounds.yMin : latitude;
      bounds.yMax = bounds.yMax > latitude ? bounds.yMax : latitude;
    } else if(!isNaN(coords[0][0])) {
      for (var j = 0; j < coords.length; j++) {
        longitude = coords[j][0];
        latitude = coords[j][1];
        bounds.xMin = bounds.xMin < longitude ? bounds.xMin : longitude;
        bounds.xMax = bounds.xMax > longitude ? bounds.xMax : longitude;
        bounds.yMin = bounds.yMin < latitude ? bounds.yMin : latitude;
        bounds.yMax = bounds.yMax > latitude ? bounds.yMax : latitude;
      }
    } else {
      for (var j = 0; j < coords[0].length; j++) {
        longitude = coords[0][j][0];
        latitude = coords[0][j][1];
        bounds.xMin = bounds.xMin < longitude ? bounds.xMin : longitude;
        bounds.xMax = bounds.xMax > longitude ? bounds.xMax : longitude;
        bounds.yMin = bounds.yMin < latitude ? bounds.yMin : latitude;
        bounds.yMax = bounds.yMax > latitude ? bounds.yMax : latitude;
      }
    }
    var boundsToReturn = [[bounds.xMin, bounds.yMin], [bounds.xMax, bounds.yMax]];
    return boundsToReturn;
  }
})(jQuery);
