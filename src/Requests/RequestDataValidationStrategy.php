<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Requests;

use martinsluters\AsynchronousTemplateData\RequestDataTrait;
use martinsluters\AsynchronousTemplateData\Requests\RequestData;

/**
 * Abstract Request Validation Strategy class to be implemented by a more concrete validation strategy.
 */
abstract class RequestDataValidationStrategy {

	use RequestDataTrait;

	/**
	 * Test if is an HTTP POST request.
	 *
	 * @return boolean
	 */
	protected function isPostRequest(): bool {
		if ( 'POST' === $this->getRequestData()->getHttpRequest()->server->get( 'REQUEST_METHOD' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Test if HTTP request header is 'application/json'.
	 *
	 * @return boolean
	 */
	protected function isValidRequestContentType(): bool {
		if ( 'application/json' === $this->getRequestData()->getHttpRequest()->headers->get( 'Content-Type' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Test if HTTP request body is valid.
	 *
	 * @return boolean
	 */
	protected function isValidRequestBody(): bool {
		$json_string = $this->getRequestData()->getHttpRequest()->getContent();
		if ( ! $this->canDecodeJsonString( $json_string ) ) {
			return false;
		}

		/**
		 * Outside primitive should be an object instead of an array.
		 *
		 * @see https://cheatsheetseries.owasp.org/cheatsheets/AJAX_Security_Cheat_Sheet.html
		 */
		if ( 0 !== strpos( $json_string, '{' ) ) {
			return false;
		}

		$json_object = json_decode( $json_string, true );
		if ( ! \array_key_exists( $this->getRequestData()->getPluginData()->getProviderIdentificationPropertyName(), $json_object ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Test if JSON String can be decoded.
	 *
	 * @param string $string JSON String in question.
	 * @return boolean
	 */
	protected function canDecodeJsonString( string $string ): bool {
		try {
			json_decode( $string, true, 512, JSON_THROW_ON_ERROR );
		} catch ( \JsonException $e ) {
			return false;
		}
		return true;
	}

	/**
	 * Main validation method.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\Requests\RequestData $request_data Request Data instance in question.
	 * @return boolean
	 */
	public function validate( RequestData $request_data ): bool {
		$this->setRequestData( $request_data );

		return $this->validateUsingConcreteStrategy();
	}

	/**
	 * Expect concrete strategies to implement the method.
	 *
	 * @return boolean
	 */
	abstract protected function validateUsingConcreteStrategy(): bool;
}
