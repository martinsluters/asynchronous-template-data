<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Unit;

use WP_Mock;

/**
 * Abstract base class of true unit tests.
 */
abstract class TestCase extends \Codeception\Test\Unit {

	use \FalseyAssertEqualsDetector\Test;

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		WP_Mock::setUp();
		parent::setUp();
	}

	/**
	 * Clean up for tests.
	 *
	 * @return void
	 */
	public function tearDown(): void {
		WP_Mock::tearDown();
		parent::tearDown();
	}
}
