<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Unit\RequestData;

use Mockery;
use WP_Mock;
use Symfony\Component\HttpFoundation\Request;
use martinsluters\AsynchronousTemplateData\Requests\RequestDataValidationAjaxStrategy;
use martinsluters\AsynchronousTemplateData\Requests\RequestData;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;
use martinsluters\AsynchronousTemplateData\PluginData;

/**
 * Unit Tests related to RequestDataValidationAjaxStrategy class.
 */
class RequestDataValidationAjaxStrategyTest extends TestCase {

	/**
	 * Test double of RequestData.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Requests\RequestData
	 */
	private RequestData $request_data_test_double;

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
	 * Instance of RequestDataValidationAjaxStrategy - sut.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Requests\RequestDataValidationAjaxStrategy
	 */
	private RequestDataValidationAjaxStrategy $request_data_validator_sut;

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		$this->plugin_data_test_double = Mockery::mock( PluginData::class );
		$this->symfony_request_test_double = Mockery::mock( Request::class );
		$this->symfony_request_test_double->server = Mockery::mock( \stdClass::class );
		$this->symfony_request_test_double->headers = Mockery::mock( \stdClass::class );
		$this->request_data_validator_sut = new RequestDataValidationAjaxStrategy();
		$this->request_data_test_double = Mockery::mock(
			RequestData::class,
			[
				$this->plugin_data_test_double,
				$this->symfony_request_test_double,
				$this->request_data_validator_sut,
			]
		);
	}

	/**
	 * Must be an invalid request if an HTTP request is not using POST method.
	 *
	 * @return void
	 */
	public function testInvalidRequestDataIfNotHttpPOSTMethod(): void {
		$this->expectGetHttpRequestCalledAndHTTPRequestReturned();
		$this->expectHTTPRequestMethodToBe( 'GET' );

		$this->assertFalse( $this->request_data_validator_sut->validate( $this->request_data_test_double ) );
	}

	/**
	 * Must be an valid request if an HTTP request is using POST method.
	 *
	 * @return void
	 */
	public function testValidRequestDataIfHttpPostMethod(): void {
		$this->expectGetHttpRequestCalledAndHTTPRequestReturned();
		$this->expectGetPluginDataCalledAndPluginDataReturned();
		$this->expectCheckAjaxRefererToReturn( true );
		$this->expectHTTPRequestMethodToBe( 'POST' );
		$this->expectHttpRequestHeaderContentTypeToReturn( 'application/json' );
		$this->expectHttpRequestBodyToReturn( '{"provider_key":"provider-key-x","wp_post_id":2}' );

		$this->assertTrue( $this->request_data_validator_sut->validate( $this->request_data_test_double ) );
	}

	/**
	 * Must be an invalid request if an invalid WP nonce is used.
	 *
	 * @return void
	 */
	public function testInvalidRequestDataIfInvalidNonce(): void {
		$this->expectGetHttpRequestCalledAndHTTPRequestReturned();
		$this->expectGetPluginDataCalledAndPluginDataReturned();
		$this->expectHTTPRequestMethodToBe( 'POST' );
		$this->expectCheckAjaxRefererToReturn( false );

		$this->assertFalse( $this->request_data_validator_sut->validate( $this->request_data_test_double ) );
	}

	/**
	 * Must be a valid request if a valid WP nonce is not used.
	 *
	 * @return void
	 */
	public function testValidRequestDataIfValidNonce(): void {
		$this->expectGetHttpRequestCalledAndHTTPRequestReturned();
		$this->expectGetPluginDataCalledAndPluginDataReturned();
		$this->expectHTTPRequestMethodToBe( 'POST' );
		$this->expectCheckAjaxRefererToReturn( true );
		$this->expectHttpRequestHeaderContentTypeToReturn( 'application/json' );
		$this->expectHttpRequestBodyToReturn( '{"provider_key":"provider-key-x","wp_post_id":2}' );

		$this->assertTrue( $this->request_data_validator_sut->validate( $this->request_data_test_double ) );
	}

	/**
	 * Must be an invalid request if an invalid HTTP request header content type is provided.
	 *
	 * @return void
	 */
	public function testInvalidRequestDataIfInvalidHttpRequestHeaderContentType(): void {
		$this->expectGetHttpRequestCalledAndHTTPRequestReturned();
		$this->expectGetPluginDataCalledAndPluginDataReturned();
		$this->expectHTTPRequestMethodToBe( 'POST' );
		$this->expectCheckAjaxRefererToReturn( true );
		$this->expectHttpRequestHeaderContentTypeToReturn( 'application/x-www-form-urlencoded' );

		$this->assertFalse( $this->request_data_validator_sut->validate( $this->request_data_test_double ) );
	}

	/**
	 * Must be a valid request if a valid HTTP request header content type is provided.
	 *
	 * @return void
	 */
	public function testValidRequestDataIfValidHttpRequestHeaderContentType(): void {
		$this->expectGetHttpRequestCalledAndHTTPRequestReturned();
		$this->expectGetPluginDataCalledAndPluginDataReturned();
		$this->expectHTTPRequestMethodToBe( 'POST' );
		$this->expectCheckAjaxRefererToReturn( true );
		$this->expectHttpRequestHeaderContentTypeToReturn( 'application/json' );
		$this->expectHttpRequestBodyToReturn( '{"provider_key":"provider-key-x","wp_post_id":2}' );

		$this->assertTrue( $this->request_data_validator_sut->validate( $this->request_data_test_double ) );
	}

	/**
	 * Must be an invalid request if an invalid HTTP request body content type is provided.
	 *
	 * @param string $invalid_json_string Invalid JSON string in question.
	 * @return void
	 * @dataProvider invalidJsonStrings
	 */
	public function testInvalidRequestDataIfInvalidHttpRequestBody( string $invalid_json_string ): void {
		$this->expectGetHttpRequestCalledAndHTTPRequestReturned();
		$this->expectGetPluginDataCalledAndPluginDataReturned();
		$this->expectHTTPRequestMethodToBe( 'POST' );
		$this->expectCheckAjaxRefererToReturn( true );
		$this->expectHttpRequestHeaderContentTypeToReturn( 'application/json' );
		$this->expectHttpRequestBodyToReturn( $invalid_json_string );

		$this->assertFalse( $this->request_data_validator_sut->validate( $this->request_data_test_double ) );
	}

	/**
	 * Must be a valid request if a valid HTTP request body content type is provided.
	 *
	 * @return void
	 */
	public function testValidRequestDataIfValidHttpRequestBody(): void {
		$this->expectGetHttpRequestCalledAndHTTPRequestReturned();
		$this->expectGetPluginDataCalledAndPluginDataReturned();
		$this->expectHTTPRequestMethodToBe( 'POST' );
		$this->expectCheckAjaxRefererToReturn( true );
		$this->expectHttpRequestHeaderContentTypeToReturn( 'application/json' );
		$this->expectHttpRequestBodyToReturn( '{"provider_key":"provider-key-x","wp_post_id":2}' );

		$this->assertTrue( $this->request_data_validator_sut->validate( $this->request_data_test_double ) );
	}

	/**
	 * Set WP's 'check_ajax_referer' expectation.
	 *
	 * @param boolean $expected_validity Expectation of WP nonce validity.
	 * @return void
	 */
	protected function expectCheckAjaxRefererToReturn( bool $expected_validity ): void {

		$this->plugin_data_test_double
			->shouldReceive( 'getSecurityNonceActionName' )
			->times( 1 )
			->andReturn( 'some_nonce_name' );

		$this->plugin_data_test_double
			->shouldReceive( 'getSecurityNonceRequestParameterName' )
			->times( 1 )
			->andReturn( 'security' );

		WP_Mock::userFunction(
			'check_ajax_referer',
			[
				'args' => [ 'some_nonce_name', 'security', false ],
				'times' => 1,
				'return' => $expected_validity,
			]
		);
	}

	/**
	 * Set HTTP request method expectation.
	 *
	 * @param string $expected_http_request_method Expectation of HTTP request method used.
	 * @return void
	 */
	protected function expectHTTPRequestMethodToBe( string $expected_http_request_method ): void {
		$this->symfony_request_test_double->server
			->shouldReceive( 'get' )
			->times( 1 )
			->andReturn( $expected_http_request_method );
	}

	/**
	 * Set HTTP request content type expectation.
	 *
	 * @param string $expected_http_request_content_type Expected HTTP request content type.
	 * @return void
	 */
	protected function expectHttpRequestHeaderContentTypeToReturn( string $expected_http_request_content_type ): void {
		$this->symfony_request_test_double->headers
			->shouldReceive( 'get' )
			->times( 1 )
			->andReturn( $expected_http_request_content_type );
	}

	/**
	 * Set HTTP request body content expectation.
	 *
	 * @param string $expected_http_request_body Expected HTTP request body content.
	 * @return void
	 */
	protected function expectHttpRequestBodyToReturn( string $expected_http_request_body ): void {

		$this->plugin_data_test_double
			->shouldReceive( 'getProviderIdentificationPropertyName' )
			->zeroOrMoreTimes()
			->andReturn( 'provider_key' );

		$this->symfony_request_test_double
			->shouldReceive( 'getContent' )
			->times( 1 )
			->andReturn( $expected_http_request_body );
	}

	/**
	 * Set GetHttpRequest expectation to receive HTTP Request wrapper test double instance.
	 *
	 * @return void
	 */
	protected function expectGetHttpRequestCalledAndHTTPRequestReturned(): void {
		$this->request_data_test_double
			->shouldReceive( 'getHttpRequest' )
			->atLeast()
			->times( 1 )
			->andReturn( $this->symfony_request_test_double );
	}
	/**
	 * Set getPluginData expectation to receive Plugin Data test double instance.
	 *
	 * @return void
	 */
	protected function expectGetPluginDataCalledAndPluginDataReturned(): void {
		$this->request_data_test_double
			->shouldReceive( 'getPluginData' )
			->atLeast()
			->times( 1 )
			->andReturn( $this->plugin_data_test_double );
	}

	/**
	 * Data provider with invalid json strings.
	 *
	 * @return array
	 */
	protected function invalidJsonStrings(): array {
		return [
			'when syntax error in JSON string' => [ '{missing_opening":"provider-key-x","wp_post_id":2}' ],
			'when "provider_key" object property missing in JSON string' => [ '{"other_key":"provider-key-x","wp_post_id":2}' ],
			'when outside primitive is not an object in JSON string (@see https://cheatsheetseries.owasp.org/cheatsheets/AJAX_Security_Cheat_Sheet.html)' => [ '[{"provider_key":"provider-key-x","wp_post_id":2}]' ],
		];
	}
}
