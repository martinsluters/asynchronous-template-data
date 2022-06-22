<?php
declare( strict_types=1 );

namespace martinsluters\AsynchronousTemplateData\Tests\Unit;

use Mockery;
use martinsluters\AsynchronousTemplateData\RequestDataTrait;
use martinsluters\AsynchronousTemplateData\Requests\RequestData;
use martinsluters\AsynchronousTemplateData\Tests\Unit\TestCase;

/**
 * Class testing static dependencies.
 */
class RequestDataTraitTest extends TestCase {

	use RequestDataTrait;

	/**
	 * Make sure Request Data instance can be injected/set.
	 *
	 * @return void
	 */
	public function testRequestDataTraitCanBeInjected(): void {

		$this->setRequestData( Mockery::mock( RequestData::class ) );
		$this->assertInstanceOf( RequestData::class, $this->getRequestData() );
	}
}
