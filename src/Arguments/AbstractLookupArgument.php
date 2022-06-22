<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Arguments;

/**
 * Abstract Lookup Argument class expected to be extended by more concrete Lookup Arguments
 *
 * @immutable
 */
abstract class AbstractLookupArgument {

	/**
	 * Stores this instance's class name without namespace.
	 *
	 * @var string
	 */
	public readonly string $argument_name; // phpcs:ignore

	/**
	 * Constructor of a Lookup Argument.
	 *
	 * @param string $provider_key Instance implementing AbstractLookupArgument.
	 */
	public function __construct( public readonly string $provider_key ) {
		$this->argument_name = ( new \ReflectionClass( $this ) )->getShortName();
	}
}
