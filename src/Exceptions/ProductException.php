<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Exceptions;

use \Throwable;

/**
 * Class ProductException
 */
class ProductException extends \OutOfBoundsException {

	/**
	 * Representation of a product in question.
	 *
	 * @var mixed
	 */
	private mixed $product = '';

	/**
	 * Construct exception.
	 *
	 * @param mixed          $product Product in any type.
	 * @param string         $message Message of of exception.
	 * @param integer        $code Exception code.
	 * @param Throwable|null $previous Previous Throwable.
	 */
	public function __construct( mixed $product = '', string $message = '', int $code = 0, ?Throwable $previous = null ) {
		$this->product = $product;
		parent::__construct( $message, $code, $previous );
	}

	/**
	 * Getter of a product representation property.
	 *
	 * @return mixed
	 */
	final public function getProduct(): mixed {
		return $this->product;
	}
}
