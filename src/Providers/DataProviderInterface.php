<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Providers;

use martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument;

interface DataProviderInterface {

	/**
	 * Returns data.
	 *
	 * @param AbstractLookupArgument $argument Instance implementing AbstractLookupArgument.
	 * @return string
	 */
	public function getData( AbstractLookupArgument $argument ): string;
}
