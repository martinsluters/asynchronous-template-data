<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData;

use martinsluters\AsynchronousTemplateData\PluginData;

trait PluginDataTrait {
	/**
	 * Plugin data instance.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\PluginData
	 */
	private PluginData $plugin_data; //phpcs:ignore

	/**
	 * Getter of plugin data instance.
	 *
	 * @return \martinsluters\AsynchronousTemplateData\PluginData
	 */
	public function getPluginData(): PluginData {
		return $this->plugin_data;
	}

	/**
	 * Setter of plugin data.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\PluginData $plugin_data PluginData instance.
	 * @return void
	 */
	protected function setPluginData( PluginData $plugin_data ): void {
		$this->plugin_data = $plugin_data;
	}
}
