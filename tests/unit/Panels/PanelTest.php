<?php

namespace Voonne\TestPanels\Panels;

use Codeception\Test\Unit;
use Mockery;
use Mockery\MockInterface;
use Nette\Application\UI\ITemplate;
use Nette\Application\UI\ITemplateFactory;
use Nette\Application\UI\Presenter;
use PHPUnit\Framework\TestResult;
use UnitTester;
use Voonne\Panels\InvalidStateException;
use Voonne\Panels\Panels\Panel;
use Voonne\Security\User;


class PanelTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @var MockInterface
	 */
	private $user;

	/**
	 * @var Presenter
	 */
	private $presenter;

	/**
	 * @var ITemplateFactory
	 */
	private $templateFactory;

	/**
	 * @var ITemplate
	 */
	private $template;

	/**
	 * @var Panel
	 */
	private $panel;


	protected function _before()
	{
		$this->user = Mockery::mock(User::class);
		$this->presenter = Mockery::mock(Presenter::class);
		$this->templateFactory = Mockery::mock(ITemplateFactory::class);
		$this->template = Mockery::mock(ITemplate::class);

		$this->presenter->shouldReceive('getParent')
			->andReturn(null);

		$this->presenter->shouldReceive('popGlobalParameters')
			->andReturn([]);

		$this->presenter->shouldReceive('getTemplateFactory')
			->andReturn($this->templateFactory);

		$this->templateFactory->shouldReceive('createTemplate')
			->andReturn($this->template);

		$this->panel = new TestPanel();
		$this->panel->setParent($this->presenter);

		$this->panel->injectPrimary($this->user);
	}


	protected function _after()
	{
		Mockery::mock();
	}


	public function testInitialize()
	{
		$this->assertEquals($this->user, $this->panel->getUser());

		$this->expectException(InvalidStateException::class);

		$this->panel->injectPrimary($this->user);
	}

}


class TestPanel extends Panel
{

}
