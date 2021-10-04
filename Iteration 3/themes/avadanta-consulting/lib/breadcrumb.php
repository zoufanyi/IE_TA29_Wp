<?php
 if( !function_exists('avadanta_breadcrumbs') ): function avadanta_breadcrumbs() {
$image = get_header_image();
  ?>
<div class="banner banner-inner tc-light brdcrmbs">
        <div class="banner-block">
          <div class="container">
   <div class="row">
               <div class="col-md-12">
                <div class="banner-content avata-center">
                  <?php if(is_home()): ?>
                            <h1 class="banner-heading" ><?php bloginfo('name'); ?></h1>
                            <?php else: ?>
                              <h1 class="banner-heading">
                                <?php if ( is_archive() ) {
                                  the_archive_title( '<h1 class="banner-heading">', '</h1>' );
                                }
                                 elseif(is_search()){

                                  echo  esc_html__('Search Results For ', 'avadanta-consulting') . ' " ' . get_search_query() . ' "';

                                 }elseif ( is_404() ) {
                                  echo  esc_html__('Nothing Found ', 'avadanta-consulting');
                                 }
                                 else{
                                  
                                    echo esc_html( get_the_title() );
                                    
                                  } 
                                 ?>
                              </h1>
                            <?php endif; 
                            ?>
                </div>
              </div>
                <div class="col-md-12">
                     <?php
                      $avadanta_header_show_breadcrumb =  get_theme_mod( 'avadanta_header_show_breadcrumb', 0 );
                      if($avadanta_header_show_breadcrumb==0){
                        ?>
                        <div class="header-bennar-right avata-center">
                            <?php avadanta_breadcrumb_trail(); ?>
                        </div>
                      <?php } ?>
                    </div>
            </div>
          </div>
          <div class="bg-image header-bg-image">
            <img src="<?php echo esc_url(get_header_image()); ?>" alt="<?php echo esc_attr__('banner','avadanta-consulting'); ?>">
          </div>
        </div>
        
      </div>
      <div id="content"></div>
<?php } endif; ?>