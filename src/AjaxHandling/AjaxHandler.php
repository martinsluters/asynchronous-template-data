<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\AjaxHandling;

use martinsluters\AsynchronousTemplateData\AjaxHandling\AjaxHandlerInterface;
use martinsluters\AsynchronousTemplateData\EventManagement\EventSubscription;
use martinsluters\AsynchronousTemplateData\ContentController;
use martinsluters\AsynchronousTemplateData\PluginDataTrait;
use martinsluters\AsynchronousTemplateData\PluginData;

/**
 * Class responsible of managing WordPress Ajax requests.
 */
class AjaxHandler implements AjaxHandlerInterface {

	use PluginDataTrait;

	/**
	 * Constructor.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\PluginData        $plugin_data Plugin Data instance.
	 * @param \martinsluters\AsynchronousTemplateData\ContentController $content_controller Content Controller Instance.
	 */
	public function __construct( PluginData $plugin_data, private ContentController $content_controller ) {
		$this->setPluginData( $plugin_data );
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSubscribedEvents(): array {
		return [
			EventSubscription::create( 'wp_ajax_' . $this->getPluginData()->getAjaxActionName(), 'handleAction' ),
			EventSubscription::create( 'wp_ajax_nopriv_' . $this->getPluginData()->getAjaxActionName(), 'handleAction' ),
		];
	}

	/**
	 * A callback of WordPress Ajax request to display information.
	 *
	 * @phpstan-return never
	 * @return void
	 */
	public function handleAction(): void {
		echo wp_kses_data( $this->content_controller->getAsynchronousTemplateData() );
		wp_die();
	}
}
