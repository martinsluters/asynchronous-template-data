<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Unit\ProviderManager;

use Mockery;
use martinsluters\AsynchronousTemplateData\Providers\DataProviderInterface;
use martinsluters\AsynchronousTemplateData\ProviderManagement\ProviderManager;
use martinsluters\AsynchronousTemplateData\Exceptions\ProviderAlreadyExistsException;
use martinsluters\AsynchronousTemplateData\Exceptions\ProviderDoesNotExistException;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;

/**
 * Unit Tests Related to ProviderManager class
 */
class ProviderManagerTest extends TestCase {

	/**
	 * Provider manager instance - sut.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\ProviderManagement\ProviderManager
	 */
	private ProviderManager $provider_manager_sut;

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		$this->provider_manager_sut = new ProviderManager();
	}

	/**
	 * Make sure an empty array is returned if no providers added.
	 *
	 * @return void
	 */
	public function testEmptyArrayOfProvidersIfNoProvidersAdded(): void {
		$this->assertIsArray( $this->provider_manager_sut->getProviders() );
		$this->assertEmpty( $this->provider_manager_sut->getProviders() );
	}

	/**
	 * Make sure an array contains the same providers that were added.
	 *
	 * @return void
	 */
	public function testSameProvidersInArrayThatWereAdded(): void {
		$provider1 = Mockery::mock( DataProviderInterface::class );
		$provider2 = Mockery::mock( DataProviderInterface::class );
		$this->provider_manager_sut->addProvider( 'provider-1', $provider1 );
		$this->provider_manager_sut->addProvider( 'provider-2', $provider2 );

		$this->assertSame( $provider1, $this->provider_manager_sut->getProviders()['provider-1'] );
		$this->assertSame( $provider2, $this->provider_manager_sut->getProviders()['provider-2'] );
	}

	/**
	 * Make sure that an ProviderDoesNotExistException exception is thrown if try to get provider from provider manager
	 * that does not exist.
	 *
	 * @return void
	 */
	public function testThrowsExceptionIfProviderDoesNotExist(): void {
		$this->expectException( ProviderDoesNotExistException::class );
		$this->provider_manager_sut->getProvider( 'provider-1' );
	}

	/**
	 * Make sure (bool) true is returned in case provider exists in provider manager when checking if a provider with key exist
	 * in provider manager.
	 *
	 * @return void
	 */
	public function testReturnTrueIfProviderWithGivenKeyAlreadyExist(): void {
		$provider = Mockery::mock( DataProviderInterface::class );
		$this->provider_manager_sut->addProvider( 'provider', $provider );

		$this->assertTrue( $this->provider_manager_sut->providerExist( 'provider' ) );
	}

	/**
	 * Make sure (bool) false is returned in case provider does not exist in provider manager when checking if a provider with key exist
	 * in provider manager.
	 *
	 * @return void
	 */
	public function testReturnFalseIfProviderWithGivenKeyDoesNotExist(): void {
		$this->assertFalse( $this->provider_manager_sut->providerExist( 'provider' ) );
	}

	/**
	 * Make sure that an exception ProviderAlreadyExistsException is thrown if try to add a provider with a key that already is
	 * in provider manager.
	 *
	 * @return void
	 */
	public function testThrowsExceptionIfProviderKeyIsAlreadyUsed(): void {
		$provider = Mockery::mock( DataProviderInterface::class );
		$this->provider_manager_sut->addProvider( 'provider', $provider );

		$this->expectException( ProviderAlreadyExistsException::class );
		$this->provider_manager_sut->addProvider( 'provider', $provider );
	}
}
