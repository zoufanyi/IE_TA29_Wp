<?php

// Exit if accessed directly.
if ( ! class_exists( 'WP_Customize_Control' ) )
    return NULL;
/**
 * Typography control
 */
class AvadantaWP_Customizer_Typography_Control extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'avadanta-typography';

	/**
	 * Render the control's content.
	 * Allows the content to be overriden without having to rewrite the wrapper in $this->render().
	 *
	 * @access protected
	 */
	protected function render_content() {
		$this_val = $this->value(); ?>
		<label>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>

			<select class="avadanta-typography-select" <?php $this->link(); ?>>
				<?php
				// Get Standard font options
				if ( $std_fonts = avadanta_standard_fonts() ) { ?>
					<optgroup label="<?php esc_attr_e( 'Fonts List', 'avadanta' ); ?>">
						<?php
						// Loop through font options and add to select
						foreach ( $std_fonts as $font ) { ?>
							<option value="<?php echo esc_html( $font ); ?>" <?php selected( $font, $this_val ); ?>><?php echo esc_html( $font ); ?></option>
						<?php } ?>
					</optgroup>
				<?php } 
				if ( $google_fonts = avadanta_google_fonts_array() ) { ?>
					<optgroup label="<?php esc_attr_e( 'Google Fonts', 'avadanta' ); ?>">
						<?php
						// Loop through font options and add to select
						foreach ( $google_fonts as $font ) { ?>
							<option value="<?php echo esc_attr( $font ); ?>" <?php selected( $font, $this_val ); ?>><?php echo esc_html( $font ); ?></option>
						<?php } ?>
					</optgroup>
				<?php } ?>
				
			</select>

		</label>

		<?php
	}
}

    /**
     * Section Re-order
    */  
    class Avadanta_Section_Re_Order extends WP_Customize_Control {
      
      public $type = 'dragndrop';
        /**
         * Render the content of the category dropdown
         *
         * @return HTML
         */
        public function render_content() {

        if ( empty( $this->choices ) ){
          return;
        }
        ?>
        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

              <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
        <ul class="controls" id ="tm-sections-reorder">
          <?php
              $default_short_array = array();
              foreach ( $this->choices as $value => $label ) 
              {

                  $default_short_array[$value] = $label;

              }

              $order_save_value = get_theme_mod( $this->id );
              
          if( !empty( $order_save_value ) ) {

            $order_save_array = explode( ',' , $order_save_value);

            $order_save_array_pop = array_pop( $order_save_array );

            foreach ($order_save_array as $key => $value) {
          ?>
            <li class="tm-section-element" data-section-name="<?php echo esc_attr( $value );?>" id="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $default_short_array[$value] ); ?></li>
          <?php 

            }
            $section_order_list = $order_save_value;

          } else {
          $order_array = array();
          foreach ( $this->choices as $value => $label ) {
            $order_array[] = $value;            
          ?>
            <li class="tm-section-element" data-section-name="<?php echo esc_attr( $value );?>" id="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></li>
          <?php }
              $section_order_list = implode ( "," , $order_array );
            }
          ?>
          <input id="shortui-order" type="hidden" <?php $this->link(); ?> value="<?php echo wp_kses_post( $section_order_list ); ?>" />  
        </ul>        
        <?php
      }
    } 