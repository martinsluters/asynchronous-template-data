<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Unit;

use martinsluters\AsynchronousTemplateData\Bootstrap;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;

/**
 * Unit Tests Related to Bootstrap class
 */
class BootstrapTest extends TestCase {

	/**
	 * The Bootstrap class needs to exist
	 *
	 * @return void
	 */
	public function testBootstrapClassExists(): void {
		$this->assertTrue( class_exists( Bootstrap::class ) );
	}

	/**
	 * Makes sure that by calling a static method getInstance returns an instance of Bootstrap
	 *
	 * @return void
	 */
	public function testAttemptToGetInstanceMustReturnInstance(): void {
		$this->assertInstanceOf( Bootstrap::class, Bootstrap::getInstance() );
	}

	/**
	 * Makes sure that by calling a static method getInstance twice returns the same instance of Bootstrap
	 *
	 * @return void
	 */
	public function testAttemptToGetInstanceTwiceMustReturnSameInstance(): void {
		$this->assertSame( Bootstrap::getInstance(), Bootstrap::getInstance() );
	}

	/**
	 * Make sure that we can null static instance variable.
	 * Method Bootstrap::tearDown is useful to test singleton Bootstrap class.
	 *
	 * @return void
	 */
	public function testTearDownSetsStaticInstanceToNull() {
		$instance = Bootstrap::getInstance();
		$this->assertSame( Bootstrap::getInstance(), $instance );

		$instance->tearDown();
		$this->assertNotSame( Bootstrap::getInstance(), $instance );

	}
}
