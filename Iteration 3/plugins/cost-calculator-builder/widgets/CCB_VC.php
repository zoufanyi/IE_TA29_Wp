<?php

$vendor = new CCB_VC();
add_action( 'vc_after_set_mode', array(
    $vendor,
    'load',
) );


class CCB_VC
{
    public function load() {
        vc_lean_map( 'stm-calc', array(
            $this,
            'ccb_vc_elements',
        ) );
    }

    function ccb_vc_elements()
    {
        $args = array(
            'post_type' => 'cost-calc',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );
        $data = new \WP_Query( $args );
        $data = $data->posts;

        $lists = array(esc_html__('Keep empty', 'cost-calculator-builder') => 'select');

        if(count($data) > 0)
            foreach ($data as $value)
                $lists[$value->post_title] = $value->ID;
        else
            $lists = array(esc_html__('No calculator was found', 'cost-calculator-builder') => 'select');

        return array(
            'base' => "cost_calculator_builder",
            'name' => esc_html__( 'Cost Calculator Builder', 'cost-calculator-builder' ),
            'icon' => CALC_URL . '/frontend/dist/img/icon-100x100.png' ,
            'category' => esc_html__( 'Content', 'cost-calculator-builder' ),
            'description' => esc_html__( 'Place Cost Calculator Builder', 'cost-calculator-builder' ),
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__( 'Select calculator', 'cost-calculator-builder' ),
                    'param_name' => 'id',
                    'value' => $lists,
                    'save_always' => true,
                    'description' => '',
                ),
            )
        );
    }
}