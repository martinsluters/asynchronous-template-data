<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\EventManagement;

/**
 * Event subscription in WordPress actions/filters context.
 */
class EventSubscription {

	/**
	 * Hook name.
	 *
	 * @var string
	 */
	private string $hook_name;

	/**
	 * Callback
	 *
	 * @var string
	 */
	private string $callback;

	/**
	 * Priority
	 *
	 * @var int
	 */
	private int $priority;

	/**
	 * Accepted Args
	 *
	 * @var int
	 */
	private int $accepted_args;

	/**
	 * Constructor of Event Subscription
	 *
	 * @param string $hook_name Hook name.
	 * @param string $callback Callback.
	 * @param int    $priority Priority.
	 * @param int    $accepted_args Accepted Args.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_filter/
	 * @see https://developer.wordpress.org/reference/functions/add_action/
	 */
	public function __construct( string $hook_name, string $callback, int $priority = 10, int $accepted_args = 1 ) {
		$this->hook_name = $hook_name;
		$this->callback = $callback;
		$this->priority = $priority;
		$this->accepted_args = $accepted_args;
	}

	/**
	 * Getter of Hook Name
	 *
	 * @return string Hook name.
	 */
	public function getHookName(): string {
		return $this->hook_name;
	}

	/**
	 * Getter of Callback
	 *
	 * @return string Callback.
	 */
	public function getCallback(): string {
		return $this->callback;
	}

	/**
	 * Getter of Priority
	 *
	 * @return int Callback.
	 */
	public function getPriority(): int {
		return $this->priority;
	}

	/**
	 * Getter of Accepted Args
	 *
	 * @return int Accepted Args.
	 */
	public function getAcceptedArgs(): int {
		return $this->accepted_args;
	}

	/**
	 * Shortcut of construction of Event Subscription.
	 *
	 * @param string|int ...$params Hook name, callback, priority and accepted args.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_filter/
	 * @see https://developer.wordpress.org/reference/functions/add_action/
	 *
	 * @return self
	 */
	public static function create( string|int ...$params ): self {
		return new self( ...$params );
	}
}
