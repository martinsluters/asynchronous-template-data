<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Unit\RequestData;

use \Mockery;
use Symfony\Component\HttpFoundation\Request;
use martinsluters\AsynchronousTemplateData\PluginData;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;
use martinsluters\AsynchronousTemplateData\Requests\RequestData;
use martinsluters\AsynchronousTemplateData\Requests\RequestDataValidationStrategy;

/**
 * Unit Tests related to RequestDataValidator class.
 */
class RequestDataValidatorTest extends TestCase {

	/**
	 * RequestData Instance to test - sut.
	 *
	 * @var RequestData
	 */
	private RequestData $request_data_sut_sut;

	/**
	 * Test double of PluginData.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\AjaxHandling\PluginData
	 */
	private PluginData $plugin_data_test_double;

	/**
	 * Test double of \Symfony\Component\HttpFoundation\Request.
	 *
	 * @var \Symfony\Component\HttpFoundation\Request
	 */
	private Request $symfony_request_test_double;

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		$this->plugin_data_test_double = Mockery::mock( PluginData::class );
		$this->symfony_request_test_double = Mockery::mock( Request::class );
		$this->request_data_validator = Mockery::mock( RequestDataValidationStrategy::class );
		$this->request_data_sut = new RequestData( $this->plugin_data_test_double, $this->symfony_request_test_double, $this->request_data_validator );
	}

	/**
	 * Set HTTP request body/content expectation.
	 *
	 * @param string $expected_body Expected HTTP request body content.
	 * @return void
	 */
	protected function expectHttpRequestBodyToReturn( string $expected_body ): void {

		$this->plugin_data_test_double->shouldReceive( 'getProviderIdentificationPropertyName' )
			->zeroOrMoreTimes()
			->andReturn( 'provider_key' );

		$this->symfony_request_test_double
			->shouldReceive( 'getContent' )
			->times( 1 )
			->andReturn( $expected_body );
	}

	/**
	 * Make sure the getter of request body returns whatever HTTP Request wrapper instance returned.
	 *
	 * @return void
	 */
	public function testMustReturnRequestBody() {
		$this->expectHttpRequestBodyToReturn( '{"provider_key":"provider-key-x","wp_post_id":2}' );
		$this->assertSame(
			'{"provider_key":"provider-key-x","wp_post_id":2}',
			$this->request_data_sut->getRequestBody()
		);
	}

	/**
	 * Make sure the getter of HTTP request wrapper returns HTTP request wrapper instance.
	 *
	 * @return void
	 */
	public function testMustReturnHttpRequestWrapperInstance() {
		$this->assertInstanceOf( Request::class, $this->request_data_sut->getHttpRequest() );
	}

	/**
	 * Make sure the getter of request data validation strategy returns request data validation strategy instance.
	 *
	 * @return void
	 */
	public function testMustReturnRequestDataValidationStrategyInstance() {
		$this->assertInstanceOf( RequestDataValidationStrategy::class, $this->request_data_sut->getValidator() );
	}

	/**
	 * Make sure that isValid is just a wrapper method of validation strategy's
	 * request data validity check.
	 *
	 * @return void
	 */
	public function testValidityCheckReturnsWhateverValidationStrategyReturns() {
		$this->request_data_validator
			->shouldReceive( 'validate' )
			->times( 2 )
			->andReturn( false, true );

		$this->assertFalse( $this->request_data_sut->isValid() );
		$this->assertTrue( $this->request_data_sut->isValid() );
	}
}
