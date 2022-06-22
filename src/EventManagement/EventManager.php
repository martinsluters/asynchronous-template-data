<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\EventManagement;

/**
 * Class responsible for registering WordPress actions and filters from various subscriber instances.
 */
class EventManager {

	/**
	 * Add a subscriber and register all it's hooks (subscriptions).
	 *
	 * @param EventSubscriberInterface $subscriber Event Subscriber that can register WordPress actions/filters.
	 * @return void
	 */
	public function addSubscriber( EventSubscriberInterface $subscriber ): void {
		array_map(
			function( EventSubscription $event_subscription ) use ( $subscriber ) {
				add_filter( $event_subscription->getHookName(), [ $subscriber, $event_subscription->getCallback() ], $event_subscription->getPriority(), $event_subscription->getAcceptedArgs() );
			},
			$subscriber->getSubscribedEvents()
		);
	}
}
