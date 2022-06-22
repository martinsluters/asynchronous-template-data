<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Unit\ContentController;

use martinsluters\AsynchronousTemplateData\Tests\Unit\ContentController\AbstractContentControllerTest;
use martinsluters\AsynchronousTemplateData\Providers\DataProviderInterface;
use martinsluters\AsynchronousTemplateData\Arguments\LookupArgument;

/**
 * Unit Tests Related to ContentController class, method getAsynchronousTemplateData.
 */
class GetAsynchronousTemplateDataTest extends AbstractContentControllerTest {

	/**
	 * Set PluginData getDataLoadingFailureMessage expectation.
	 *
	 * @param string $expected_loading_failure_message Expected loading failure message.
	 * @return void
	 */
	protected function expectGetDataLoadingFailureMessageCalledAndReturnMessage( string $expected_loading_failure_message ): void {
		$this->plugin_data_test_double->shouldReceive( 'getDataLoadingFailureMessage' )
		->times( 1 )
		->andReturn( $expected_loading_failure_message );
	}

	/**
	 * Set RequestData isValid expectation.
	 *
	 * @param bool $expected_validity Expected validity of request data.
	 * @return void
	 */
	protected function expectRequestDataIsValidCalledAndToReturns( bool $expected_validity ): void {
		$this->request_data_test_double->shouldReceive( 'isValid' )
		->times( 1 )
		->andReturn( $expected_validity );
	}

	/**
	 * Expect LookupArgumentFactory createLookupArgumentFromJsonString to throw an exception.
	 *
	 * @param string $expected_throwable_class Expected class name of throwable exception.
	 * @return void
	 */
	protected function expectLookupArgumentFactoryThrowException( string $expected_throwable_class ): void {
		$this->lookup_argument_factory_test_double->shouldReceive( 'createLookupArgumentFromJsonString' )
			->times( 1 )
			->andThrow( $expected_throwable_class );
	}

	/**
	 * Expect Provider getData to throw an exception.
	 *
	 * @param string $expected_throwable_class Expected class name of throwable exception.
	 * @return void
	 */
	protected function expectProviderThrowException( string $expected_throwable_class ): void {
		$this->provider_test_double->shouldReceive( 'getData' )
			->times( 1 )
			->andThrow( $expected_throwable_class );
	}

	/**
	 * Expect ProviderManager getProvider to throw an exception.
	 *
	 * @param string $expected_throwable_class Expected class name of throwable exception.
	 * @return void
	 */
	protected function expectProviderManagerThrowException( string $expected_throwable_class ): void {
		$this->provider_manager_test_double->shouldReceive( 'getProvider' )
			->times( 1 )
			->andThrow( $expected_throwable_class );
	}

	/**
	 * Set PluginData getRequestBody expectation.
	 *
	 * @param string $expected_body_string_json Expected JSON string from HTTP request's body.
	 * @return void
	 */
	protected function expectRequestBodyReturns( string $expected_body_string_json ): void {
		$this->request_data_test_double->shouldReceive( 'getRequestBody' )
		->times( 1 )
		->andReturn( $expected_body_string_json );
	}

	/**
	 * Set LookupArgumentFactory expectation to return LookupArgument.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\Arguments\LookupArgument $expected_lookup_argument Expected Lookup Argument.
	 * @return void
	 */
	protected function expectLookupArgumentFactoryReturnLookupArgument( LookupArgument $expected_lookup_argument ): void {
		$this->lookup_argument_factory_test_double->shouldReceive( 'createLookupArgumentFromJsonString' )
			->times( 1 )
			->andReturn( $expected_lookup_argument );
	}

	/**
	 * Set ProviderManager expectation to return Provider.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\Providers\DataProviderInterface $expected_provider Expected Provider.
	 * @return void
	 */
	protected function expectProviderManagerReturnProvider( DataProviderInterface $expected_provider ): void {
		$this->provider_manager_test_double->shouldReceive( 'getProvider' )
			->times( 1 )
			->andReturn( $expected_provider );
	}

