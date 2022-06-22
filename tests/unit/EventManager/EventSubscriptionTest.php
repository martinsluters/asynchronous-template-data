<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Unit\EventManager;

use martinsluters\AsynchronousTemplateData\EventManagement\EventSubscription;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;

/**
 * Unit Tests Related to EventSubscription class
 */
class EventSubscriptionTest extends TestCase {

	/**
	 * Make sure that same instance property values are returned when calling properties getter methods
	 * that were passed while constructing EventSubscription instance.
	 *
	 * @return void
	 */
	public function testSamePropertiesValuesReturnedAsWhenCreated(): void {
		$event_subscription = new EventSubscription( 'save_post', 'awesomeSavePostMethod', 10, 1 );
		$this->assertSame( 'save_post', $event_subscription->getHookName() );
		$this->assertSame( 'awesomeSavePostMethod', $event_subscription->getCallback() );
		$this->assertSame( 10, $event_subscription->getPriority() );
		$this->assertSame( 1, $event_subscription->getAcceptedArgs() );
	}

	/**
	 * Make sure that same instance property values are returned when calling properties getter methods
	 * that were passed while constructing EventSubscription instance via static factory method.
	 *
	 * @return void
	 */
	public function testSamePropertiesReturnedAsWhenCreatedUsingShortcutStaticMethod(): void {
		$event_subscription = EventSubscription::create( 'save_post', 'awesomeSavePostMethod', 10, 1 );
		$this->assertSame( 'save_post', $event_subscription->getHookName() );
		$this->assertSame( 'awesomeSavePostMethod', $event_subscription->getCallback() );
		$this->assertSame( 10, $event_subscription->getPriority() );
		$this->assertSame( 1, $event_subscription->getAcceptedArgs() );
	}

	/**
	 * Make sure that the default priority is 10 if omitted on construction
	 *
	 * @return void
	 */
	public function testDefaultPriorityIsTen(): void {
		$event_subscription = new EventSubscription( 'save_post', 'awesomeSavePostMethod' );
		$this->assertSame( 10, $event_subscription->getPriority() );
	}

	/**
	 * Make sure that the default number of accepted args is 1 if omitted on construction
	 *
	 * @return void
	 */
	public function testDefaultAcceptedArgsIsOne(): void {
		$event_subscription = new EventSubscription( 'save_post', 'awesomeSavePostMethod' );
		$this->assertSame( 1, $event_subscription->getAcceptedArgs() );
	}
}
