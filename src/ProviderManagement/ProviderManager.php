<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\ProviderManagement;

use martinsluters\AsynchronousTemplateData\Exceptions\ProviderDoesNotExistException;
use martinsluters\AsynchronousTemplateData\Exceptions\ProviderAlreadyExistsException;
use martinsluters\AsynchronousTemplateData\Providers\DataProviderInterface;

/**
 * Class responsible for managing available Data Providers. (Container)
 */
class ProviderManager {

	/**
	 * Array of Data Providers
	 *
	 * @var array<DataProviderInterface> containing martinsluters\AsynchronousTemplateData\Providers\DataProviderInterface instances.
	 */
	private array $providers = [];

	/**
	 * Getter or providers array
	 *
	 * @return array<DataProviderInterface>
	 */
	public function getProviders(): array {
		return $this->providers;
	}

	/**
	 * Populate providers array;
	 *
	 * @param string                $provider_key a unique key of a provider.
	 * @param DataProviderInterface $provider Implementation of DataProviderInterface.
	 * @return void
	 * @throws ProviderAlreadyExistsException If provider key already used.
	 */
	public function addProvider( string $provider_key, DataProviderInterface $provider ): void {
		if ( $this->providerExist( $provider_key ) ) {
			throw new ProviderAlreadyExistsException( $provider_key, 'Provider key already used.' );
		}

		$this->providers[ $provider_key ] = $provider;
	}

	/**
	 * Getter of a single provider.
	 *
	 * @param string $provider_key a unique key of a provider.
	 * @return DataProviderInterface Provider.
	 * @throws ProviderDoesNotExistException If provider does not exist.
	 */
	public function getProvider( string $provider_key ): DataProviderInterface {
		if ( ! $this->providerExist( $provider_key ) ) {
			throw new ProviderDoesNotExistException( $provider_key, 'Provider does not exist.' );
		}

		return $this->providers[ $provider_key ];
	}

	/**
	 * Check if provider is already added with a given key.
	 *
	 * @param string $provider_key unique key of a provider.
	 * @return boolean
	 */
	public function providerExist( string $provider_key ): bool {
		return \array_key_exists( $provider_key, $this->getProviders() );
	}
}
