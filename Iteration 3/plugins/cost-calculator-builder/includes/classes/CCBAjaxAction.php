<?php

namespace cBuilder\Classes;

class CCBAjaxAction {

    /**
     * @param string   $tag             The name of the action to which the $function_to_add is hooked.
     * @param callable $function_to_add The name of the function you wish to be called.
     * @param boolean  $nonpriv         Optional. Boolean argument for adding wp_ajax_nopriv_action. Default false.
     * @param int      $priority        Optional. Used to specify the order in which the functions
     *                                  associated with a particular action are executed. Default 10.
     *                                  Lower numbers correspond with earlier execution,
     *                                  and functions with the same priority are executed
     *                                  in the order in which they were added to the action.
     * @param int      $accepted_args   Optional. The number of arguments the function accepts. Default 1.
     * @return true Will always return true.
     */

    public static function addAction($tag, $function_to_add, $nonpriv = false, $priority = 10, $accepted_args = 1) {
        add_action('wp_ajax_'.$tag, $function_to_add, $priority = 10, $accepted_args = 1);
        if ( $nonpriv ) add_action('wp_ajax_nopriv_'.$tag, $function_to_add);
        return true;
    }

    public static function init() {
        CCBAjaxAction::addAction('calc_create_id', [CCBCalculators::class , 'create_calc_id']);
        CCBAjaxAction::addAction('calc_edit_calc', [CCBCalculators::class , 'edit_calc']);
        CCBAjaxAction::addAction('calc_delete_calc', [CCBCalculators::class , 'delete_calc']);
        CCBAjaxAction::addAction('calc_save_custom', [CCBCalculators::class , 'save_custom']);
        CCBAjaxAction::addAction('calc_get_existing', [CCBCalculators::class , 'get_existing']);
        CCBAjaxAction::addAction('calc_save_settings', [CCBCalculators::class , 'save_settings']);
        CCBAjaxAction::addAction('calc_duplicate_calc', [CCBCalculators::class , 'duplicate_calc']);
        CCBAjaxAction::addAction('calc-run-calc-updates', [CCBUpdates::class , 'run_calc_updates']);

        /** import/export  */
        CCBAjaxAction::addAction('cost-calculator-custom-import-total', [CCBExportImport::class , 'custom_import_calculators_total']);
        CCBAjaxAction::addAction('cost-calculator-demo-calculators-total', [CCBExportImport::class , 'demo_import_calculators_total']);
        CCBAjaxAction::addAction('cost-calculator-import-run', [CCBExportImport::class , 'import_run']);
        CCBAjaxAction::addAction('cost-calculator-custom_export_run', [CCBExportImport::class , 'export_calculators']);

    }
}