<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Requests;

use martinsluters\AsynchronousTemplateData\Requests\RequestDataValidationStrategy;
use martinsluters\AsynchronousTemplateData\Requests\RequestData;

/**
 * Concrete Request Data validation strategy to be used for WP Ajax Requests.
 */
class RequestDataValidationAjaxStrategy extends RequestDataValidationStrategy {

	/**
	 * Test if is a valid WP Ajax request.
	 *
	 * @return boolean
	 */
	protected function isValidAjaxRequest(): bool {
		return (bool) check_ajax_referer(
			$this->getRequestData()->getPluginData()->getSecurityNonceActionName(),
			$this->getRequestData()->getPluginData()->getSecurityNonceRequestParameterName(),
			false
		);
	}

	/**
	 * Main validation method to check if a request data is valid
	 * in this validation strategy's context.
	 *
	 * @return boolean
	 */
	protected function validateUsingConcreteStrategy(): bool {

		// Is POST request?
		if ( ! $this->isPostRequest() ) {
			return false;
		}

		// Nonce valid?
		if ( ! $this->isValidAjaxRequest() ) {
			return false;
		}

		// Content Type valid?
		if ( ! $this->isValidRequestContentType() ) {
			return false;
		}

		// Body/content valid?
		if ( ! $this->isValidRequestBody() ) {
			return false;
		}

		return true;
	}
}
