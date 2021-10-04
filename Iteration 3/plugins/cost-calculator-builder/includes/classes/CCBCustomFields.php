<?php

namespace cBuilder\Classes;

class   CCBCustomFields {
    public static function custom_fields()
    {
        $data = [
            "v-container" => [
                "name"    => "v-container",
                "fields"  => [
                    self::generate_width("Width", "width", 0, 100, 1, 47.5, '%'),
                    self::generate_bg_color("Container-background", "background-color", "#eff4f4", "#eff4f4", "#eff4f4"),
                    self::generate_border(0, 100, 1, 0, 0, 0, 0, 0, '0', '#ffffff', 'px'),
                    self::generate_border_radius(0, 100, 1, 8, 8, 8, 8, 8, null, null, 'px'),
                    self::generate_box_shadow( self::container_box_shadow() ),
                    self::generate_indentations("Margin", "margin","0px", "0px", "0px", "0px"),
                    self::generate_indentations("Padding", "padding", "50px", "50px", "50px", "50px"),
                ],
            ],

            "h-container" => [
                "name"    => "h-container",
                "fields"  => [
                    self::generate_width("Width", "width",0, 100, 1, 100, '%'),
                    self::generate_bg_color("Container-background", "background-color", "#eff4f4", "#eff4f4", "#eff4f4"),
                    self::generate_border(0, 100, 1, 0, 0, 0, 0, 0, '0', '#ffffff', 'px'),
                    self::generate_border_radius(0, 100, 1, 8, 8, 8, 8, 8, null, null, 'px'),
                    self::generate_box_shadow( self::container_box_shadow() ),
                    self::generate_indentations("Margin", "margin","0px", "0px", "0px", "0px"),
                    self::generate_indentations("Padding", "padding", "50px", "50px", "50px", "50px"),
                ],
            ],

            "headers" => [
                "name"   => "headers",
                "fields" => [
                    self::generate_text_settings(
                        [
                            'label'     => 'Text-color',
                            'value'     => '#000000',
                        ],
                        [
                            'label'     => 'Font-size',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 22,
                            'dimension' => 'px'
                        ],
                        [
                            'label'     => 'Letter-spacing',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 0,
                            'dimension' => 'px'
                        ],
                        [
                            "blur"        => ["min" =>  0,  "max" => 20, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "opacity"     => ["min" =>  0,  "max" => 1,  "step" => 0.01, "value" => 0, "dimension" => "px"],
                            "shift_right" => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "shift_down"  => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "color"       => '#ffffff',
                        ],
                        [
                            "value" => '700',
                        ],
                        [
                            "value" => 'normal'
                        ]
                    ),
                ]
            ],

            "labels"     => [
                "name"   => "labels",
                "fields" => [
                    self::generate_text_settings(
                        [
                            'label'     => 'Text-color',
                            'value'     => '#000000',
                        ],
                        [
                            'label'     => 'Font-size',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 14,
                            'dimension' => 'px'
                        ],
                        [
                            'label'     => 'Letter-spacing',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 0,
                            'dimension' => 'px'
                        ],
                        [
                            "blur"        => ["min" =>  0,  "max" => 20, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "opacity"     => ["min" =>  0,  "max" => 1,  "step" => 0.01, "value" => 0, "dimension" => "px"],
                            "shift_right" => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "shift_down"  => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "color"       => '#ffffff',
                        ],
                        [
                            "value" => '400',
                        ],
                        [
                            "value" => 'normal'
                        ]
                    ),
                    self::generate_indentations("Margin","margin", "0px", "0px", "8px", "0px"),
                    self::generate_indentations("Padding", "padding", "0px", "0px", "0px", "0px"),
                ]

            ],

            "descriptions" => [
                "name"     => "descriptions",
                "fields"   => [
                    self::generate_text_settings(
                        [
                            'label'     => 'Text-color',
                            'value'     => '#a29f9f',
                        ],
                        [
                            'label'     => 'Font-size',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 12,
                            'dimension' => 'px'
                        ],
                        [
                            'label'     => 'Letter-spacing',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 0,
                            'dimension' => 'px'
                        ],
                        [
                            "blur"        => ["min" =>  0,  "max" => 20, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "opacity"     => ["min" =>  0,  "max" => 1,  "step" => 0.01, "value" => 0, "dimension" => "px"],
                            "shift_right" => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "shift_down"  => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "color"       => '#ffffff',
                        ],
                        [
                            "value" => '400',
                        ],
                        [
                            "value" => 'normal',
                        ]
                    ),
                    self::generate_indentations("Margin","margin",  "3px", "0px", "3px", "0px"),
                    self::generate_indentations("Padding", "padding", "0px", "0px", "0px", "0px"),
                ]

            ],

            "total-summary" => [
                "name"   => "total-summary",
                "fields" => [
                    self::generate_text_settings(
                        [
                            'label'     => 'Text-color',
                            'value'     => '#000000',
                        ],
                        [
                            'label'     => 'Font-size',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 16,
                            'dimension' => 'px'
                        ],
                        [
                            'label'     => 'Letter-spacing',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 0,
                            'dimension' => 'px'
                        ],
                        [
                            "blur"        => ["min" =>  0,  "max" => 20, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "opacity"     => ["min" =>  0,  "max" => 1,  "step" => 0.01, "value" => 0, "dimension" => "px"],
                            "shift_right" => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "shift_down"  => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "color"       => '#ffffff',
                        ],
                        [
                            "value" => '400',
                        ],
                        [
                            "value" => 'normal'
                        ]
                    ),
                ],
            ],

            "total" => [
                "name"   => "total",
                "fields" => [
                    self::generate_text_settings(
                        [
                            'label'     => 'Text-color',
                            'value'     => '#000000',
                        ],
                        [
                            'label'     => 'Font-size',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 16,
                            'dimension' => 'px'
                        ],
                        [
                            'label'     => 'Letter-spacing',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 0,
                            'dimension' => 'px'
                        ],
                        [
                            "blur"        => ["min" =>  0,  "max" => 20, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "opacity"     => ["min" =>  0,  "max" => 1,  "step" => 0.01, "value" => 0, "dimension" => "px"],
                            "shift_right" => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "shift_down"  => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "color"       => '#000000',
                        ],
                        [
                            "value" => '700',
                        ],
                        [
                            "value" => 'normal'
                        ],
                        [
                            'name'  => 'text-align',
                            'value' => ''
                        ]
                    ),
                ],
            ],

            "submit-button" => [
                "name"   => "submit-button",
                "fields" => [
                    self::generate_text_settings(
                        [
                            'label'     => 'Text-color',
                            'value'     => '#fff',
                        ],
                        [
                            'label'     => 'Font-size',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 13,
                            'dimension' => 'px'
                        ],
                        [
                            'label'     => 'Letter-spacing',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 0,
                            'dimension' => 'px'
                        ],
                        [
                            "blur"        => ["min" =>  0,  "max" => 20, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "opacity"     => ["min" =>  0,  "max" => 1,  "step" => 0.01, "value" => 0, "dimension" => "px"],
                            "shift_right" => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "shift_down"  => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "color"       => '#000000',
                        ],
                        [
                            "value" => '400',
                        ],
                        [
                            "value" => 'normal'
                        ],
                        [
                            'name'  => 'text-align',
                            'value' => ''
                        ]
                    ),
                    self::generate_indentations("Margin","margin", "0px", "0px", "8px", "0px"),
                    self::generate_indentations("Padding", "padding", "20px", "40px", "20px", "40px"),
                    self::generate_border(0, 100, 1, 1, 1, 1, 1, 1, '1', '#00b163', 'px'),
                    self::generate_bg_color("Background-color", "background-color", "#00b163", "#00b163", "#00b163"),

                    self::generate_effects([
                        "name"  => "submit-hover-effects",
                        "label" => "Hover-effects",
                        "data"  => [
                            [
                                "label"     => "Background-color",
                                "name"      => "background-color",
                                "type"      => "effects",
                                "default"   => "#047b47",
                                "value"     => "#047b47",

                            ],
                            [
                                "label"     => "Border-color",
                                "name"      => "border-color",
                                "type"      => "effects",
                                "default"   => "#bdc9ca",
                                "value"     => "#bdc9ca",

                            ],
                            [
                                "label"     => "Font-color",
                                "name"      => "font-color",
                                "type"      => "effects",
                                "default"   => "#fff",
                                "value"     => "#fff",
                            ],
                        ],
                        "effect_type" => "hover"
                    ]),
                ],
            ],

            "drop-down"  => [
                "name"   => "drop-down",
                "fields" => [
                    self::generate_text_settings(
                        [
                            'label'     => 'Text-color',
                            'value'     => '#000000',
                        ],
                        [
                            'label'     => 'Font-size',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 14,
                            'dimension' => 'px'
                        ],
                        [
                            'label'     => 'Letter-spacing',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 0,
                            'dimension' => 'px'
                        ],
                        [
                            "blur"        => ["min" =>  0,  "max" => 20, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "opacity"     => ["min" =>  0,  "max" => 1,  "step" => 0.01, "value" => 0, "dimension" => "px"],
                            "shift_right" => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "shift_down"  => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "color"       => '#ffffff',
                        ],
                        [
                            "value" => '400'
                        ],
                        [
                            "value" => 'normal'
                        ],
                        [
                            'name'  => 'text-align-last',
                            'value' => 'left'
                        ]
                    ),
                    self::generate_border(0, 100, 1, 0, 0, 0, 0, 0, '1', '#d0d0d0', 'px'),
                    self::generate_border_radius(0, 100, 1, 0, 0, 0, 0, 0, null, null, 'px'),
                    self::generate_bg_color("Drop-down-background",  "background-color","#fff", "#fff", "#fff"),
                    self::generate_indentations("Margin","margin", "0px", "0px", "0px", "0px"),
                    self::generate_indentations("Padding", "padding", "15px", "20px", "16px", "20px"),
                ]

            ],

            "radio-button" => [
                "name"     => "radio-button",
                "fields"   => [
                    self::generate_text_settings(
                        [
                            'label'     => 'Text-color',
                            'value'     => '#000000',
                        ],
                        [
                            'label'     => 'Font-size',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 14,
                            'dimension' => 'px'
                        ],
                        [
                            'label'     => 'Letter-spacing',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 0,
                            'dimension' => 'px'
                        ],
                        [
                            "blur"        => ["min" =>  0,  "max" => 20, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "opacity"     => ["min" =>  0,  "max" => 1,  "step" => 0.01, "value" => 0, "dimension" => "px"],
                            "shift_right" => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "shift_down"  => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "color"       => '#ffffff',
                        ],
                        [
                            "value" => "400"
                        ],
                        [
                            "value" => "normal"
                        ]
                    ),
                    self::generate_single_color("Radio-Border",  "radioBorder","#bdc9ca"),
                    self::generate_single_color("Radio-Background", "radioBackground","#fff"),
                    self::generate_single_color("Radio-Checked-Background",  "radioChecked","#00b163"),
                ],
            ],

            "checkbox"   => [
                "name"   => "checkbox",
                "fields" => [
                    self::generate_text_settings(
                        [
                            'label'     => 'Text-color',
                            'value'     => '#000000',
                        ],
                        [
                            'label'     => 'Font-size',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 14,
                            'dimension' => 'px'
                        ],
                        [
                            'label'     => 'Letter-spacing',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 0,
                            'dimension' => 'px'
                        ],
                        [
                            "blur"        => ["min" =>  0,  "max" => 20, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "opacity"     => ["min" =>  0,  "max" => 1,  "step" => 0.01, "value" => 0, "dimension" => "px"],
                            "shift_right" => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "shift_down"  => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "color"       => '#ffffff',
                        ],
                        [
                            "value" => "400"
                        ],
                        [
                            "value" => "normal"
                        ]
                    ),
                    self::generate_single_color("Border-color", "b_color","#bdc9ca"),
                    self::generate_single_color("Background-color", "bg_color","#fff"),
                    self::generate_single_color("Checkbox-Checked-color", "checkedColor","#00b163"),
                    self::generate_single_color("Checkbox-color", "checkbox_color","#fff"),
                ],
            ],

            "range-button" => [
                "name"     => "range-button",
                "fields"   => [
                    self::generate_single_color("Range-color",   "fColor","#ccc"),
                    self::generate_single_color("Ranged-color",  "lineColor","#00b163"),
                    self::generate_single_color("Circle-color", "circleColor","#00b163"),
                ],
            ],

            "toggle" => [
                "name"     => "toggle",
                "fields"   => [
                    self::generate_text_settings(
                        [
                            'label'     => 'Text-color',
                            'value'     => '#000000',
                        ],
                        [
                            'label'     => 'Font-size',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 14,
                            'dimension' => 'px'
                        ],
                        [
                            'label'     => 'Letter-spacing',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 0,
                            'dimension' => 'px'
                        ],
                        [
                            "blur"        => ["min" =>  0,  "max" => 20, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "opacity"     => ["min" =>  0,  "max" => 1,  "step" => 0.01, "value" => 0, "dimension" => "px"],
                            "shift_right" => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "shift_down"  => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "color"       => '#ffffff',
                        ],
                        [
                            "value" => "400"
                        ],
                        [
                            "value" => "normal"
                        ]
                    ),
                    self::generate_single_color("Circle-color",   "circleColor","#ffff"),
                    self::generate_single_color("Background-color",  "bg_color","#ccc"),
                    self::generate_single_color("Background-checked-color", "checkedColor","#00b163"),
                ],
            ],

            "quantity"   => [
                "name"   => "quantity",
                "fields" => [
                    self::generate_text_settings(
                        [
                            'label'     => 'Text-color',
                            'value'     => '#000000',
                        ],
                        [
                            'label'     => 'Font-size',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 14,
                            'dimension' => 'px'
                        ],
                        [
                            'label'     => 'Letter-spacing',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 0,
                            'dimension' => 'px'
                        ],
                        [
                            "blur"        => ["min" =>  0,  "max" => 20, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "opacity"     => ["min" =>  0,  "max" => 1,  "step" => 0.01, "value" => 0, "dimension" => "px"],
                            "shift_right" => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "shift_down"  => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "color"       => '#ffffff',
                        ],
                        [
                            "value" => "400"
                        ],
                        [
                            "value" => "normal"
                        ]
                    ),
                    self::generate_width("Width","width", 0, 100, 1, 100, '%'),
                    self::generate_bg_color("Input-color", "background-color","#fff", "#fff", "#fff"),
                    self::generate_border(0, 100, 1, 1, 1, 1, 1, 1, '1', '#d0d0d0', 'px'),
                    self::generate_border_radius(0, 100, 1, 0, 0, 0, 0, 0, null, null, 'px'),
                    self::generate_indentations("Margin","margin", "0px", "0px", "0px", "0px"),
                    self::generate_indentations("Padding","padding", "17px", "15px", "17px", "15px"),
                    self::generate_effects([
                        "name"  => "quantity-active-effects",
                        "label" => "Active-effects",
                        "data"  => [
                            [
                                "label"     => "Background-color",
                                "name"      => "background-color",
                                "type"      => "effects",
                                "default"   => "#fff",
                                "value"     => "#fff",

                            ],
                            [
                                "label"     => "Border-color",
                                "name"      => "border-color",
                                "type"      => "effects",
                                "default"   => "#00b163",
                                "value"     => "#00b163",

                            ],
                            [
                                "label"     => "Font-color",
                                "name"      => "font-color",
                                "type"      => "effects",
                                "default"   => "#000",
                                "value"     => "#000",
                            ],
                        ],
                        "effect_type" => "active"
                    ]),
                ],
            ],

            "text-area"  => [
                "name"   => "text-area",
                "fields" => [
                    self::generate_text_settings(
                        [
                            'label'     => 'Text-color',
                            'value'     => '#000000',
                        ],
                        [
                            'label'     => 'Font-size',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 16,
                            'dimension' => 'px'
                        ],
                        [
                            'label'     => 'Letter-spacing',
                            'min'       => 0,
                            'max'       => 100,
                            'step'      => 1,
                            'value'     => 0,
                            'dimension' => 'px'
                        ],
                        [
                            "blur"        => ["min" =>  0,  "max" => 20, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "opacity"     => ["min" =>  0,  "max" => 1,  "step" => 0.01, "value" => 0, "dimension" => "px"],
                            "shift_right" => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "shift_down"  => ["min" => -40, "max" => 40, "step" => 1,    "value" => 0, "dimension" => "px"],
                            "color"       => '#ffffff',
                        ],
                        [
                            "value" => '400',
                        ],
                        [
                            "value" => 'normal'
                        ]
                    ),
                    self::generate_width("Width","width", 0, 100, 1, 100, '%'),
                    self::generate_bg_color("Background-color", "background-color","#fff", "#fff", "#fff"),
                    self::generate_border( 0, 100, 1, 1, 1, 1, 1, 1, 1, '#d0d0d0', 'px'),
                    self::generate_border_radius(0, 100, 1, 0, 0, 0, 0, 0, null, null, 'px'),
                    self::generate_indentations("Margin","margin", "0px", "0px", "0px", "0px"),
                    self::generate_indentations("Padding", "padding", "10px", "10px", "10px", "10px"),
                    self::generate_effects([
                        "name"  => "text-area-active-effects",
                        "label" => "Active-effects",
                        "data"  => [
                            [
                                "label"     => "Background-color",
                                "name"      => "background-color",
                                "type"      => "effects",
                                "default"   => "#fff",
                                "value"     => "#fff",

                            ],
                            [
                                "label"     => "Border-color",
                                "name"      => "border-color",
                                "type"      => "effects",
                                "default"   => "#00b163",
                                "value"     => "#00b163",

                            ],
                            [
                                "label"     => "Font-color",
                                "name"      => "font-color",
                                "type"      => "effects",
                                "default"   => "#000",
                                "value"     => "#000",
                            ],
                        ],
                        "effect_type" => "active"
                    ]),
                ],
            ],

            "hr-line"    => [
                "name"   => "hr-line",
                "fields" => [
                    self::generate_single_color("Color",   "border-bottom-color","#bdc9ca"),
                    self::generate_indentations("Margin","margin",  "0px", "0px", "0px", "0px"),
                    self::generate_indentations("Padding", "padding", "0px", "0px", "0px", "0px"),
                ],
            ],

        ];

        return $data;
    }

    public static function generate_single_color($label, $name, $color)
    {
        return [
            "label"   => $label,
            "name"    => $name,
            "type"    => "single-color",
            "value"   => $color,
            "default" => $color,
        ];
    }

    public static function generate_bg_color($label, $name,  $default_1, $default_2, $default_3, $alias = '')
    {
        if(empty($_name)) $alias = $name;

        return [
            "label"   => $label,
            "name"    => $name,
            "alias"   => $alias,
            "default" => "solid",
            "type"    => "background-color",
            "solid" => [
                "label"   => $label,
                "value"   => $default_1,
                "default" => $default_1,
                "alias"   => "backgroundColor",
            ],
            "gradient" => [
                [
                    "label"   => "Gradient From",
                    "value"   => $default_2,
                    "default" => $default_2,
                    "alias"   => "backgroundImage",
                ],
                [
                    "label"   => "Gradient To",
                    "value"   => $default_3,
                    "default" => $default_3,
                    "alias"   => "backgroundImage",
                ]
            ],
        ];
    }

    public static function generate_border($min, $max, $step, $g_v, $t_l, $t_r, $b_l, $b_r, $width, $color,  $dimension)
    {
        return [
            "label"    => "Border",
            "type"     => 'border',
            "name"     => "border",
            "default"  => [
                "label"     => "All Corners Width",
                "value"     => $g_v,
                "min"       => $min,
                "max"       => $max,
                "step"      => $step,
                "width"     => [
                    "value" => $width,
                    "label" => "Border Width",
                    "name"  => "border-width",
                    "options"     => [
                        "top_left"   => [
                            "value" => $t_l,
                            "label" => "Top",
                        ],
                        "top_right"  => [
                            "value" => $t_r,
                            "label" => "Right",
                        ],
                        "bottom_left"    => [
                            "value" => $b_l,
                            "label" => "Bottom",
                        ],
                        "bottom_right" => [
                            "value" => $b_r,
                            "label" => "Left",
                        ],
                    ],
                ],
                "style"     => [
                    "value"   => "solid",
                    "name"    => "border-style",
                    "label"   => "Border Style",
                    "options" => ["solid", "dotted", "dashed", "double", "groove", "ridge", "inset", "outset", "inherit", "hidden", "none"],
                ],
                "color"     => [
                    "label"   => "Border Color",
                    "name"  => "border-color",
                    "value"   => $color,
                    "default" => $color,
                ],
            ],
            "dimension" => $dimension
        ];
    }

    public static function generate_border_radius($min, $max, $step, $g_v, $t_l, $t_r, $b_l, $b_r, $width, $color,  $dimension)
    {
        return [
            "label"    => "Border-radius",
            "type"     => 'border-radius',
            "name"     => 'border-radius',
            "default"  => [
                "label"     => "All Corners Radius",
                "value"     => $g_v,
                "min"       => $min,
                "max"       => $max,
                "step"      => $step,
                "radius"     => [
                    "value" => $width,
                    "label" => "Border Radius",
                    "name"  => "border-radius",
                    "options"     => [
                        "top_left"   => [
                            "value" => $t_l,
                            "label" => "Top Left",
                        ],
                        "top_right"  => [
                            "value" => $t_r,
                            "label" => "Top Right",
                        ],
                        "bottom_left"    => [
                            "value" => $b_l,
                            "label" => "Bottom Left",
                        ],
                        "bottom_right" => [
                            "value" => $b_r,
                            "label" => "Bottom Right",
                        ],
                    ],
                ],
            ],
            "dimension" => $dimension
        ];
    }

    public static function generate_width($label, $name, $min, $max, $step, $value, $dimension)
    {
        return [
            "name"      => $name,
            "label"     => $label,
            "type"      => "width",
            "dimension" => $dimension,
            "default"   => [
                "min"       => $min,
                "max"       => $max,
                "step"      => $step,
                "value"     => $value,
            ],
        ];
    }

    public static function generate_text_color($label, $default)
    {
        return [
            "label"   => $label,
            "name"    => "color",
            "type"    => "text-color",
            "default" => $default,
            "value"   => '',
        ];
    }

    public static function generate_text_settings($text_color, $font_size, $letter_spacing, $text_shadow, $font_weight, $font_style, $position = ['name' => 'text-align', 'value' => 'left'])
    {
        return [
            "label"       => "Font settings",
            "name"        => "text-settings",
            "type"        => "text-settings",
            "color"       => self::generate_text_color($text_color['label'], $text_color['value']),
            "drop_down"   => [
                "font_wight" => [
                    "name"    => "font-weight",
                    "value"   => $font_weight['value'],
                    "label"  => "Font-Weight",
                    "options" => ['inherit', '100', '200', '300', '400', '500', '600', '700', '800', '900', 'bold', 'bolder'],
                ],
                "font_style" => [
                    "name"    => "font-style",
                    "value"   => $font_style['value'],
                    "label"  => "Font-Style",
                    "options" => ['inherit', 'normal', 'italic', 'oblique', 'inherit'],
                ],
                "position"     => [
                    "name"    => $position['name'],
                    "label"   => "Text position",
                    "value"   => $position['value'],
                    'options' => ['left', 'center', 'right'],
                ],
            ],

            "range"       => [
                "font_size"      => self::generate_width($font_size['label'], "font-size", $font_size['min'], $font_size['max'], $font_size['step'], $font_size['value'], $font_size['dimension']),
                "letter_spacing" => self::generate_width($letter_spacing['label'], "letter-spacing", $letter_spacing['min'], $letter_spacing['max'], $letter_spacing['step'], $letter_spacing['value'], $letter_spacing['dimension']),
            ],

            "text_shadow" => [
                "label"   => "Text Shadow",
                "name"    => "text-shadow",
                "options" => [
                    "shift_right" => [
                        "label"     => "Shift Right",
                        "min"       => $text_shadow['shift_right']['min'],
                        "max"       => $text_shadow['shift_right']['max'],
                        "step"      => $text_shadow['shift_right']['step'],
                        "value"     => $text_shadow['shift_right']['value'],
                        "dimension" => $text_shadow['shift_right']['dimension'],
                    ],
                    "shift_down"  => [
                        "label"     => "Shift Down",
                        "min"       => $text_shadow['shift_down']['min'],
                        "max"       => $text_shadow['shift_down']['max'],
                        "step"      => $text_shadow['shift_down']['step'],
                        "value"     => $text_shadow['shift_down']['value'],
                        "dimension" => $text_shadow['shift_down']['dimension'],
                    ],
                    "blur"        => [
                        "label"     => "Blur",
                        "min"       => $text_shadow['blur']['min'],
                        "max"       => $text_shadow['blur']['max'],
                        "step"      => $text_shadow['blur']['step'],
                        "value"     => $text_shadow['blur']['value'],
                        "dimension" => $text_shadow['blur']['dimension'],
                    ],
                ],

                "opacity"     => [
                    "label"     => "Opacity",
                    "min"       => $text_shadow['opacity']['min'],
                    "max"       => $text_shadow['opacity']['max'],
                    "step"      => $text_shadow['opacity']['step'],
                    "value"     => $text_shadow['opacity']['value'],
                ],

                "color" => [
                    "name"    => "color",
                    "label"   => "Color",
                    "value"   => $text_shadow['color'],
                    "default" => $text_shadow['color'],
                ]
            ],
        ];
    }

    /**
     * @param $args array
     * @return array
     */
    public static function generate_effects($args) {
        return [
            "label"   => $args["label"],
            "name"    => $args["name"],
            "default" => "solid",
            "effect"  => $args['effect_type'],
            "type"    => "effects",
            "data"    => $args['data']
        ];
    }

    public static function generate_focus_effects() {

    }

    public static function generate_indentations($label, $name, $t_l, $t_r, $b_l, $b_r){
        return [
            "label" => $label,
            "name"  => $name,
            "type"  => "indentation",
            "default"  => [
                "label"       => "All Corners",
                "options"     => [
                    "top_left"   => [
                        "label" => "Top",
                        "value" => $t_l,
                    ],
                    "top_right"  => [
                        "label" => "Right",
                        "value" => $t_r,
                    ],
                    "bottom_left"    => [
                        "label" => "Bottom",
                        "value" => $b_l,
                    ],
                    "bottom_right" => [
                        "label" => "Left",
                        "value" => $b_r,
                    ],
                ],
            ]
        ];
    }

    public static function generate_box_shadow($args)
    {
        return [
            "label"   => "box-shadow",
            "type"    => "box-shadow",
            "name"    => "box-shadow",
            "range"   => [
                "vertical_length" => [
                    "label"     => "Vertical Length",
                    "min"       => $args['vertical_length']['min'],
                    "max"       => $args['vertical_length']['max'],
                    "step"      => $args['vertical_length']['step'],
                    "value"     => $args['vertical_length']['value'],
                    "dimension" => $args['vertical_length']['dimension']
                ],
                "horizontal_length" => [
                    "label"     => "Horizontal Length",
                    "min"       => $args['horizontal_length']['min'],
                    "max"       => $args['horizontal_length']['max'],
                    "step"      => $args['horizontal_length']['step'],
                    "value"     => $args['horizontal_length']['value'],
                    "dimension" => $args['horizontal_length']['dimension']
                ],
                "blur_radius"   => [
                    "label"     => "Blur Radius",
                    "min"       => $args['blur_radius']['min'],
                    "max"       => $args['blur_radius']['max'],
                    "step"      => $args['blur_radius']['step'],
                    "value"     => $args['blur_radius']['value'],
                    "dimension" => $args['blur_radius']['dimension']
                ],
                "spread_radius" => [
                    "label"     => "Spread Radius",
                    "min"       => $args['spread_radius']['min'],
                    "max"       => $args['spread_radius']['max'],
                    "step"      => $args['spread_radius']['step'],
                    "value"     => $args['spread_radius']['value'],
                    "dimension" => $args['spread_radius']['dimension']
                ],
            ],

            "opacity"       => [
                "label"     => "Opacity",
                "min"       => $args['opacity']['min'],
                "max"       => $args['opacity']['max'],
                "step"      => $args['opacity']['step'],
                "value"     => $args['opacity']['value'],
                "dimension" => $args['opacity']['dimension']
            ],

            "color"        => [
                "shadow_color"  => [
                    "label"   => "Shadow Color",
                    "value"   => "#542554",
                    "default" => $args['shadow_color']['color'],
                ],
            ],

            "line"          => [
                'value'   => '',
                "options" => [
                    "outline" => [
                        "label" => "Outline",
                        "value" => 'outline'
                    ],
                    "inset"   => [
                        "label" => "Inset",
                        "value" => 'inset'
                    ],
                ]
            ],
        ];
    }

    public static function container_box_shadow()
    {
        return [
            "vertical_length" => [
                "min"       => -200,
                "max"       => 200,
                "step"      => 1,
                "value"     => 0,
                "dimension" => "px"
            ],

            "horizontal_length" => [
                "min"       => -200,
                "max"       => 200,
                "step"      => 1,
                "value"     => 0,
                "dimension" => "px"
            ],

            "blur_radius" => [
                "min"       => 0,
                "max"       => 300,
                "step"      => 1,
                "value"     => 0,
                "dimension" => "px"
            ],

            "spread_radius" => [
                "min"       => -200,
                "max"       => 200,
                "step"      => 1,
                "value"     => 0,
                "dimension" => "px"
            ],

            "opacity" => [
                "min"       => 0,
                "max"       => 1,
                "step"      => 0.01,
                "value"     => 0,
                "dimension" => ""
            ],

            "shadow_color" => [
                "color" => "#ffffff"
            ],
        ];
    }

    public static function custom_default_styles()
    {
        $data = [
            "v-container" => [
                "width"           => "47.5%",
                "margin"          => "0px 0px 0px 0px",
                "padding"         => "50px 50px 50px 50px",
                "box-shadow"       => "0px 0px 0px 0px rgba(255,255,255,0)",
                "border-width"     => "0px 0px 0px 0px",
                "border-style"     => "solid",
                "border-color"     => "#ffffff",
                "border-radius"    => "10px 10px 10px 10px",
                "background-color" => "#eff4f4",
                "background-image" => '',
            ],

            "h-container" => [
                "width"           => "100%",
                "margin"          => "0px 0px 0px 0px",
                "padding"         => "50px 50px 50px 50px",
                "box-shadow"       => "0px 0px 0px 0px rgba(255,255,255,0)",
                "border-width"     => "0px 0px 0px 0px",
                "border-style"     => "solid",
                "border-color"     => "#ffffff",
                "border-radius"    => "10px 10px 10px 10px",
                "background-color" => "#eff4f4",
                "background-image" => '',
            ],

            "headers" => [
                "color"            => "#000000",
                "font-size"        => "22px",
                "font-style"       => "normal",
                "text-align"       => " ",
                "font-weight"      => "700",
                "text-shadow"      => "0px 0px 0px rgba(255,255,255,0)",
                "letter-spacing"   => "0px",
            ],

            "labels"    => [
                "color"           => "#000000",
                "margin"          => "0px 0px 8px 0px",
                "padding"         => "0px 0px 0px 0px",
                "font-size"        => "14px",
                "text-align"       => "left",
                "font-style"       => "normal",
                "font-weight"      => "400",
                "text-shadow"      => "0px 0px 0px rgba(255,255,255,0)",
                "letter-spacing"   => "0px",
            ],

            "descriptions" => [
                "color"           => "#a29f9f",
                "margin"          => "3px 0px 3px 0px",
                "padding"         => "0px 0px 0px 0px",
                "font-size"        => "12px",
                "text-align"       => "left",
                "font-style"       => "normal",
                "font-weight"      => "400",
                "text-shadow"      => "0px 0px 0px rgba(255,255,255,0)",
                "letter-spacing"   => "0px",
            ],

            "total-summary" =>  [
                "color"          => "#000000",
                "font-style"     => "normal",
                "font-size"      => "16px",
                "text-align"     => "left",
                "font-weight"    => "400",
                "text-shadow"    => "0px 0px 0px rgba(255,255,255,0)",
                "letter-spacing" => "0px",
            ],

            "total" => [
                "color"            => "#000000",
                "font-size"        => "16px",
                "font-style"       => "normal",
                "text-align"       => "",
                "font-weight"      => "700",
                "text-shadow"      => "0px 0px 0px rgba(255,255,255,0)",
                "letter-spacing"   => "0px",
            ],

            "drop-down" => [
                "color"           => "#000000",
                "margin"          => "0px 0px 0px 0px",
                "padding"         => "15px 20px 15px 20px",
                "font-size"        => "14px",
                "text-align-last"  => "left",
                "box-shadow"       => "0px 0px 0px 0px rgba(255,255,255,0)",
                "font-style"       => "normal",
                "font-weight"      => "400 ",
                "text-shadow"      => "0px 0px 0px rgba(255,255,255,0) ",
                "border-width"     => "1px 1px 1px 1px",
                "border-style"     => "solid",
                "letter-spacing"   => "0px",
                "background-color" => "#fff",
            ],

            "radio-button" => [
                "color"           => "#000000",
                "font-size"        => "14px",
                "text-align"       => "left",
                "font-style"       => "normal",
                "font-weight"      => "400 ",
                "text-shadow"      => "0px 0px 0px rgba(255,255,255,0)",
                "letter-spacing"   => "0px",
                "radioColor"       => "#fff",
                "radioBorder"      => "#bdc9ca",
                "radioChecked"     => "#00b163",
            ],

            "checkbox"     => [
                "color"           => "#000000",
                "font-size"        => "14px",
                "text-align"       => "left",
                "font-style"       => "normal",
                "font-weight"      => "400 ",
                "text-shadow"      => "0px 0px 0px rgba(255,255,255,0)",
                "letter-spacing"   => "0px",
                "b_color"          => "#bdc9ca",
                "bg_color"         => "#fff",
                "checkedColor"     => "#00b163",
                "checkbox_color"   => "#fff"
            ],

            "range-button" => [
                "fColor"           => "#ccc",
                "circleColor"      => "#00b163",
                "lineColor"        => "#00b163",
            ],

            "toggle" => [
                "color"            => "#000000",
                "font-size"        => "14px",
                "text-align"       => "left",
                "font-style"       => "normal",
                "font-weight"      => "400",
                "text-shadow"      => "0px 0px 0px rgba(255,255,255,0)",
                "letter-spacing"   => "0px",
                "circleColor"      => "#ffff",
                "bg_color"         => "#ccc",
                "checkedColor"     => "#00b163",
            ],

            "quantity"     => [
                "color"            => "#000000",
                "width"            => "100%",
                "margin"           => "0px 0px 0px 0px",
                "padding"          => "17px 15px 17px 15px",
                "font-size"        => "14px",
                "text-align"       => "left",
                "font-style"       => "normal",
                "font-weight"      => "400",
                "text-shadow"      => "0px 0px 0px rgba(255,255,255,0)",
                "border-width"     => "1px 1px 1px 1px",
                "border-style"     => "solid",
                "border-color"     => "#d0d0d0",
                "border-radius"    => "0px 0px 0px 0px",
                "letter-spacing"   => "0px",
                "background-color" => "#fff",
            ],

            "text-area"    => [
                "color"            => "#000000",
                "width"            => "100%",
                "margin"           => "0px 0px 0px 0px",
                "padding"          => "10px 10px 10px 10px",
                "font-size"        => "14px",
                "text-align"       => "left",
                "font-style"       => "normal",
                "font-weight"      => "400 ",
                "text-shadow"      => "0px 0px 0px rgba(255,255,255,0)",
                "border-style"     => "solid",
                "border-width"     => "1px 1px 1px 1px",
                "border-color"     => "#d0d0d0",
                "border-radius"    => "0px 0px 0px 0px",
                "letter-spacing"   => "0px",
                "background-color" => "#fff",
            ],

            "hr-line"      => [
                "margin"              => "0px 0px 0px 0px",
                "padding"             => "0px 0px 0px 0px",
                "border-bottom-color" => "#bdc9ca",
            ],

            "submit-button" => [
                "color"            => "#fff",
                "margin"           => "0px 0px 0px 0px",
                "padding"          => "20px 40px 20px 40px",
                "font-size"        => "13px",
                "font-style"       => "normal ",
                "text-align"       => "",
                "font-weight"      => "400",
                "text-shadow"      => "0px 0px 0px rgba(255,255,255,0) ",
                "letter-spacing"   => "0px",
                "border-width"     => "1px 1px 1px 1px",
                "border-style"     => "solid",
                "border-color"     => "#00b163",
                "background-color" => "#00b163"
            ],
        ];

        return $data;
    }
}