<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\AjaxHandling;

use martinsluters\AsynchronousTemplateData\EventManagement\EventSubscriberInterface;

/**
 * Ajax Handling Interface for WordPress Ajax requests.
 */
interface AjaxHandlerInterface extends EventSubscriberInterface {

	/**
	 * Main Ajax action handler method.
	 *
	 * @return void
	 */
	public function handleAction(): void;
}
