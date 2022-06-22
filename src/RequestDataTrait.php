<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData;

use martinsluters\AsynchronousTemplateData\Requests\RequestData;

trait RequestDataTrait {

	/**
	 * Request data instance.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Requests\RequestData
	 */
	private RequestData $request_data; //phpcs:ignore

	/**
	 * Getter of request data instance.
	 *
	 * @return \martinsluters\AsynchronousTemplateData\Requests\RequestData
	 */
	public function getRequestData(): RequestData {
		return $this->request_data;
	}

	/**
	 * Setter of plugin data.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\Requests\RequestData $request_data RequestData instance.
	 * @return void
	 */
	protected function setRequestData( RequestData $request_data ): void {
		$this->request_data = $request_data;
	}
}
