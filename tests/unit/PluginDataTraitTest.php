<?php
declare( strict_types=1 );
namespace martinsluters\AsynchronousTemplateData\Tests\Unit;

use Mockery;
use martinsluters\AsynchronousTemplateData\PluginData;
use martinsluters\AsynchronousTemplateData\PluginDataTrait;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;

/**
 * Class testing static dependencies.
 */
class PluginDataTraitTest extends TestCase {

	use PluginDataTrait;

	/**
	 * Make sure plugin data instance is set.
	 *
	 * @return void
	 */
	public function testPluginDataCanBeInjected(): void {

		$this->setPluginData( Mockery::mock( PluginData::class ) );
		$this->assertInstanceOf( PluginData::class, $this->getPluginData() );
	}
}
