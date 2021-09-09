<div id="getting_started" class="avante-tab-pane">
    <div class="left">
        <div>
            <p><?php esc_html_e('Follow the steps below to setup Avante and begin customizing your website.', 'avante-lite'); ?></p>
            <h3><?php esc_html_e('Step 1 - Install Plugins', 'avante-lite'); ?></h3>
            <ol>
                <li><?php esc_html_e('Install', 'avante-lite'); ?> <a target="_blank" href="<?php echo esc_url('https://wordpress.org/plugins/avante-theme-extensions/'); ?>"><?php esc_html_e('Avante Theme Extensions', 'avante-lite'); ?></a> <?php esc_html_e('plugin', 'avante-lite'); ?></li>
                <li><?php esc_html_e('Install', 'avante-lite'); ?> <a target="_blank" href="<?php echo esc_url('https://wordpress.org/plugins/wpforms-lite/'); ?>"><?php esc_html_e('Contact Form by WPForms', 'avante-lite'); ?></a> <?php esc_html_e('plugin', 'avante-lite'); ?></li>
                <li><?php esc_html_e('Install', 'avante-lite'); ?> <a target="_blank" href="<?php echo esc_url('https://wordpress.org/plugins/theme-demo-import/'); ?>"><?php esc_html_e('Theme Demo Importer', 'avante-lite'); ?></a> <?php esc_html_e('plugin', 'avante-lite'); ?></li>
            </ol>
            <p>
                <?php if ( current_user_can( 'install_plugins' ) ) : ?>
                    <a class="button button-secondary" href="<?php echo esc_url('themes.php?page=tgmpa-install-plugins'); ?>"><?php esc_html_e('Install Plugins', 'avante-lite'); ?></a>
                <?php endif; ?>
            </p>
        </div>
        <div>
            <h3><?php esc_html_e('Step 2 - Configure One-Page Template', 'avante-lite'); ?></h3>
            <ol>
                <li><?php esc_html_e('Create or edit a page, and assign it the One-Page Template from the Page Attributes section.', 'avante-lite'); ?></li>
                <li><?php esc_html_e('Go to Settings > Reading and set "Front page displays" to "A static page".', 'avante-lite'); ?></li>
                <li><?php esc_html_e('Select the page you just assigned the One-Page Template to as "Front page" and then choose another page as "Posts page" to serve your blog posts.', 'avante-lite'); ?></li>
            </ol>
            <p><a class="button button-secondary" target="_blank" href="<?php echo esc_url('http://support.themely.com/knowledgebase/avante-configure-homepage-layout/'); ?>"><?php esc_html_e('Animated Instructions', 'avante-lite'); ?></a></p>
        </div>
        <div>
            <h3><?php esc_html_e('Step 3 - Import Demo Content (OPTIONAL)', 'avante-lite'); ?></h3>
            <p><?php esc_html_e('Speed up and simplify the website creation process by importing demo content and editing according to your needs.', 'avante-lite'); ?> <?php esc_html_e('Live Demo:', 'avante-lite'); ?> <a target="_blank" href="<?php echo esc_url('https://demo.themely.com/avante-lite/'); ?>">https://demo.themely.com/avante-lite/</a></p>
            <p><a class="button button-secondary" href="<?php echo esc_url('themes.php?page=theme-demo-import'); ?>"><?php esc_html_e('Import Demo Content', 'avante-lite'); ?></a></p>
        </div>
        <div>
            <h3><?php esc_html_e('Step 4 - Customize Your Website', 'avante-lite'); ?></h3>
            <p class="about"><?php esc_html_e('Click the button below to configure theme settings and start customizing your site.', 'avante-lite'); ?></p>
            <p>
                <?php if ( current_user_can( 'customize' ) ) : ?>
                    <a class="button button-primary button-hero" href="<?php echo esc_url( wp_customize_url() ); ?>"><?php esc_html_e('Customizer', 'avante-lite'); ?></a>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <div class="right">
        <div class="upgrade">
            <h3><?php esc_html_e('Upgrade to Avante Pro!', 'avante-lite' ); ?> <a target="_blank" href="<?php echo esc_url('https://demo.themely.com/avante-pro/'); ?>"><?php esc_html_e('View live demo', 'avante-lite'); ?></a></h3>
            <p class="about-text"><?php esc_html_e('Unlock additional sections, features & settings!', 'avante-lite'); ?></p>
            <p class="about-text red"><strong><?php esc_html_e('Save 15% with coupon code', 'avante-lite'); ?> <span class="border-red"><?php esc_html_e('THEMELY15', 'avante-lite'); ?></span></strong></p>
            <ul class="ul-square">
                <li><?php esc_html_e('Easily change font type, color and size for all sections (no coding required)', 'avante-lite'); ?></li>
                <li><?php esc_html_e('Change order of one-page sections (drag & drop)', 'avante-lite'); ?></li>
                <li><?php esc_html_e('Select full-width or boxed layout', 'avante-lite'); ?></li>
                <li><?php esc_html_e('UNLOCK 11 Additional One-Page Sections', 'avante-lite'); ?></li>
                <li><?php esc_html_e('UNLOCK Hero Section Image Slider', 'avante-lite'); ?></li>
                <li><?php esc_html_e('UNLOCK Featured Section', 'avante-lite'); ?></li>
                <li><?php esc_html_e('UNLOCK Team Members Section', 'avante-lite'); ?></li>
                <li><?php esc_html_e('UNLOCK Skills Section', 'avante-lite'); ?></li>
                <li><?php esc_html_e('UNLOCK Brands Section', 'avante-lite'); ?></li>
                <li><?php esc_html_e('UNLOCK Video Section', 'avante-lite'); ?></li>
                <li><?php esc_html_e('UNLOCK Showcase Section', 'avante-lite'); ?></li>
                <li><?php esc_html_e('UNLOCK Products/Woocommerce Section', 'avante-lite'); ?></li>
                <li><?php esc_html_e('UNLOCK Frequently Asked Questions Section', 'avante-lite'); ?></li>
                <li><?php esc_html_e('UNLOCK Support Section', 'avante-lite'); ?></li>
                <li><?php esc_html_e('UNLOCK Tweets Section', 'avante-lite'); ?></li>
                <li><?php esc_html_e('UNLOCK Instagram Section', 'avante-lite'); ?></li>
                <li><?php esc_html_e('UNLOCK Google Maps Section', 'avante-lite'); ?></li>
                <li><?php esc_html_e('MORE Theme Customizer Settings', 'avante-lite'); ?></li>
                <li><?php esc_html_e('MORE Widget Areas', 'avante-lite'); ?></li>
                <li><?php esc_html_e('MORE Custom Widgets', 'avante-lite'); ?></li>
                <li><?php esc_html_e('FREE Child Theme', 'avante-lite'); ?></li>
                <li><?php esc_html_e('No restrictions!', 'avante-lite'); ?></li>
                <li><?php esc_html_e('Priority support', 'avante-lite'); ?></li>
                <li><?php esc_html_e('Regular theme updates', 'avante-lite'); ?></li>
            </ul>
            <p>
                <a class="button button-primary button-hero" target="_blank" href="<?php echo esc_url('https://www.themely.com/themes/avante/'); ?>"><?php esc_html_e('UPGRADE NOW', 'avante-lite'); ?></a>
            </p>
        </div>
    </div>
</div>
<div class="clear"></div>