<?php

namespace Voonne\TestPanels\Renderers;

use Codeception\Test\Unit;
use Mockery;
use Mockery\MockInterface;
use UnitTester;
use Voonne\Forms\Container;
use Voonne\Panels\InvalidStateException;
use Voonne\Panels\Renderers\Renderer;


class RendererTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @var MockInterface
	 */
	private $container;

	/**
	 * @var Renderer
	 */
	private $renderer;


	protected function _before()
	{
		$this->container = Mockery::mock(Container::class);

		$this->renderer = new TestRenderer();
		$this->renderer->injectPrimary($this->container);
	}


	protected function _after()
	{
		Mockery::mock();
	}


	public function testInitialize()
	{
		$this->assertEquals($this->container, $this->renderer->getContainer());

		$this->expectException(InvalidStateException::class);

		$this->renderer->injectPrimary($this->container);
	}

}


class TestRenderer extends Renderer
{

}
