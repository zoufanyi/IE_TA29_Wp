<?php

  require_once("../../../../../wp-load.php");

  $geojson = array(
    'type' => 'FeatureCollection',
    'features' => array()
  );
  if($_GET['gl_js_maps_category']==='all') {
    $args = array(
      'post_type'  => 'gl_js_maps',
      'posts_per_page' => -1
    );
  } else {
    $args = array(
      'post_type'  => 'gl_js_maps',
      'tax_query' => array(
        array(
          'taxonomy' => 'gl_js_maps_category',
          'field'    => 'slug',
          'terms'    => strtolower($_GET['gl_js_maps_category'])
        )
      ),
      'posts_per_page' => -1
    );
  }
  $query = new WP_Query( $args );
  if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
      $query->the_post();
      $geojsonFeatures = json_decode(get_post_meta(get_the_ID(),'wp_mapbox_gl_js_map_object')[0]);
      $post = get_post(get_the_ID());
      foreach($geojsonFeatures->mapData as $feature) {
        $feature->properties->link = get_permalink(get_the_ID());
        $feature->properties->title = html_entity_decode(get_the_title(get_the_ID()));
        $feature->properties->slug = $post->post_name;
        unset($feature->properties->marker_icon_url);
        unset($feature->properties->marker_icon_anchor);
        unset($feature->properties->popup_open);
        array_push($geojson['features'],$feature);
      }

    }
    wp_reset_postdata();
  }

  echo json_encode($geojson);

?>
