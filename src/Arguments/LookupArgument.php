<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Arguments;

use martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument;

/**
 * Lookup Argument class.
 *
 * @immutable
 */
class LookupArgument extends AbstractLookupArgument {

	/**
	 * Constructor of generic WP Argument accepting WP Post ID.
	 *
	 * @param string  $provider_key Provider key that tells which Provider to use.
	 * @param integer $wp_post_id WordPress Post ID.
	 */
	public function __construct(
		string $provider_key,
		public readonly int $wp_post_id
	) {
		parent::__construct( $provider_key );
	}
}
