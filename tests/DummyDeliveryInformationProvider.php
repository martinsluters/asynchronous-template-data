<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests;

use martinsluters\AsynchronousTemplateData\Providers\AbstractDataProvider;
use martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument;

/**
 * Simple delivery information provider for testing purposes.
 */
class DummyDeliveryInformationProvider extends AbstractDataProvider {

	/**
	 * Returns a string of delivery information of a WooCommerce product.
	 *
	 * @param AbstractLookupArgument $argument Instance implementing AbstractLookupArgument.
	 * @return string
	 */
	public function getConcreteData( AbstractLookupArgument $argument ): string {
		return (string) apply_filters( 'ml_asynchronous_template_data_dummy_delivery_information', 'Lightning Fast Delivery!' );
	}
}
