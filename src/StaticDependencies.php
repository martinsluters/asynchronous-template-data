<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData;

use martinsluters\AsynchronousTemplateData\EventManagement\EventSubscriberInterface;
use martinsluters\AsynchronousTemplateData\EventManagement\EventSubscription;
use martinsluters\AsynchronousTemplateData\PluginDataTrait;
use martinsluters\AsynchronousTemplateData\PluginData;

/**
 * Class for enqueueing static dependencies in WP context.
 */
class StaticDependencies implements EventSubscriberInterface {

	use PluginDataTrait;

	/**
	 * Constructor.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\PluginData $plugin_data PluginData instance.
	 */
	public function __construct( PluginData $plugin_data ) {
		$this->setPluginData( $plugin_data );
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSubscribedEvents(): array {
		return [
			EventSubscription::create( 'wp_enqueue_scripts', 'enqueueFrontendJs' ),
		];
	}

	/**
	 * Enqueue Frontend JS dependencies
	 *
	 * @return void
	 */
	public function enqueueFrontendJs(): void {
		wp_enqueue_script( $this->getPluginData()->getPluginTextDomain() . '-js', $this->getPluginData()->getAssetsDirUrl() . 'main.js', [], $this->getPluginData()->getPluginVersion(), true );
		wp_localize_script(
			$this->getPluginData()->getPluginTextDomain() . '-js',
			'dii',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'action_name' => $this->getPluginData()->getAjaxActionName(),
				'nonce_request_parameter_name' => $this->getPluginData()->getSecurityNonceRequestParameterName(),
				'nonce' => wp_create_nonce( $this->getPluginData()->getSecurityNonceActionName() ),
				'loading_text' => $this->getPluginData()->getDataLoadingMessage(),
				'default_fail_text' => $this->getPluginData()->getDataLoadingFailureMessage(),
			]
		);
	}
}
