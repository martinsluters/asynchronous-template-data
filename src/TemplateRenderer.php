<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData;

use Twig;
use martinsluters\AsynchronousTemplateData\PluginDataTrait;
use martinsluters\AsynchronousTemplateData\PluginData;

/**
 * A class responsible of rendering template.
 */
class TemplateRenderer {

	use PluginDataTrait;

	/**
	 * Rendering Engine.
	 *
	 * @var Twig\Environment
	 */
	protected Twig\Environment $rendering_engine;

	/**
	 * Constructor.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\PluginData $plugin_data PluginData instance.
	 * @param Twig\Environment                                   $rendering_engine Twig rendering engine.
	 * @todo make rendering engine agnostic.
	 */
	public function __construct( PluginData $plugin_data, Twig\Environment $rendering_engine ) {
		$this->rendering_engine = $rendering_engine;
		$this->setPluginData( $plugin_data );
	}

	/**
	 * Main render method that displays template rendering engine's
	 * rendered content.
	 *
	 * @param string       $template_name Name of the template php file.
	 * @param array<mixed> $data  Data passed to template.
	 * @return void
	 */
	public function render( string $template_name, array $data ): void {

		do_action( $this->getPluginData()->getKeyPrefix() . '_pre_template_render', $template_name, $data );

		if ( $this->rendering_engine->getLoader()->exists( $template_name ) ) {
			$this->rendering_engine
				->display(
					$template_name,
					$data
				);
		}

		do_action( $this->getPluginData()->getKeyPrefix() . '_after_template_render', $template_name, $data );
	}
}
