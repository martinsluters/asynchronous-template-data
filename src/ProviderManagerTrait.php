<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData;

use martinsluters\AsynchronousTemplateData\ProviderManagement\ProviderManager;

trait ProviderManagerTrait {

	/**
	 * Provider manager instance.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\ProviderManagement\ProviderManager
	 */
	private ProviderManager $provider_manager;

	/**
	 * Getter of provider manager.
	 *
	 * @return \martinsluters\AsynchronousTemplateData\ProviderManagement\ProviderManager
	 * @throws \Exception Is thrown if getProviderManager is called before iti is initialized.
	 */
	public function getProviderManager(): ProviderManager {
		try {
			/**
			 * Return provider_manager property.
			 *
			 * @throws \Error if accessed before initialized
			 */
			return $this->provider_manager;
		} catch ( \Error $th ) {
			throw new \Exception( 'Provider manager can\'t be accessed before it is initialized.' );
		}
	}

	/**
	 * Setter of provider manager.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\ProviderManagement\ProviderManager $provider_manager
	 * @return void
	 */
	protected function setProviderManager( ProviderManager $provider_manager ): void {
		$this->provider_manager = $provider_manager;
	}
}
