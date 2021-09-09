<?php
/**
 * Plugin Panel.
 *
 */
?>
<!-- Updates panel -->
<div id="plugins-panel" class="panel-left">
    <h4><?php _e( 'Recommended Plugins', 'avadanta' ); ?></h4>

    <p><?php _e( 'Install and activate Avadanta Companion plugin for taking full advantage of all the features this theme has to offer Avadanta.
', 'avadanta' ); ?></p>

    <hr/>

    <?php 
    $free_plugins = array(
                    
         
        'Avadanta Companion' => array(
            'slug'      => 'avadanta-companion',
            'filename'  => 'avadanta-companion.php',
        )
        
            
    );

    if( $free_plugins ){ ?>
        <h4 class="recomplug-title"><?php esc_html_e( 'Free Plugins', 'avadanta' ); ?></h4>
        <p><?php esc_html_e( 'These Free Plugins might be handy for you.', 'avadanta' ); ?></p>
        <div class="recomended-plugin-wrap">
            <?php
            foreach( $free_plugins as $plugin ) {
                $info     = avadanta_call_plugin_api( $plugin['slug'] );
                $icon_url = avadanta_check_for_icon( $info->icons ); ?>    
                <div class="recom-plugin-wrap">
                    <div class="plugin-img-wrap">
                        <img src="<?php echo esc_url( $icon_url ); ?>" />
                        <div class="version-author-info">
                            <span class="version"><?php printf( esc_html__( 'Version %s', 'avadanta' ), $info->version ); ?></span>
                            <span class="seperator">|</span>
                            <span class="author"><?php echo esc_html( strip_tags( $info->author ) ); ?></span>
                        </div>
                    </div>
                    <div class="plugin-title-install clearfix">
                        <span class="title" title="<?php echo esc_attr( $info->name ); ?>">
                            <?php echo esc_html( $info->name ); ?>  
                        </span>
                        <div class="button-wrap">
                           <?php echo Avadanta_Getting_Started_Page_Plugin_Helper::instance()->get_button_html( $plugin['slug'] ); ?>
                        </div>
                    </div>
                </div>
                <?php
            } 
            ?>
        </div>
    <?php
    } 
?>
</div><!-- .panel-left -->