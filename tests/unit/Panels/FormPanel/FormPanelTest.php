<?php

namespace Voonne\TestPanels\Panels\FormPanel;

use Codeception\Test\Unit;
use Mockery;
use Mockery\MockInterface;
use UnitTester;
use Voonne\Forms\Container;
use Voonne\Panels\InvalidStateException;
use Voonne\Panels\Panels\FormPanel\FormPanel;


class FormPanelTest extends Unit
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
	 * @var FormPanel
	 */
	private $formPanel;


	protected function _before()
	{
		$this->container = Mockery::mock(Container::class);

		$this->formPanel = new TestFormPanel();
		$this->formPanel->injectPanel($this->container);
	}


	protected function _after()
	{
		Mockery::close();
	}


	public function testInitialize()
	{
		$this->expectException(InvalidStateException::class);

		$this->formPanel->injectPanel($this->container);
	}


	public function testSetTitle()
	{
		$this->assertNull($this->formPanel->getTitle());

		$this->formPanel->setTitle('title');

		$this->assertEquals('title', $this->formPanel->getTitle());
	}

}


class TestFormPanel extends FormPanel
{

}
