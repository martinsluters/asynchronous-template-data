<?php
declare( strict_types=1 );
namespace martinsluters\AsynchronousTemplateData\Tests\Unit\ContentController;

use \Mockery;
use martinsluters\AsynchronousTemplateData\Providers\DataProviderInterface;
use martinsluters\AsynchronousTemplateData\ProviderManagement\ProviderManager;
use martinsluters\AsynchronousTemplateData\Arguments\LookupArgumentFactory;
use martinsluters\AsynchronousTemplateData\Arguments\LookupArgument;
use martinsluters\AsynchronousTemplateData\Requests\RequestData;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;
use martinsluters\AsynchronousTemplateData\ContentController;
use martinsluters\AsynchronousTemplateData\TemplateRenderer;
use martinsluters\AsynchronousTemplateData\PluginData;

/**
 * Unit Tests Related to ContentController class
 */
abstract class AbstractContentControllerTest extends TestCase {

	/**
	 * ContentController Instance to test - sut.
	 *
	 * @var ContentController
	 */
	protected ContentController $content_controller_sut;

	/**
	 * Stub of PluginData.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\AjaxHandling\PluginData
	 */
	protected PluginData $plugin_data_test_double;

	/**
	 * Immutable LookupArgument instance.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Arguments\LookupArgument
	 */
	protected LookupArgument $lookup_argument;

	/**
	 * Stub of TemplateRenderer.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\AjaxHandling\TemplateRenderer
	 */
	protected TemplateRenderer $template_renderer_test_double;

	/**
	 * Stub of RequestData.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Requests\RequestData
	 */
	protected RequestData $request_data_test_double;

	/**
	 * Stub of LookupArgumentFactory.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Arguments\LookupArgumentFactory
	 */
	protected LookupArgumentFactory $lookup_argument_factory_test_double;

	/**
	 *  Stub of ProviderManager.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\ProviderManagement\ProviderManager
	 */
	protected ProviderManager $provider_manager_test_double;

	/**
	 * Stub of DataProviderInterface.
	 *
	 * @var \martinsluters\AsynchronousTemplateData\Providers\DataProviderInterface
	 */
	protected DataProviderInterface $provider_test_double;

	/**
	 * Create objects against which will test
	 *
	 * @return void
	 */
	public function setUp(): void {
		$this->template_renderer_test_double = Mockery::mock( TemplateRenderer::class );
		$this->plugin_data_test_double = Mockery::mock( PluginData::class );
		$this->lookup_argument = new LookupArgument( 'provider_key_x', 999 );
		$this->request_data_test_double = Mockery::mock( RequestData::class );
		$this->lookup_argument_factory_test_double = Mockery::mock( LookupArgumentFactory::class );
		$this->provider_manager_test_double = Mockery::mock( ProviderManager::class );
		$this->provider_test_double = Mockery::mock( DataProviderInterface::class );
		$this->content_controller_sut = new ContentController(
			$this->template_renderer_test_double,
			$this->plugin_data_test_double,
			$this->request_data_test_double,
			$this->lookup_argument_factory_test_double,
			$this->provider_manager_test_double
		);
	}
}
