<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Unit\Providers;

use Mockery;
use WP_Mock;
use martinsluters\AsynchronousTemplateData\Providers\DataProviderInterface;
use martinsluters\AsynchronousTemplateData\Providers\AbstractDataProvider;
use martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;

/**
 * Unit Tests Related to AbstractDataProvider class
 */
class AbstractDataProviderTest extends TestCase {

	/**
	 * Instance that implements DataProviderInterface.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Providers\DataProviderInterface
	 */
	private DataProviderInterface $data_provider_sut;

	/**
	 * LookupArgument test double
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument
	 */
	private AbstractLookupArgument $lookup_argument_test_double;

	/**
	 * Create test objects.
	 *
	 * @return void
	 */
	public function setUp(): void {

		/**
		 *  Since testing abstract class make Mockery partial.
		 *
		 *  @see https://docs.mockery.io/en/latest/reference/partial_mocks.html
		 */
		$this->data_provider_sut = Mockery::mock( AbstractDataProvider::class )->makePartial();
		$this->lookup_argument_test_double = Mockery::mock( AbstractLookupArgument::class );
	}

	/**
	 * Make sure the abstract data provider interface DataProviderInterface contract is in place.
	 *
	 * @return void
	 */
	public function testIsInstanceOfDataProviderInterface(): void {
		$this->assertInstanceOf( DataProviderInterface::class, $this->data_provider_sut );
	}

	/**
	 * Make sure the mocked data provider is instance of abstract class AbstractDataProvider.
	 *
	 * @return void
	 */
	public function testIsInstanceOfAbstractDataProvider(): void {
		$this->assertInstanceOf( AbstractDataProvider::class, $this->data_provider_sut );
	}

	/**
	 * Make sure it is possible short-circuit data gathering.
	 * Useful for when using caching.
	 * e.g. cache results of resource/time-heavy information gathering for n minutes
	 *
	 * @return void
	 */
	public function testDataCanBeReturnedUsingShortCircuitVersionOfMessage(): void {

		WP_Mock::onFilter( 'ml_asynchronous_template_data_pre_get_data' )
		->with( null, $this->lookup_argument_test_double )
		->reply( 'Amended Delivery Information Message' );

		$this->assertSame(
			'Amended Delivery Information Message',
			$this->data_provider_sut->getData( $this->lookup_argument_test_double )
		);
	}

	/**
	 * Make sure that a request for a concrete data from a provider is not requested when a short-circuit version is used.
	 * It is important to test this to make sure the expensive call is never made.
	 *
	 * @return void
	 */
	public function testACallToProviderToGetConcreteDataIsNotMadeWhenShortCircuitUsed(): void {

		WP_Mock::onFilter( 'ml_asynchronous_template_data_pre_get_data' )
			->with( null, $this->lookup_argument_test_double )
			->reply( 'Amended Delivery Information Message' );

		$this->data_provider_sut->shouldNotReceive( 'getConcreteData' );

		$this->data_provider_sut->getData( $this->lookup_argument_test_double );
	}

	/**
	 * Make sure that data is coming from a concrete implementation.
	 * A direct call to provider is made and short-circuit is never hit.
	 *
	 * @return void
	 */
	public function testACallToProviderToGetConcreteDataIsMadeWhenShortCircuitNotUsed(): void {

		WP_Mock::onFilter( 'ml_asynchronous_template_data_pre_get_data' )
			->with( null, $this->lookup_argument_test_double )
			->reply( null );

		$this->data_provider_sut
			->shouldReceive( 'getConcreteData' )
			->once()
			->andReturn( 'Concrete Delivery Information Message' );

		$this->assertSame(
			'Concrete Delivery Information Message',
			$this->data_provider_sut->getData( $this->lookup_argument_test_double )
		);
	}

	/**
	 * Make sure that if WordPress filter (which makes possible to short-circuit a call of a concrete data
	 * call) does not return a string or null then fall back to and rely on the concrete data call.
	 *
	 * @return void
	 */
	public function testFilterReturnsStringOtherwiseRelyOnNonShortCircuitVersion(): void {

		WP_Mock::onFilter( 'ml_asynchronous_template_data_pre_get_data' )
			->with( null, $this->lookup_argument_test_double )
			->reply( true );

		$this->data_provider_sut
			->shouldReceive( 'getConcreteData' )
			->once()
			->andReturn( 'Concrete Delivery Information Message' );

		$this->assertSame(
			'Concrete Delivery Information Message',
			$this->data_provider_sut->getData( $this->lookup_argument_test_double )
		);
	}
}
