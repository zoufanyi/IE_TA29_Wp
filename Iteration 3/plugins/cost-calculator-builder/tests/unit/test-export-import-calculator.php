<?php
/**
 * Class ExportCalculatorTest
 *
 * @package Cost_Calculator_Builder
 */
use cBuilder\Classes\CCBCalculators;
use cBuilder\Classes\CCBExportImport;

/**
 * Export/Import Calculator test case.
 */
class ExportImportCalculatorTest extends WP_UnitTestCase {

	static $testCalculatorFilePath  = CALC_PATH . '/tests/files/import-calculators-data.txt';
	static $demoCalculatorsFilePath = CALC_PATH . '/demo-sample/cost_calculator_data.txt';

	private function getFileContentForTest( $filePath ){
		if ( !file_exists( $filePath) ) {
			return false;
		}
		return file_get_contents( $filePath );
	}

	/**
	 * Imitate import_run
	 * CCBExportImport : 55
	 */
	public function test_demo_import_run() {
		$contents = $this->getFileContentForTest( self::$demoCalculatorsFilePath );
		if ( !$contents ) {
			return;
		}

		$contents = json_decode($contents, true);
		$existing = [];

		foreach ( $contents as $itemKey => $item ) {
			$result    = ['step' =>  1, 'key' => 0 , 'success' => false];
			$oldResult = $result;
			$title     = !empty( $item['stm-name'] ) ? sanitize_text_field( $item['stm-name'] ) : 'empty';

			CCBExportImport::addCalculatorData( $item, $result );

			$this->assertEquals( $result['data'], 'Create Calculator: ' . $title );
			$this->assertNotEquals($oldResult['key'], $result['key']);
			$this->assertTrue($result['success']);

			$existing = $result['existing'];
		}

		$this->assertCount(5, $existing);
	}

	/**
	 * Imitate import_run
	 * CCBExportImport : 55
	 */
	public function test_custom_import_run() {
		$contents = $this->getFileContentForTest( self::$testCalculatorFilePath );
		if ( !$contents ) {
			return;
		}

		$contents = json_decode($contents, true);
		$existing = [];

		foreach ( $contents as $itemKey => $item ) {
			$result    = ['step' =>  1, 'key' => 0 , 'success' => false];
			$oldResult = $result;
			$title     = !empty( $item['stm-name'] ) ? sanitize_text_field( $item['stm-name'] ) : 'empty';

			CCBExportImport::addCalculatorData( $item, $result );

			$this->assertEquals( $result['data'], 'Create Calculator: ' . $title );
			$this->assertNotEquals($oldResult['key'], $result['key']);
			$this->assertTrue($result['success']);

			$existing = $result['existing'];
		}

		$this->assertCount(8, $existing);
	}

	/**
	 * Check addCalculatorData function
	 * CCBExportImport : 101
	 */
	public function test_import_one_calculator() {
		$result    = ['step' =>  1, 'key' => 0 , 'success' => false];
		$oldResult = $result;

		$contents = $this->getFileContentForTest( self::$testCalculatorFilePath);
		if ( !$contents ) {
			return;
		}

		$contents = json_decode($contents, true);
		$item     = $contents[$result['key']];

		CCBExportImport::addCalculatorData( $item, $result );

		$this->assertEquals( $result['data'], 'Create Calculator: LoL Rank Boost (TEST FOR STYLEMIX SUPPORT)' );
		$this->assertNotEquals($oldResult['key'], $result['key']);
		$this->assertTrue($result['success']);
		$this->assertCount(1, $result['existing']);
	}

	/**
	 * Import file data
	 * than export
	 */
	public function test_import_export_run() {
		$contents = $this->getFileContentForTest( self::$demoCalculatorsFilePath );
		if ( !$contents ) {
			return;
		}

		$beforeImportCalculators = CCBCalculators::getWPCalculatorsData();
		$this->assertEmpty( $beforeImportCalculators );

		$contents = json_decode($contents, true);

		foreach ( $contents as $itemKey => $item ) {
			$result    = ['step' =>  1, 'key' => 0 , 'success' => false];
			$oldResult = $result;
			$title     = !empty( $item['stm-name'] ) ? sanitize_text_field( $item['stm-name'] ) : 'empty';

			CCBExportImport::addCalculatorData( $item, $result );

			$this->assertEquals( $result['data'], 'Create Calculator: ' . $title );
			$this->assertNotEquals($oldResult['key'], $result['key']);
			$this->assertTrue($result['success']);
		}

		$afterImportCalculators = CCBCalculators::getWPCalculatorsData();
		$afterImportCalculators = CCBExportImport::parse_export_data( $afterImportCalculators );

		$this->assertCount(5, $afterImportCalculators);

		foreach ( $afterImportCalculators as $calculator ) {
			$this->assertArrayHasKey('stm-name', $calculator);
			$this->assertArrayHasKey('stm-fields', $calculator);
			$this->assertArrayHasKey('stm-formula', $calculator);
			$this->assertArrayHasKey('stm-conditions', $calculator);
			$this->assertArrayHasKey('ccb-custom-styles', $calculator);
			$this->assertArrayHasKey('ccb-custom-fields', $calculator);
			$this->assertArrayHasKey('stm_ccb_form_settings', $calculator);
		}
	}

	/** is correctly work on empty data */
	public function test_calculator_parse_export_data_on_empty() {
		$this->assertCount(0, CCBExportImport::parse_export_data( [] ));
	}

	/** is correctly work if not array data */
	public function test_calculator_parse_export_data_on_wrong_type() {
		$this->assertCount(0, CCBExportImport::parse_export_data( "test" ));
		$this->assertCount(0, CCBExportImport::parse_export_data( 1999 ));
		$this->assertCount(0, CCBExportImport::parse_export_data( ["test" => 1, "test2" => 4] ));
		$this->assertCount(0, CCBExportImport::parse_export_data( [["test" => 1], ["test2" => 4]] ));

		/** @var  $testObj simulate wp post object*/
		$testObj = new stdClass;
		$testObj->ID = 1;
		$testObj->post_title  = 'post_title';
		$testObj->post_status = "publish";
		$testObj->post_name   = "post_name";
		$testObj->post_type   = "cost-calc";

		$this->assertCount(0, CCBExportImport::parse_export_data( [$testObj] ));
	}

}
