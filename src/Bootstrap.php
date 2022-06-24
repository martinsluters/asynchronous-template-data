<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData;

use Twig\Environment as TemplateRederingEngine;
use Symfony\Component\HttpFoundation\Request;
use martinsluters\AsynchronousTemplateData\EventManagement\EventSubscriberInterface;
use martinsluters\AsynchronousTemplateData\EventManagement\EventManager;
use martinsluters\AsynchronousTemplateData\ProviderManagement\ProviderManager;
use martinsluters\AsynchronousTemplateData\AjaxHandling\AjaxHandler;
use martinsluters\AsynchronousTemplateData\AjaxHandling\AjaxHandlerInterface;
use martinsluters\AsynchronousTemplateData\Requests\RequestData;
use martinsluters\AsynchronousTemplateData\Requests\RequestDataValidationAjaxStrategy;
use martinsluters\AsynchronousTemplateData\Arguments\LookupArgumentFactory;
use martinsluters\AsynchronousTemplateData\ContentController;
use martinsluters\AsynchronousTemplateData\TemplateRenderer;
use martinsluters\AsynchronousTemplateData\PluginDataTrait;
use martinsluters\AsynchronousTemplateData\PluginData;
use martinsluters\AsynchronousTemplateData\Twig;

/**
 * Bootstrap plugin.
 */
final class Bootstrap {

	use PluginDataTrait;

	/**
	 * Stores one and only true instance of Bootstrap
	 *
	 * @var self
	 */
	private static ?Bootstrap $instance = null;

	/**
	 * Event Manager (WP Plugin API);
	 *
	 * @var EventManager
	 */
	private EventManager $event_manager;

	/**
	 * Ajax request/action handler.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\AjaxHandling\AjaxHandlerInterface
	 */
	private AjaxHandlerInterface $ajax_handler;

	/**
	 * Data content controller.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\ContentController
	 */
	private ContentController $content_controller;

	/**
	 * Template rendering engine.
	 *
	 * @var \Twig\Environment
	 */
	private TemplateRederingEngine $template_rendering_engine;

	/**
	 * Template renderer.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\TemplateRenderer
	 */
	private TemplateRenderer $template_renderer;

	/**
	 * HTTP request data.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Requests\RequestData
	 */
	private RequestData $request_data;

	/**
	 * Static dependencies enqueueing.
	 *
	 * @var StaticDependencies
	 */
	private StaticDependencies $static_dependencies;

	/**
	 * Data Provider Manager;
	 *
	 * @var ProviderManager
	 */
	public ProviderManager $provider_manager;

	/**
	 * Event subscribers collection.
	 *
	 * @return array<int, EventSubscriberInterface>
	 */
	private function getEventSubscribers(): array {
		return [
			$this->ajax_handler,
			$this->static_dependencies,
		];
	}

	/**
	 * Add subscribers to Plugin API event manager.s
	 *
	 * @return void
	 */
	private function manageEventSubscribers(): void {
		$this->event_manager = new EventManager();
		array_map(
			function ( EventSubscriberInterface $event_subscriber ) {
				$this->event_manager->addSubscriber( $event_subscriber );
			},
			$this->getEventSubscribers()
		);
	}

	/**
	 * Disallow constructor.
	 */
	private function __construct() {
	}

	/**
	 * Prevent the instance from being cloned (which would create a second instance of it)
	 *
	 * @return void
	 */
	private function __clone(): void {
	}

	/**
	 * Prevent from being unserialized (which would create a second instance of it).
	 *
	 * @return void
	 * @throws \Exception Throw new Exception.
	 */
	public function __wakeup(): void {
		throw new \Exception( 'Cannot unserialize singleton' );
	}

	/**
	 * Singleton instantiation.
	 *
	 * @return self
	 */
	public static function getInstance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Main bootstrap method.
	 *
	 * @return void
	 * @todo Use container.
	 */
	public function init(): void {
		$this->setPluginData( new PluginData() );
		$this->template_rendering_engine = ( new Twig( $this->getPluginData() ) )->init();

		$this->request_data = new RequestData(
			$this->getPluginData(),
			Request::createFromGlobals(),
			new RequestDataValidationAjaxStrategy()
		);

		$this->template_renderer = new TemplateRenderer(
			$this->getPluginData(),
			$this->template_rendering_engine
		);

		$this->provider_manager = new ProviderManager();

		$this->content_controller = new ContentController(
			$this->template_renderer,
			$this->getPluginData(),
			$this->request_data,
			new LookupArgumentFactory(),
			$this->provider_manager
		);

		$this->ajax_handler = new AjaxHandler( $this->getPluginData(), $this->content_controller );
		$this->static_dependencies = new StaticDependencies( $this->getPluginData() );

		$this->manageEventSubscribers();
	}

	/**
	 * Getter of content controller.
	 *
	 * @return ContentController
	 * @throws \Exception Is thrown if getContentController is called before plugin is initialized.
	 */
	public function getContentController(): ContentController {
		try {
			/**
			 * Return content_controller property.
			 *
			 * @throws \Error if accessed before initialized
			 */
			return $this->content_controller;
		} catch ( \Error $th ) {
			throw new \Exception( 'Content controller can\'t be accessed before Bootstrap::init is called.' );
		}
	}

	/**
	 * Tear down function for Unit Testing singleton.
	 *
	 * @return void
	 */
	public static function tearDown(): void {
		static::$instance = null;
	}
}
