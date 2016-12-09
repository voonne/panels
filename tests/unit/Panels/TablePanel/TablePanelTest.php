<?php

namespace Voonne\TestPanels\Panels\TablePanel;

use Codeception\Test\Unit;
use Mockery;
use UnitTester;
use Voonne\Panels\Panels\TablePanel\TablePanel;


class TablePanelTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @var TablePanel
	 */
	private $tablePanel;


	protected function _before()
	{
		$this->tablePanel = new TestTablePanel();
	}


	protected function _after()
	{
		Mockery::close();
	}


	public function testSetTitle()
	{
		$this->assertNull($this->tablePanel->getTitle());

		$this->tablePanel->setTitle('title');

		$this->assertEquals('title', $this->tablePanel->getTitle());
	}


	public function testSetLimit()
	{
		$this->assertEquals(50, $this->tablePanel->getLimit());

		$this->tablePanel->setLimit(100);

		$this->assertEquals(100, $this->tablePanel->getLimit());
	}

}

class TestTablePanel extends TablePanel
{

}
