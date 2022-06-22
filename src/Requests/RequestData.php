<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Requests;

use Symfony\Component\HttpFoundation\Request;
use martinsluters\AsynchronousTemplateData\Requests\RequestDataValidationStrategy;
use martinsluters\AsynchronousTemplateData\PluginDataTrait;
use martinsluters\AsynchronousTemplateData\PluginData;

/**
 * Request Data Class.
 */
class RequestData {

	use PluginDataTrait;

	/**
	 * Constructor.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\PluginData                             $plugin_data Plugin Data instance.
	 * @param \Symfony\Component\HttpFoundation\Request                                      $http_request Symfony HTTP Request instance.
	 * @param \martinsluters\AsynchronousTemplateData\Requests\RequestDataValidationStrategy $request_data_validator Request validation strategy.
	 */
	public function __construct(
		PluginData $plugin_data,
		private Request $http_request,
		private RequestDataValidationStrategy $request_data_validator
	) {
		$this->setPluginData( $plugin_data );
	}

	/**
	 * Getter of Symfony HTTP Request instance.
	 *
	 * @return \Symfony\Component\HttpFoundation\Request
	 */
	public function getHttpRequest(): Request {
		return $this->http_request;
	}

	/**
	 * Getter of Request Data Validation Strategy instance.
	 *
	 * @return \martinsluters\AsynchronousTemplateData\Requests\RequestDataValidationStrategy
	 */
	public function getValidator(): RequestDataValidationStrategy {
		return $this->request_data_validator;
	}

	/**
	 * Test if Request Data is valid using a validation strategy provided in constructor.
	 *
	 * @return boolean
	 */
	public function isValid(): bool {
		return $this->getValidator()->validate( $this );
	}

	/**
	 * Getter of Symfony HTTP Request Body.
	 *
	 * @return string
	 */
	public function getRequestBody(): string {
		return $this->getHttpRequest()->getContent();
	}
}
