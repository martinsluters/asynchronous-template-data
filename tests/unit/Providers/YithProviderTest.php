<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Unit\Providers;

use \Mockery;
use \WP_Mock;
use martinsluters\AsynchronousTemplateData\Providers\DataProviderInterface;
use martinsluters\AsynchronousTemplateData\Providers\AbstractDataProvider;
use martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument;
use martinsluters\AsynchronousTemplateData\Arguments\LookupArgument;
use martinsluters\AsynchronousTemplateData\Providers\YithProvider;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;

/**
 * Unit Tests Related to YithDeliveryInformationProvider class
 */
class YithDeliveryInformationProviderTest extends TestCase {

	/**
	 * Instance to tests
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Providers\YithProvider
	 */
	private YithProvider $yith_provider_sut;

	/**
	 * LookupArgument test double
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Arguments\LookupArgument
	 */
	private LookupArgument $lookup_argument_test_double;

	/**
	 * Mock of \YITH_Delivery_Date_Product_Frontend that is responsible of providing
	 * delivery information.
	 *
	 * @var \YITH_Delivery_Date_Product_Frontend
	 */
	protected \YITH_Delivery_Date_Product_Frontend $yith_dd_product_frontend_test_double;

	/**
	 * Set expectation that wc_get_product function is called.
	 *
	 * @return void
	 */
	private function expectMethodWcGetProductCalled(): void {
		WP_Mock::userFunction(
			'wc_get_product',
			[
				'times' => 1,
			]
		);
	}

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		$this->yith_dd_product_frontend_test_double  = Mockery::mock( '\YITH_Delivery_Date_Product_Frontend' );
		$this->lookup_argument_test_double = Mockery::mock( LookupArgument::class, [ 'argument-key-x', 1 ] );
		$this->yith_provider_sut = new YithProvider( $this->yith_dd_product_frontend_test_double );
	}

	/**
	 * Make sure the delivery information provider contract is in place.
	 *
	 * @return void
	 */
	public function testIsInstanceOfDataProviderInterface(): void {
		$this->assertInstanceOf( DataProviderInterface::class, $this->yith_provider_sut );
	}

	/**
	 * Make sure the delivery information provider is instance of abstract class AbstractDataProvider.
	 *
	 * @return void
	 */
	public function testIsInstanceOfAbstractDataProvider(): void {
		$this->assertInstanceOf( AbstractDataProvider::class, $this->yith_provider_sut );
	}

	/**
	 * Make sure the information message is an empty string if YITH WooCommerce Delivery Date could not provide information.
	 *
	 * @return void
	 */
	public function testReturnEmptyStringInformationIfYITHInstanceFailsToReturnDeliveryDateInformaiton(): void {
		$this->expectMethodWcGetProductCalled();
		$this->yith_dd_product_frontend_test_double
			->shouldReceive( 'get_date_info' )
			->times( 1 )
			->andReturn( [] ); // <- missing information

		$this->assertSame( '', $this->yith_provider_sut->getData( $this->lookup_argument_test_double ) );
	}

	/**
	 * Make sure the information message is a non-empty string if YITH WooCommerce Delivery Date is available.
	 *
	 * @return void
	 */
	public function testReturnExpectedStringInformationIfYITHInstanceReturnsDeliveryDateInformaiton(): void {
		$this->expectMethodWcGetProductCalled();
		$this->yith_dd_product_frontend_test_double
			->shouldReceive( 'get_date_info' )
			->times( 1 )
			->andReturn(
				[
					'last_shipping_date' => 'Last Shipping Date',
					'delivery_date' => ' Delivery Date',
				]
			);

		$this->assertSame( 'Last Shipping Date Delivery Date', $this->yith_provider_sut->getData( $this->lookup_argument_test_double ) );
	}

	/**
	 * Should be able to filter information received from YITH WooCommerce Delivery Dates.
	 *
	 * @return void
	 */
	public function testCanFilterDeliveryInformationMessageBeforeReturning(): void {
		$this->expectMethodWcGetProductCalled();
		$this->yith_dd_product_frontend_test_double
			->shouldReceive( 'get_date_info' )
			->times( 1 )
			->andReturn(
				[
					'last_shipping_date' => 'Unfiltered Last Shipping Date',
					'delivery_date' => ' Unfiltered Delivery Date',
				]
			);

		WP_Mock::onFilter( 'ml_asynchronous_template_data_yith_delivery_information' )
			->with( 'Unfiltered Last Shipping Date Unfiltered Delivery Date', $this->lookup_argument_test_double, $this->yith_dd_product_frontend_test_double )
			->reply( 'Filtered Last Shipping Date Filtered Delivery Date' );

		$this->assertSame( 'Filtered Last Shipping Date Filtered Delivery Date', $this->yith_provider_sut->getData( $this->lookup_argument_test_double ) );
	}

	/**
	 * Make sure that if AbstractLookupArgument is not an instance
	 * of LookupArgument concrete class then throws an InvalidArgumentException
	 * exception.
	 *
	 * @return void
	 */
	public function testThrowsInvalidArgumentExceptionIfNotConcreteLookupArgumentInstance(): void {
		// Not a concrete one, i.e. not martinsluters\AsynchronousTemplateData\Arguments\LookupArgument.
		$abstract_lookup_argument = Mockery::mock( AbstractLookupArgument::class );

		$this->expectException( \InvalidArgumentException::class );

		$this->yith_provider_sut->getData( $abstract_lookup_argument );
	}
}
