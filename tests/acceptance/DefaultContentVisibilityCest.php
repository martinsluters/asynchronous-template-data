<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Acceptance;

/**
 * Class to test template data appearing on desired location after async load.
 *
 * @see tests/client-mu-plugin - a client that is responsible for implementing the test case.
 */
class DefaulContentVisibilityCest {

	/**
	 * Make sure that asynchronously loaded template data appears successfully.
	 *
	 * @param \AcceptanceTester $i AcceptanceTester actor instance.
	 * @return void
	 */
	public function asynchronousTemplateDataVisibilityTest( \AcceptanceTester $i ) {
		$i->wantTo( 'See data appear successfully' );
		$i->loginAsAdmin();
		$i->amOnPluginsPage();
		$i->activatePlugin( 'asynchronous-template-data' );
		$i->activatePlugin( 'woocommerce' );
		$i->amOnAdminPage( 'post-new.php?post_type=product' );
		$i->fillField( '#title', 'Test Product' );
		$i->click( 'Publish' );
		$i->click( 'View product' );
		$i->see( 'Loading data' );
		$i->wait( 3 );
		$i->see( 'Lightning Fast Delivery!' );
		$i->see( 'Blazing Fast Delivery!' );
	}
}
