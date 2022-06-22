<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Acceptance;

/**
 * Class to test plugin activation.
 *
 * @see tests/client-mu-plugin - a client that is responsible for implementing the test case.
 */
class PluginActivationCest {

	/**
	 * Test plugin can be activated.
	 *
	 * @param \AcceptanceTester $i AcceptanceTester actor instance.
	 * @return void
	 */
	public function activatePluginSuccessfullyTest( \AcceptanceTester $i ) {
		$i->wantTo( 'activate plugin successfully' );
		$i->loginAsAdmin();
		$i->amOnPluginsPage();
		$i->activatePlugin( 'asynchronous-template-data' );
		$i->see( 'Selected plugins activated.' );
	}
}
