<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Exceptions;

use \Throwable;

/**
 * Class ProviderException
 */
class ProviderException extends \OutOfBoundsException {

	/**
	 * Representation of a provider in question.
	 *
	 * @var mixed
	 */
	private mixed $provider = '';

	/**
	 * Construct exception.
	 *
	 * @param mixed          $provider Provider in any type.
	 * @param string         $message Message of of exception.
	 * @param integer        $code Exception code.
	 * @param Throwable|null $previous Previous Throwable.
	 */
	public function __construct( mixed $provider = '', string $message = '', int $code = 0, ?Throwable $previous = null ) {
		$this->provider = $provider;
		parent::__construct( $message, $code, $previous );
	}

	/**
	 * Getter of a provider representation property.
	 *
	 * @return mixed
	 */
	final public function getProvider(): mixed {
		return $this->provider;
	}
}
