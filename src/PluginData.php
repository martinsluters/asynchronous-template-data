<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData;

/**
 * The basic information about the plugin, like version and file locations.
 */
class PluginData {

	/**
	 * Get this plugin's version.
	 *
	 * @return string
	 * @todo Should match composer.json's version
	 */
	public function getPluginVersion(): string {
		return '0.1';
	}

	/**
	 * Get this plugin's required minimum version of PHP.
	 *
	 * @link https://wordpress.org/about/requirements/
	 * @link https://en.wikipedia.org/wiki/PHP#Release_history
	 *
	 * @return string
	 * @todo Should match composer.json's `"require": { "php":...`
	 */
	public function getMinPHPVersion(): string {
		return '8.1';
	}

	/**
	 * Get this plugin's directory path, relative to this file's location.
	 *
	 * @return string
	 */
	public function getPluginDirPath(): string {
		return trailingslashit( realpath( __DIR__ . DIRECTORY_SEPARATOR . '..' ) );
	}

	/**
	 * Get this plugin's template directory.
	 *
	 * @return string
	 */
	public function getTemplatesDirPath(): string {
		$templates_dir = 'templates/';
		return $this->getPluginDirPath() . $templates_dir;
	}

	/**
	 * Get this plugin's template directory path withing a theme.
	 *
	 * @return string
	 */
	public function getThemeTemplatesDirPath(): string {
		$templates_dir = $this->getPluginTextDomain() . '-templates/';
		return trailingslashit( get_stylesheet_directory() ) . $templates_dir;
	}

	/**
	 *  Template filename.
	 *
	 * @return string
	 */
	public function getTemplateFilename(): string {
		return 'asynchronousTemplateData.twig';
	}

	/**
	 * Get this plugin's text domain.
	 *
	 * @return string
	 */
	public function getPluginTextDomain(): string {
		return 'asynchronous-template-data';
	}

	/**
	 * Get the base URL of ui directory.
	 *
	 * @return string
	 */
	public function getAssetsDirUrl(): string {
		return $this->getPluginDirUrl() . 'ui/dist/';
	}

	/**
	 * Get this plugin's directory URL.
	 *
	 * Example: https://example.com/wp-content/plugins/plugin-name/
	 *
	 * @return string
	 */
	public function getPluginDirUrl(): string {
		return plugin_dir_url( $this->getMainPluginFile() );
	}

	/**
	 * WP action name that captures Ajax request.
	 *
	 * @return string
	 */
	public function getAjaxActionName(): string {
		return $this->getKeyPrefix() . '_ajax_action';
	}

	/**
	 * HTTP request parameter name to be used for
	 * WP nonce transportation.
	 *
	 * @return string
	 */
	public function getSecurityNonceRequestParameterName(): string {
		return 'security';
	}

	/**
	 * Get nonce action name to be used for security checks
	 * when retrieving data via an HTTP request.
	 * Used to create a WP nonce and validate a nonce.
	 *
	 * @return string
	 */
	public function getSecurityNonceActionName(): string {
		return $this->getKeyPrefix() . '_retrieve_information';
	}

	/**
	 * Get prefix used for various string based keys.
	 *
	 * @return string  Underscore separated string with lowercase letters only.
	 */
	public function getKeyPrefix(): string {
		return 'ml_asynchronous_template_data';
	}

	/**
	 * Object property name to store a provider identification key.
	 * Useful to check when decoding a json string to see if
	 * a provider identification property exists.
	 *
	 * @return string
	 */
	public function getProviderIdentificationPropertyName(): string {
		return 'provider_key';
	}

	/**
	 * Default data loading error message.
	 * Will show up by default in various places in case of failure
	 * retrieving information.
	 *
	 * @return string
	 */
	public function getDataLoadingFailureMessage(): string {
		return apply_filters(
			$this->getKeyPrefix() . '_loading_information_failure_message',
			_x( 'Failed to load data', 'loading data failure', $this->getPluginTextDomain() )
		);
	}

	/**
	 * Default data loading message.
	 *
	 * @return string
	 */
	public function getDataLoadingMessage(): string {
		return apply_filters(
			$this->getKeyPrefix() . '_loading_information_message',
			_x( 'Loading data', 'loading data', $this->getPluginTextDomain() )
		);
	}

	/**
	 * Get this plugin's main plugin file.
	 *
	 * @return string
	 */
	public function getMainPluginFile(): string {
		return $this->getPluginDirPath() . $this->getPluginTextDomain() . '.php';
	}
}
