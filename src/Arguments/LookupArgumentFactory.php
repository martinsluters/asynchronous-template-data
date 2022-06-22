<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Arguments;

use CuyZ\Valinor;
use martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument;

/**
 * Simple factory class of Lookup Argument.
 */
class LookupArgumentFactory {

	/**
	 * Create Lookup Argument by a JSON string.
	 *
	 * @param string $json_string JSON string to be used to create a Lookup Argument.
	 * @return \martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument
	 *
	 * @throws \Exception In case something could not create object.
	 * @throws \DomainException In case JSON string's argument_name property value does not a represent a
	 * valid class under martinsluters\AsynchronousTemplateData\Arguments namespace.
	 */
	public function createLookupArgumentFromJsonString( string $json_string ): AbstractLookupArgument {
		return ( new Valinor\MapperBuilder() )
				->infer(
					AbstractLookupArgument::class,
					function( string $argument_name ): string {
						$class_name = 'martinsluters\AsynchronousTemplateData\Arguments\\' . $argument_name;
						if ( class_exists( $class_name ) ) {
							return $class_name;
						} else {
							throw new \DomainException( "Unhandled Lookup Argument '$argument_name'." );
						}
					}
				)
				->mapper()
				->map(
					AbstractLookupArgument::class,
					new Valinor\Mapper\Source\JsonSource( $json_string )
				);
	}
}
