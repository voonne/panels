<?php

namespace Voonne\TestPanels\Renderers;

use Codeception\Test\Unit;
use Mockery;
use Mockery\MockInterface;
use Nette\DI\Container;
use UnitTester;
use Voonne\Panels\NotRegisteredException;
use Voonne\Panels\Panels\BasicPanel\BasicPanel;
use Voonne\Panels\Renderers\BasicPanelRenderer\BasicRendererFactory;
use Voonne\Panels\Renderers\BlankPanelRenderer\BlankRendererFactory;
use Voonne\Panels\Renderers\RendererManager;


class RendererManagerTest extends Unit
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
	 * @var RendererManager
	 */
	private $rendererManager;


	protected function _before()
	{
		$this->container = Mockery::mock(Container::class);

		$this->rendererManager = new RendererManager($this->container);
	}


	protected function _after()
	{
		Mockery::close();
	}


	public function testGetExistingRendererFactory()
	{
		$basicPanelRendererFactory = Mockery::mock(BasicRendererFactory::class);
		$blankPanelRendererFactory = Mockery::mock(BlankRendererFactory::class);

		$panel = Mockery::mock(BasicPanel::class);

		$this->container->shouldReceive('findByTag')
			->once()
			->with(RendererManager::TAG_RENDERER)
			->andReturn(['voonne.basicPanelRenderer' => true, 'voonne.blankPanelRenderer' => true]);

		$this->container->shouldReceive('getService')
			->once()
			->with('voonne.basicPanelRenderer')
			->andReturn($basicPanelRendererFactory);

		$this->container->shouldReceive('getService')
			->once()
			->with('voonne.blankPanelRenderer')
			->andReturn($blankPanelRendererFactory);

		$this->assertEquals($basicPanelRendererFactory, $this->rendererManager->getRendererFactory($panel));
	}


	public function testGetNonexistentRendererFactory()
	{
		$blankPanelRendererFactory = Mockery::mock(BlankRendererFactory::class);

		$panel = Mockery::mock(BasicPanel::class);

		$this->container->shouldReceive('findByTag')
			->once()
			->with(RendererManager::TAG_RENDERER)
			->andReturn(['voonne.blankPanelRenderer' => true]);

		$this->container->shouldReceive('getService')
			->once()
			->with('voonne.blankPanelRenderer')
			->andReturn($blankPanelRendererFactory);

		$this->expectException(NotRegisteredException::class);
		$this->rendererManager->getRendererFactory($panel);
	}

}
