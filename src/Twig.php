<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use martinsluters\AsynchronousTemplateData\PluginDataTrait;
use martinsluters\AsynchronousTemplateData\PluginData;

/**
 * Class that creates template rendering engine.
 */
final class Twig {

	use PluginDataTrait;

	/**
	 * Instance that stores the Twig configuration and renders templates.
	 *
	 * @var \Twig\Environment|null
	 */
	private ?Environment $twig = null;

	/**
	 * Constructor.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\PluginData $plugin_data Plugin Data instance.
	 */
	public function __construct( PluginData $plugin_data ) {
		$this->setPluginData( $plugin_data );
	}

	/**
	 * Initialize Twig instance.
	 *
	 * @return Environment
	 */
	public function init(): Environment {

		if ( $this->twig instanceof Environment ) {
			return $this->twig;
		}

		$template_paths = [ $this->getPluginData()->getTemplatesDirPath() ];

		// Look first in theme's template directory maybe.
		if ( file_exists( $this->getPluginData()->getThemeTemplatesDirPath() ) ) {
			array_unshift( $template_paths, $this->getPluginData()->getThemeTemplatesDirPath() );
		}

		$loader = new FilesystemLoader( $template_paths );
		$this->twig = new Environment(
			$loader,
			[
				// 'cache' => PluginData::templates_cache_dir(),
			]
		);

		return $this->twig;
	}
}
