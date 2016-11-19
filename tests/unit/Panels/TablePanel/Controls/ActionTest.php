<?php

namespace Voonne\TestPanels\Panels\TablePanel\Controls;

use Codeception\Test\Unit;
use Mockery;
use stdClass;
use UnitTester;
use Voonne\Panels\Panels\TablePanel\Controls\Action;


class ActionTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @var Action
	 */
	private $action;


	protected function _before()
	{
		$this->action = new Action('name', 'Label', function ($row) {
			return 'http://example.com';
		});
	}


	protected function _after()
	{
		Mockery::close();
	}


	public function testInitialize()
	{
		$this->assertEquals('name', $this->action->getName());
		$this->assertEquals('Label', $this->action->getLabel());
		$this->assertEquals('http://example.com', $this->action->getLink(new stdClass()));
	}

}
