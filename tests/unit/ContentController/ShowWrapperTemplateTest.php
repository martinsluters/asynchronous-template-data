<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Unit\ContentController;

use martinsluters\AsynchronousTemplateData\Tests\Unit\ContentController\AbstractContentControllerTest;

/**
 * Unit Tests Related to ContentController class, method showWrapperTemplate.
 */
class ShowTemplateDataTemplateTest extends AbstractContentControllerTest {

	/**
	 * Default expected data array passed to renderer.
	 *
	 * @var array
	 */
	private function getExpectedRenderMethodsArgumentsArray(): array {
		return [
			'TemplateData.twig',
			[
				'css_class' => [ 'asynchronous-template-data' ],
				'information' => '<noscript>Fast Delivery</noscript>',
				'argument' => json_encode( $this->lookup_argument ),
			],
		];
	}

	/**
	 * Set PluginData getTemplateFilename expectation.
	 *
	 * @return void
	 */
	protected function expectGetTemplateFilenameCalled(): void {
		$this->plugin_data_test_double->shouldReceive( 'getTemplateFilename' )
		->times( 1 )
		->andReturn( 'TemplateData.twig' );
	}

	/**
	 * Set PluginData getKeyPrefix expectation.
	 *
	 * @return void
	 */
	protected function expectGetKeyPrefixCalled(): void {
		$this->plugin_data_test_double->shouldReceive( 'getKeyPrefix' )
		->atLeast()
		->times( 1 )
		->andReturn( 'ml_asynchronous_template_data' );
	}

	/**
	 * Set PluginData getDataLoadingFailureMessage expectation.
	 *
	 * @return void
	 */
	protected function expectgetDataLoadingFailureMessageCalled(): void {
		$this->plugin_data_test_double->shouldReceive( 'getDataLoadingFailureMessage' )
		->times( 1 )
		->andReturn( 'Fast Delivery' );
	}

	/**
	 * Set Template Renderer received data expectation.
	 *
	 * @param array $expected_render_methods_arguments_array Array containing expected render method's arguments.
	 * @return void
	 */
	protected function expectTemplateRendererToReceive( array $expected_render_methods_arguments_array ): void {
		$this->template_renderer_test_double
		->shouldReceive( 'render' )
		->times( 1 )
		->with( ...$expected_render_methods_arguments_array );
	}

	/**
	 * Make sure that expected template data array gets passed to template renderer render method.
	 *
	 * @return void
	 */
	public function testExpectedDataArrayPassedToTemplateRenderer(): void {
		$this->expectTemplateRendererToReceive( $this->getExpectedRenderMethodsArgumentsArray() );
		$this->expectGetTemplateFilenameCalled();
		$this->expectgetDataLoadingFailureMessageCalled();
		$this->expectGetKeyPrefixCalled();

		$this->content_controller_sut->showWrapperTemplate( $this->lookup_argument );
	}

	/**
	 * Make sure that expected template data array gets passed to template renderer render method.
	 *
	 * @return void
	 */
	public function testReturnDefaultFailureMessageIfRequestInvalid(): void {
		$this->expectTemplateRendererToReceive( $this->getExpectedRenderMethodsArgumentsArray() );
		$this->expectGetTemplateFilenameCalled();
		$this->expectgetDataLoadingFailureMessageCalled();
		$this->expectGetKeyPrefixCalled();

		$this->content_controller_sut->showWrapperTemplate( $this->lookup_argument );
	}
}
