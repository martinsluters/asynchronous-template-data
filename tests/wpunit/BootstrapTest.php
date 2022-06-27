<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Wpunit;

use martinsluters\AsynchronousTemplateData\Bootstrap;
use martinsluters\AsynchronousTemplateData\ContentController;
use martinsluters\AsynchronousTemplateData\ProviderManagement\ProviderManager;

/**
 * Tests Related to Bootstrap class
 */
class BootstrapTest extends \Codeception\TestCase\WPTestCase {

	use \FalseyAssertEqualsDetector\Test;

	/**
	 * Bootstrap - sut.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Bootstrap
	 */
	private Bootstrap $bootstrap_sut;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();
		$this->bootstrap_sut = Bootstrap::getInstance();
	}

	/**
	 * Tear down singleton between tests.
	 *
	 * @return void
	 */
	protected function tearDown(): void {
		parent::tearDown();
		$this->bootstrap_sut->tearDown();
	}

	/**
	 * Make sure that getter of content_controller returns instance of content controller.
	 *
	 * @return void
	 */
	public function testGetContentControllerMustReturnContentControllerInstance(): void {
		$this->bootstrap_sut->init();
		$this->assertInstanceOf( ContentController::class, $this->bootstrap_sut->getContentController() );
	}

	/**
	 * Make sure that getter of content_controller throws exception if
	 * content controller property is not initialized yet.
	 *
	 * @return void
	 */
	public function testGetContentControllerMustThrowException(): void {
		$this->expectException( \Exception::class );
		$this->bootstrap_sut->getContentController();
	}

	/**
	 * Make sure that getter of provider_manager returns instance of Provider Manager.
	 *
	 * @return void
	 */
	public function testGetProviderManagerShouldReturnProviderManagerInstance() {
		$this->bootstrap_sut->init();
		$this->assertInstanceOf( ProviderManager::class, $this->bootstrap_sut->getProviderManager() );
	}

	/**
	 * Make sure that getter of provider_manager throws exception if
	 * Provider Manager property is not initialized yet.
	 *
	 * @return void
	 */
	public function testGetProviderManagerMustThrowException(): void {
		$this->expectException( \Exception::class );
		$this->bootstrap_sut->getProviderManager();
	}
}
