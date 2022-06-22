<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Unit\Arguments;

use martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument;
use martinsluters\AsynchronousTemplateData\Arguments\LookupArgumentFactory;
use martinsluters\AsynchronousTemplateData\Arguments\LookupArgument;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;

/**
 * Unit Tests Related to Lookup Arguments Factory
 */
class LookupArgumentFactoryTest extends TestCase {

	/**
	 * LookupArgumentFactory instance - sut.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Arguments\LookupArgumentFactory
	 */
	protected LookupArgumentFactory $lookup_argument_factory_sut;

	/**
	 * Set up SUT.
	 *
	 * @return void
	 */
	public function setUp(): void {
		$this->lookup_argument_factory_sut = new LookupArgumentFactory();
	}

	/**
	 * Make sure the class implements AbstractLookupArgument and is instance of LookupArgument.
	 *
	 * @return void
	 */
	public function testLookupArgumentIsInstanceOfExpectedClasses(): void {
		$json_string = '{"argument_name":"LookupArgument","provider_key":"provider-key-x","wp_post_id":2}';
		$lookup_argument = $this->lookup_argument_factory_sut->createLookupArgumentFromJsonString( $json_string );
		$this->assertInstanceOf( AbstractLookupArgument::class, $lookup_argument );
		$this->assertInstanceOf( LookupArgument::class, $lookup_argument );
	}

	/**
	 * Make sure the lookup argument has expected property values.
	 *
	 * @return void
	 */
	public function testLookupArgumentHasExpectedProperties(): void {
		$json_string = '{"argument_name":"LookupArgument","provider_key":"provider-key-x","wp_post_id":2}';
		$lookup_argument = $this->lookup_argument_factory_sut->createLookupArgumentFromJsonString( $json_string );
		$this->assertSame( 'LookupArgument', $lookup_argument->argument_name );
		$this->assertSame( 'provider-key-x', $lookup_argument->provider_key );
		$this->assertSame( 2, $lookup_argument->wp_post_id );
	}
}
