<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Providers;

use \YITH_Delivery_Date_Product_Frontend;
use martinsluters\AsynchronousTemplateData\Providers\AbstractDataProvider;
use martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument;
use martinsluters\AsynchronousTemplateData\Arguments\LookupArgument;

/**
 * Adapter of YITH WooCommerce Delivery Date.
 */
class YithProvider extends AbstractDataProvider {

	/**
	 * Get YITH WooCommerce Delivery Date Information String.
	 *
	 * @param integer $product_id Product ID (WP post ID).
	 * @return string
	 * @todo Apply WP filters for output.
	 */
	protected function getYITHDeliveryInformation( int $product_id ): string {
		$product = wc_get_product( $product_id );
		$data = (array) $this->yith_dd_product_frontend->get_date_info( $product );
		if ( \array_key_exists( 'last_shipping_date', $data ) || \array_key_exists( 'delivery_date', $data ) ) {
			return (string) $data['last_shipping_date'] . (string) $data['delivery_date'];
		}
		return '';
	}

	/**
	 * Constructor
	 *
	 * @param \YITH_Delivery_Date_Product_Frontend $yith_dd_product_frontend Instance of YITH_Delivery_Date_Product_Frontend that is responsible of providing
	 * delivery information.
	 * @return void
	 */
	public function __construct( protected readonly YITH_Delivery_Date_Product_Frontend $yith_dd_product_frontend ) {
	}

	/**
	 * Returns a string of delivery information of a WooCommerce product.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument $argument An instance of AbstractLookupArgument.
	 * @return string
	 * @throws \InvalidArgumentException Thrown in case lookup argument is not an instance of LookupArgument.
	 */
	public function getConcreteData( AbstractLookupArgument $argument ): string {
		if ( ! $argument instanceof LookupArgument ) {
			throw new \InvalidArgumentException( 'Invalid Lookup Argument type provided' );
		}
		return (string) apply_filters( 'ml_asynchronous_template_data_yith_delivery_information', $this->getYITHDeliveryInformation( $argument->wp_post_id ), $argument, $this->yith_dd_product_frontend );
	}
}
