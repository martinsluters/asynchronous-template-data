<?php
declare( strict_types=1 );

/**
 * Plugin Name: Client MU Plugin
 * Description: Client MU Plugin for testing purposes
 */

namespace martinsluters\AsynchronousTemplateData\Tests;

use martinsluters\AsynchronousTemplateData\Bootstrap;
use martinsluters\AsynchronousTemplateData\Arguments\LookupArgument;

/**
 * Test client plugin class
 */
class Plugin {

	public const PROVIDER_1_KEY = 'test-delivery-information-provider-1';
	public const PROVIDER_2_KEY = 'test-delivery-information-provider-2';

	/**
	 * Instance of Asynchronous Template Data main plugin class.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Bootstrap
	 */
	public static Bootstrap $delivery_information_plugin;

	/**
	 * Initial plugin action
	 *
	 * @return void
	 */
	public static function init(): void {
		add_action(
			'plugins_loaded',
			function() {
				if ( class_exists( '\martinsluters\AsynchronousTemplateData\Bootstrap' ) && class_exists( 'woocommerce' ) ) {
					self::$delivery_information_plugin = Bootstrap::getInstance();
					self::addDeliveryInformationProvider();
					self::addContentFilter();
				}
			}
		);
	}

	/**
	 * We need at least one provider.
	 *
	 * @return void
	 */
	public static function addDeliveryInformationProvider(): void {
		self::$delivery_information_plugin->provider_manager->addProvider( self::PROVIDER_1_KEY, new DummyDeliveryInformationProvider() );
		self::$delivery_information_plugin->provider_manager->addProvider( self::PROVIDER_2_KEY, new DummyDeliveryInformationProvider() );
	}

	/**
	 * Hook in the content filter and append a delivery information to a content.
	 *
	 * @return void
	 */
	public static function addContentFilter(): void {

		// Add 1st delivery information section.
		add_action(
			'woocommerce_single_product_summary',
			function(): void {
				self::getRenderDeliveryInformationTemplate( self::PROVIDER_1_KEY );
			},
			25
		);

		// Add 2nd delivery information section.
		add_action(
			'woocommerce_single_product_summary',
			function(): void {
				self::getRenderDeliveryInformationTemplate( self::PROVIDER_2_KEY );
			},
			30
		);

		// Wrap template with div.
		add_action(
			'ml_asynchronous_template_data_pre_template_render',
			function( $product_id, $provider_key ): void {
				echo '<div>';
			},
			10,
			2
		);
		add_action(
			'ml_asynchronous_template_data_after_template_render',
			function( $product_id, $provider_key ): void {
				echo '</div>';
			},
			10,
			2
		);

		// Slightly amend the message via filter.
		add_filter(
			'ml_asynchronous_template_data',
			fn( $message, $argument ) => ( self::PROVIDER_2_KEY === $argument->provider_key ? 'Blazing <strong>Fast</strong> Delivery!' : $message ),
			10,
			3
		);
	}

	/**
	 * Gets delivery information
	 *
	 * @param string $provider_key String representing provider.
	 * @return void
	 */
	public static function getRenderDeliveryInformationTemplate( string $provider_key ): void {
		self::$delivery_information_plugin->getContentController()->showWrapperTemplate(
			new LookupArgument( $provider_key, get_the_ID() )
		);
	}
}

Plugin::init();

