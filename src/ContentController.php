<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData;

use martinsluters\AsynchronousTemplateData\ProviderManagement\ProviderManager;
use martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument;
use martinsluters\AsynchronousTemplateData\Arguments\LookupArgumentFactory;
use martinsluters\AsynchronousTemplateData\Requests\RequestData;
use martinsluters\AsynchronousTemplateData\TemplateRenderer;
use martinsluters\AsynchronousTemplateData\RequestDataTrait;
use martinsluters\AsynchronousTemplateData\PluginDataTrait;
use martinsluters\AsynchronousTemplateData\PluginData;

/**
 * Class that generates data for a template.
 */
class ContentController {

	use PluginDataTrait;
	use RequestDataTrait;

	/**
	 * Instance of TemplateRenderer.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\TemplateRenderer
	 */
	protected TemplateRenderer $template_renderer;

	/**
	 *  Instance of ProviderManager.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\ProviderManagement\ProviderManager
	 */
	protected ProviderManager $provider_manager;

	/**
	 * Instance of LookupArgumentFactory.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Arguments\LookupArgumentFactory
	 */
	protected LookupArgumentFactory $lookup_argument_factory;

	/**
	 * Constructor of content controller.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\TemplateRenderer                   $template_renderer Template Renderer instance.
	 * @param \martinsluters\AsynchronousTemplateData\PluginData                         $plugin_data Plugin Data instance.
	 * @param \martinsluters\AsynchronousTemplateData\Requests\RequestData               $request_data Request Data instance.
	 * @param \martinsluters\AsynchronousTemplateData\Arguments\LookupArgumentFactory    $lookup_argument_factory Lookup Argument Factory instance.
	 * @param \martinsluters\AsynchronousTemplateData\ProviderManagement\ProviderManager $provider_manager Provider Manager instance.
	 */
	public function __construct(
		TemplateRenderer $template_renderer,
		PluginData $plugin_data,
		RequestData $request_data,
		LookupArgumentFactory $lookup_argument_factory,
		ProviderManager $provider_manager
	) {
		$this->template_renderer = $template_renderer;
		$this->lookup_argument_factory = $lookup_argument_factory;
		$this->provider_manager = $provider_manager;
		$this->setPluginData( $plugin_data );
		$this->setRequestData( $request_data );
	}

	/**
	 * Main method to render a template.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument $lookup_argument Lookup argument instance.
	 * @return void
	 */
	public function showWrapperTemplate( AbstractLookupArgument $lookup_argument ): void {
		$this->template_renderer->render( $this->getPluginData()->getTemplateFilename(), $this->getTemplateData( $lookup_argument ) );
	}

	/**
	 * Method that is used to return data HTML for JS AJAX purposes.
	 *
	 * @return string
	 */
	public function getAsynchronousTemplateData(): string {

		// Fail if invalid request data.
		if ( ! $this->getRequestData()->isValid() ) {
			return $this->getPluginData()->getDataLoadingFailureMessage();
		}

		try {
			$lookup_argument = $this->lookup_argument_factory->createLookupArgumentFromJsonString(
				$this->getRequestData()->getRequestBody()
			);

			return $this->provider_manager
				->getProvider( $lookup_argument->provider_key )
				->getData( $lookup_argument );

		} catch ( \Throwable $th ) {
			return $this->getPluginData()->getDataLoadingFailureMessage();
		}
	}

	/**
	 * Get template data.
	 *
	 * @param \martinsluters\AsynchronousTemplateData\Arguments\AbstractLookupArgument $lookup_argument Lookup argument to find data.
	 * @return array<mixed>
	 */
	protected function getTemplateData( AbstractLookupArgument $lookup_argument ): array {

		$return_data_array = [];

		// Default css class.
		$return_data_array['css_class'] = [
			esc_attr( (string) apply_filters( $this->getPluginData()->getKeyPrefix() . '_template_css_class', 'asynchronous-template-data', $lookup_argument ) ),
		];

		// Noscript tag.
		$noscript_html = sprintf(
			'<noscript>%s</noscript>',
			apply_filters( $this->getPluginData()->getKeyPrefix() . '_template_no_script_information', $this->getPluginData()->getDataLoadingFailureMessage(), $lookup_argument )
		);

		$return_data_array['information'] =
				apply_filters(
					$this->getPluginData()->getKeyPrefix() . '_template_information',
					$noscript_html,
					$lookup_argument
				);

		// Force cast to array after filters applied.
		$return_data_array = (array) apply_filters( $this->getPluginData()->getKeyPrefix() . '_template_data', $return_data_array, $lookup_argument );

		// For security reasons lookup argument is not filterable.
		$return_data_array['argument'] = json_encode( $lookup_argument );

		return $return_data_array;
	}
}
