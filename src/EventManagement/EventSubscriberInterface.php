<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\EventManagement;

interface EventSubscriberInterface {

	/**
	 * Return array of Event Subscriptions
	 *
	 * @return EventSubscription[] array of Event Subscriptions.
	 */
	public function getSubscribedEvents(): array;
}
