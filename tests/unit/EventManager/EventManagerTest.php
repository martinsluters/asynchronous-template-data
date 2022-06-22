<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Unit\EventManager;

use Mockery;
use WP_Mock;
use martinsluters\AsynchronousTemplateData\EventManagement\EventSubscriberInterface;
use martinsluters\AsynchronousTemplateData\EventManagement\EventSubscription;
use martinsluters\AsynchronousTemplateData\EventManagement\EventManager;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;

/**
 * Unit Tests Related to EventManager class.
 */
class EventManagerTest extends TestCase {

	/**
	 * EventManager Instance to test.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\EventManagement\EventManager
	 */
	private EventManager $event_manager_sut;

	/**
	 * Create object which will test against.
	 *
	 * @return void
	 */
	public function setUp(): void {
		$this->event_manager_sut = new EventManager();
	}

	/**
	 * Make sure that hooks are added/subscribed to the Plugin API.
	 *
	 * @return void
	 */
	public function testActionsAndFiltersAreSubscribed(): void {

		$event_subscription1 = Mockery::mock( EventSubscription::class );
		$event_subscription2 = Mockery::mock( EventSubscription::class );

		$event_subscription1->shouldReceive( 'getHookName' )
			->andReturn( 'save_post' );
		$event_subscription1->shouldReceive( 'getCallback' )
			->andReturn( 'awesomeSavePostMethod' );
		$event_subscription1->shouldReceive( 'getPriority' )
			->andReturn( 10 );
		$event_subscription1->shouldReceive( 'getAcceptedArgs' )
			->andReturn( 1 );

		$event_subscription2->shouldReceive( 'getHookName' )
			->andReturn( 'comment_edit_redirect' );
		$event_subscription2->shouldReceive( 'getCallback' )
			->andReturn( 'awesomeCommentRedirectMethod' );
		$event_subscription2->shouldReceive( 'getPriority' )
			->andReturn( 5 );
		$event_subscription2->shouldReceive( 'getAcceptedArgs' )
			->andReturn( 2 );

		$subscriber = Mockery::mock( EventSubscriberInterface::class );
		$subscriber->shouldReceive( 'getSubscribedEvents' )
			->andReturn( [ $event_subscription1, $event_subscription2 ] );

		WP_Mock::expectFilterAdded( 'save_post', [ $subscriber, 'awesomeSavePostMethod' ], 10, 1 );
		WP_Mock::expectFilterAdded( 'comment_edit_redirect', [ $subscriber, 'awesomeCommentRedirectMethod' ], 5, 2 );

		$this->event_manager_sut->addSubscriber( $subscriber );
	}
}
