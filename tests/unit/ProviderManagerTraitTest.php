<?php
declare( strict_types=1 );
namespace martinsluters\AsynchronousTemplateData\Tests\Unit;

use Mockery;
use martinsluters\AsynchronousTemplateData\ProviderManagement\ProviderManager;
use martinsluters\AsynchronousTemplateData\ProviderManagerTrait;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;

/**
 * Class of testing provider manager trait.
 */
class ProviderManagerTraitTest extends TestCase {

	use ProviderManagerTrait;

	/**
	 * Make sure plugin data instance is set.
	 *
	 * @return void
	 */
	public function testProviderManagerCanBeInjected(): void {

		$this->setProviderManager( Mockery::mock( ProviderManager::class ) );
		$this->assertInstanceOf( ProviderManager::class, $this->getProviderManager() );
	}

	/**
	 * Make sure that getter of provider_manager throws exception if
	 * Provider Manager property is not initialized yet.
	 *
	 * @return void
	 */
	public function testGetProviderManagerMustThrowException(): void {
		$this->expectException( \Exception::class );
		$this->getProviderManager();
	}
}
