<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Providers;

use martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument;

/**
 * Abstract class of template data provider.
 */
abstract class AbstractDataProvider implements DataProviderInterface {

	/**
	 * Expect child classes to implement a concrete process of gathering data.
	 *
	 * @param AbstractLookupArgument $argument Instance implementing AbstractLookupArgument.
	 * @return string
	 */
	abstract public function getConcreteData( AbstractLookupArgument $argument ): string;

	/**
	 * Returns data.
	 *
	 * @param AbstractLookupArgument $argument Instance implementing AbstractLookupArgument.
	 * @return string
	 */
	public function getData( AbstractLookupArgument $argument ): string {

		$short_circuit_data = apply_filters( 'ml_asynchronous_template_data_pre_get_data', null, $argument );

		// Short circuit.
		if ( \is_string( $short_circuit_data ) ) {
			return $short_circuit_data;
		}

		return (string) apply_filters(
			'ml_asynchronous_template_data',
			$this->getConcreteData( $argument ),
			$argument
		);
	}
}
