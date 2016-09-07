<?php

namespace Voonne\TestPanels;

use Codeception\Test\Unit;
use Mockery;
use UnitTester;
use Voonne\Panels\BasicPanel;


class BasicPanelTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @var BasicPanel
	 */
	private $basicPanel;


	protected function _before()
	{
		$this->basicPanel = new TestBasicPanel();
	}


	protected function _after()
	{
		Mockery::close();
	}


	public function testSetTitle()
	{
		$this->assertNull($this->basicPanel->getTitle());

		$this->basicPanel->setTitle('title');

		$this->assertEquals('title', $this->basicPanel->getTitle());
	}

}


class TestBasicPanel extends BasicPanel
{

}
