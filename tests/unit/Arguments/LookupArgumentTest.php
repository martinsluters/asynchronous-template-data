<?php
declare( strict_types=1 );
namespace martinsluters\AsynchronousTemplateData\Tests\Unit\Arguments;

use martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument;
use martinsluters\AsynchronousTemplateData\Arguments\LookupArgument;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;

/**
 * Unit Tests Related to LookupArgument class
 */
class LookupArgumentTest extends TestCase {

	/**
	 * LookupArgument instance - sut.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Arguments\LookupArgument
	 */
	protected LookupArgument $lookup_argument_sut;

	/**
	 * Set up SUT.
	 *
	 * @return void
	 */
	public function setUp(): void {
		$this->lookup_argument_sut = new LookupArgument( 'provider-key-x', 999 );
	}

	/**
	 * Make sure the class extends AbstractLookupArgument.
	 *
	 * @return void
	 */
	public function testIsInstanceOfAbstractLookupArgumentClass(): void {
		$this->assertInstanceOf( AbstractLookupArgument::class, $this->lookup_argument_sut );
	}

	/**
	 * Make sure the class implements AbstractLookupArgument.
	 *
	 * @return void
	 */
	public function testToJsonReturnsExpectedJsonString(): void {
		$this->assertSame(
			json_encode(
				[
					'argument_name' => 'LookupArgument',
					'provider_key' => 'provider-key-x',
					'wp_post_id' => 999,
				]
			),
			json_encode( $this->lookup_argument_sut )
		);
	}

	/**
	 * Make sure it is possible to access provider key property.
	 *
	 * @return void
	 */
	public function testCanAccessProviderKeyProperty(): void {
		$this->assertSame(
			'provider-key-x',
			$this->lookup_argument_sut->provider_key
		);
	}

	/**
	 * Make sure that instance property argument_name contains a short name of class
	 * i.e. class name without namespace.
	 *
	 * @return void
	 */
	public function testInstanceContainsArgumentNamePropertyContainingShortClassName(): void {
		$this->assertSame(
			'LookupArgument',
			$this->lookup_argument_sut->argument_name
		);
	}

	/**
	 * Make sure all properties of instance are readonly - immutable.
	 *
	 * @return void
	 */
	public function testInstanceContainsReadOnlyProperties(): void {
		$available_instance_properties = ( new \ReflectionClass( LookupArgument::class ) )
			->getProperties();

		array_map(
			function( \ReflectionProperty $instance_property ) {
				$this->assertTrue(
					$instance_property->isReadOnly(),
					'Failed asserting that instance property ' . $instance_property->getName() . ' is readonly.'
				);
			},
			$available_instance_properties
		);
	}

	/**
	 * Make sure all properties of instance are public.
	 *
	 * @return void
	 */
	public function testInstanceContainsPublicOnlyProperties(): void {
		$available_instance_properties = ( new \ReflectionClass( LookupArgument::class ) )
			->getProperties();

		array_map(
			function( \ReflectionProperty $instance_property ) {
				$this->assertTrue(
					$instance_property->isPublic(),
					'Failed asserting that instance property ' . $instance_property->getName() . ' is public.'
				);
			},
			$available_instance_properties
		);
	}

}