	/**
	 * Set Provider expectation to return message.
	 *
	 * @param string $expected_information Expected information that Provider instance provided.
	 * @return void
	 */
	protected function expectProviderReturnInformation( string $expected_information ): void {
		$this->provider_test_double->shouldReceive( 'getData' )
			->times( 1 )
			->andReturn( $expected_information );
	}

	/**
	 * Make sure that a default failure message gets returned if request data is not valid.
	 *
	 * @return void
	 */
	public function testReturnDefaultFailureMessageIfRequestInvalid(): void {
		$is_request_data_valid = false;
		$this->expectRequestDataIsValidCalledAndToReturns( $is_request_data_valid );
		$this->expectGetDataLoadingFailureMessageCalledAndReturnMessage( 'Fast Delivery' );

		$this->assertSame( 'Fast Delivery', $this->content_controller_sut->getAsynchronousTemplateData() );
	}

	/**
	 * Make sure that a default failure message gets returned if fails
	 * to create Lookup Argument from Request Data body (json string).
	 *
	 * @return void
	 */
	public function testReturnDefaultFailureMessageIfFailsToCreateLookupArgument(): void {
		$this->expectRequestDataIsValidCalledAndToReturns( true );
		$this->expectRequestBodyReturns( '{"faking_something_invalid"}' );
		$this->expectLookupArgumentFactoryThrowException( \Exception::class );
		$this->expectGetDataLoadingFailureMessageCalledAndReturnMessage( 'Fast Delivery' );

		$this->assertSame( 'Fast Delivery', $this->content_controller_sut->getAsynchronousTemplateData() );
	}

	/**
	 * Make sure that a default failure message gets returned if fails
	 * to find provider.
	 *
	 * @return void
	 */
	public function testReturnDefaultFailureMessageIfFailsToFindProvider(): void {
		$this->expectRequestDataIsValidCalledAndToReturns( true );
		$this->expectRequestBodyReturns( '{"Some exceptionally exciting JSON string that we do not care about now"}' );
		$this->expectLookupArgumentFactoryReturnLookupArgument( $this->lookup_argument );
		$this->expectProviderManagerThrowException( \Exception::class );
		$this->expectGetDataLoadingFailureMessageCalledAndReturnMessage( 'Fast Delivery' );

		$this->assertSame( 'Fast Delivery', $this->content_controller_sut->getAsynchronousTemplateData() );
	}

	/**
	 * Make sure that a default failure message gets returned if fails
	 * to get template data from a provider.
	 *
	 * @return void
	 */
	public function testReturnDefaultFailureMessageIfProviderFailsToProvideContent(): void {
		$this->expectRequestDataIsValidCalledAndToReturns( true );
		$this->expectRequestBodyReturns( '{"Some exceptionally exciting JSON string that we do not care about now"}' );
		$this->expectLookupArgumentFactoryReturnLookupArgument( $this->lookup_argument );
		$this->expectProviderManagerReturnProvider( $this->provider_test_double );
		$this->expectProviderThrowException( \Exception::class );
		$this->expectGetDataLoadingFailureMessageCalledAndReturnMessage( 'Fast Delivery' );

		$this->assertSame( 'Fast Delivery', $this->content_controller_sut->getAsynchronousTemplateData() );
	}

	/**
	 * Make sure that a Provider provider content gets returned.
	 *
	 * @return void
	 */
	public function testReturnProviderProvidedContent(): void {
		$this->expectRequestDataIsValidCalledAndToReturns( true );
		$this->expectRequestBodyReturns( '{"Some exceptionally exciting JSON string that we do not care about now"}' );
		$this->expectLookupArgumentFactoryReturnLookupArgument( $this->lookup_argument );
		$this->expectProviderManagerReturnProvider( $this->provider_test_double );
		$this->expectProviderReturnInformation( 'Blazing <strong>Fast</strong> Delivery' );

		$this->assertSame( 'Blazing <strong>Fast</strong> Delivery', $this->content_controller_sut->getAsynchronousTemplateData() );
	}
}
