<?php
/**
 * Update calculator
 * @param $data array
 * @return boolean
 */
function ccb_update_calc_values($data)
{
    if (isset($data['id'])) {
        $title = !empty($data['title']) ? sanitize_text_field( $data['title'] ) : __('empty name', 'cost-calculator-builder');

        wp_update_post(['ID' => $data['id'], 'post_title' => $title]);
        update_option('stm_ccb_form_settings_' . sanitize_text_field($data['id']), apply_filters('stm_ccb_sanitize_array', $data['settings']));

        update_post_meta($data['id'], 'stm-name', $title);
        update_post_meta($data['id'], 'stm-formula', !empty($data['formula']) ? apply_filters('stm_ccb_sanitize_array', $data['formula']) : []);
        update_post_meta($data['id'], 'stm-fields', !empty($data['builder']) ? apply_filters('stm_ccb_sanitize_array', $data['builder']) : []);
        update_post_meta($data['id'], 'stm-conditions', !empty($data['conditions']) ? apply_filters('stm_ccb_sanitize_array', $data['conditions']) : []);

        $woo_products_enabled = isset($data['settings']['woo_products']['enable']) && filter_var($data['settings']['woo_products']['enable'], FILTER_VALIDATE_BOOLEAN);
        ccb_update_woocommerce_calcs($data['id'], !$woo_products_enabled);

        $styles = !empty(get_post_meta($data['id'], 'ccb-custom-styles', true))
            ? get_post_meta($data['id'], 'ccb-custom-styles', true)
            : \cBuilder\Classes\CCBCustomFields::custom_default_styles();
        if ( ! empty($data['styles']) ) {
            $styles = $data['styles'];
        }

        $fields = !empty(get_post_meta($data['id'], 'ccb-custom-fields', true))
            ? get_post_meta($data['id'], 'ccb-custom-fields', true)
            : \cBuilder\Classes\CCBCustomFields::custom_fields();

        if ( ! empty($data['fields']) ) {
            $fields = $data['fields'];
        }

        update_post_meta($data['id'], 'ccb-custom-styles', apply_filters('stm_ccb_sanitize_array', $styles));
        update_post_meta($data['id'], 'ccb-custom-fields',  apply_filters('stm_ccb_sanitize_array', $fields));
        return true;
    }

    return false;
}

/**
 * @param $calc_id
 * @param boolean $delete
 */
function ccb_update_woocommerce_calcs($calc_id, $delete = false) {
    $woocommerce_calcs = get_option('stm_ccb_woocommerce_calcs', []);
    if ( $delete ) {
        if ( ($key = array_search($calc_id, $woocommerce_calcs)) !== false ) {
            unset($woocommerce_calcs[$key]);
        }
    } elseif ( ! in_array($calc_id, $woocommerce_calcs) ) {
        $woocommerce_calcs[] = $calc_id;
    }

    update_option('stm_ccb_woocommerce_calcs', apply_filters('stm_ccb_sanitize_array', $woocommerce_calcs));
}

/**
 *  Get All Calculators
 * @param $post_type string
 * @return mixed|array
 */
function ccb_calc_get_all_posts($post_type)
{
    $args = array(
        'posts_per_page' => -1,
        'post_type' => $post_type,
        'post_status' => array('publish')
    );

    $resources = new WP_Query($args);

    $resources_json = array();

    if ($resources->have_posts()) {
        while ($resources->have_posts()) {
            $resources->the_post();
            $id = get_the_ID();
            $resources_json[$id] = get_the_title();
        }
    }

    return $resources_json;
}


/**
 * Parse settings by $calc_id
 * @param $settings
 * @return array
 */

function ccb_parse_settings($settings)
{
    $currency  = isset($settings['currency']['currency']) ? $settings['currency']['currency'] : '$';

    return  [
        'currency'            => $currency,
        'num_after_integer'   => isset($settings['currency']['num_after_integer'])   ? $settings['currency']['num_after_integer'] : 2,
        'decimal_separator'   => isset($settings['currency']['decimal_separator'])   ? $settings['currency']['decimal_separator'] : '.',
        'thousands_separator' => isset($settings['currency']['thousands_separator']) ? $settings['currency']['thousands_separator'] : ',',
        'currency_position'   => isset($settings['currency']['currencyPosition'])    ? $settings['currency']['currencyPosition'] : 'left_with_space',
    ];
}

/**
 * WooCommerce Products
 * @return array
 */
function ccb_woo_products()
{
    return get_posts([
        'post_type' => 'product',
        'posts_per_page' => -1
    ]);
}

/**
 * WooCommerce Categories
 * @return array
 */
function ccb_woo_categories()
{
    return get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => false
    ]);
}

/**
 * Contact Form 7 Forms
 * @return array
 */
function ccb_contact_forms()
{
    $contactForms = get_posts([
        'post_type' => 'wpcf7_contact_form',
        'posts_per_page' => -1
    ]);

    $forms = [];
    if (count($contactForms)) {
        foreach ($contactForms as $contactForm) {
            $forms[] = [
                'id' => $contactForm->ID,
                'title' => $contactForm->post_title
            ];
        }
    }

    return $forms;
}

/**
 * Check active Add-on
 * @return bool
 */
function ccb_pro_active()
{
    return (defined("CCB_PRO_VERSION")) ? true : false;
}

function ccb_all_calculators()
{
    $lists = array(esc_html__('select', 'cost-calculator-builder') => 'Select');
    $args = array(
        'post_type' => 'cost-calc',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );
    $data = new \WP_Query( $args );
    $data = $data->posts;

    if(count($data) > 0)
        foreach ($data as $value)
            $lists[$value->ID] = $value->post_title;

    return $lists;
}

/**
 * Write to log
 * @param $log
 * @return void
 */
function ccb_write_log($log) {
    if (true === WP_DEBUG) {
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}

/**
 * Return Support Ticket URL
 * @return string
 */
function ccb_get_ticket_url() {
    $type = ccb_pro_active() ? 'support' : 'pre-sale';

    return "https://support.stylemixthemes.com/tickets/new/{$type}?item_id=29";
}

/** Base helper functions */


/**
 * @param string $jsonString
 */
function is_json_string( $jsonString ) {
	json_decode($jsonString);
	return (json_last_error() == JSON_ERROR_NONE) ? true : false;
}


/**
 * sanitize_text_field without < replacement
 * @param string $jsonString
 */
function sanitize_without_tag_clean( $jsonString ) {
	$result = str_replace('<', '{less}', $jsonString);
	$result = sanitize_text_field($result);
	$result = str_replace('{less}', '<', $result);

	return $result;
}