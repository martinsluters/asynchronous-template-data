<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Wpunit;

use martinsluters\AsynchronousTemplateData\Bootstrap;
use martinsluters\AsynchronousTemplateData\PluginData;

/**
 * Class testing static dependencies.
 */
class StaticDependenciesTest extends \Codeception\TestCase\WPTestCase {

	use \FalseyAssertEqualsDetector\Test;

	/**
	 * Make sure the main frontend (WP) JS file is enqueued.
	 *
	 * @return void
	 */
	public function testJSFileIsEnqueued(): void {
		Bootstrap::getInstance()->init();
		do_action( 'wp_enqueue_scripts' ); // phpcs:ignore
		$this->assertTrue( wp_script_is( ( new PluginData() )->getPluginTextDomain() . '-js' ) );
	}
}
