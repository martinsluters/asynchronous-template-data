<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Unit\AjaxHandling;

use \Mockery;
use \WP_Mock;
use martinsluters\AsynchronousTemplateData\EventManagement\EventSubscriberInterface;
use martinsluters\AsynchronousTemplateData\AjaxHandling\AjaxHandlerInterface;
use martinsluters\AsynchronousTemplateData\AjaxHandling\AjaxHandler;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;
use martinsluters\AsynchronousTemplateData\ContentController;
use martinsluters\AsynchronousTemplateData\PluginData;

/**
 * Unit Tests Related to AjaxHandler class
 */
class AjaxHandlerTest extends TestCase {

	/**
	 * Instance of AjaxHandler - sut
	 *
	 * @var \martinsluters\AsynchronousTemplateData\AjaxHandling\AjaxHandler
	 */
	private AjaxHandler $ajax_handler_sut;

	/**
	 * Test double of PluginData.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\AjaxHandling\PluginData
	 */
	private PluginData $plugin_data_test_double;

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void { // phpcs:ignore
		$this->content_controller_test_double = Mockery::mock( ContentController::class );
		$this->plugin_data_test_double = Mockery::mock( PluginData::class );
		$this->ajax_handler_sut = new AjaxHandler( $this->plugin_data_test_double, $this->content_controller_test_double );
	}

	/**
	 * Make sure that SUT implements EventSubscriberInterface.
	 *
	 * @return void
	 */
	public function testMustImplementEventSubscriberInterface(): void {
		$this->assertInstanceOf( EventSubscriberInterface::class, $this->ajax_handler_sut );
	}

	/**
	 * Make sure that SUT implements AjaxHandlerInterface.
	 *
	 * @return void
	 */
	public function testMustImplementAjaxHandlerInterface(): void {
		$this->assertInstanceOf( AjaxHandlerInterface::class, $this->ajax_handler_sut );
	}

	/**
	 * Make sure that handleRequest outputs value.
	 *
	 * @return void
	 */
	public function testMustOutputValue(): void {
		WP_Mock::passthruFunction( 'wp_kses_data' );
		WP_Mock::userFunction( 'wp_die' );

		$this->content_controller_test_double
			->shouldReceive( 'getAsynchronousTemplateData' )
			->times( 1 )
			->andReturn( 'Fast Delivery' );

		// e.g. echo, print etc.
		$this->expectOutputString( 'Fast Delivery' );

		$this->ajax_handler_sut->handleAction();
	}

	/**
	 * Make sure that handleRequest dies in WP manner as WP Ajax action must do so.
	 *
	 * @return void
	 */
	public function testMustWPDie(): void {
		WP_Mock::passthruFunction( 'wp_kses_data' );

		$this->content_controller_test_double
			->shouldReceive( 'getAsynchronousTemplateData' );

		WP_Mock::userFunction( 'wp_die' )->once();

		$this->ajax_handler_sut->handleAction();
	}
}
