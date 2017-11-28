<?php

namespace Voonne\TestPanels\Panels\TablePanel\Controls;

use Codeception\Test\Unit;
use Mockery;
use UnitTester;
use Voonne\Panels\InvalidArgumentException;
use Voonne\Panels\Panels\TablePanel\Controls\Filter;


class FilterTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;


	protected function _after()
	{
		Mockery::close();
	}


	public function testInitialize()
	{
		$filterText = new Filter(Filter::TYPE_TEXT);
		$filterSelect = new Filter(Filter::TYPE_SELECT, [
			'text' => 'text',
			'text1' => 'text1'
		]);

		$this->assertEquals(Filter::TYPE_TEXT, $filterText->getType());
		$this->assertEquals(null, $filterText->getItems());

		$this->assertEquals(Filter::TYPE_SELECT, $filterSelect->getType());
		$this->assertEquals([
			'text' => 'text',
			'text1' => 'text1'
		], $filterSelect->getItems());
	}


	public function testBadType()
	{
		$this->expectException(InvalidArgumentException::class);

		new Filter('BAD_TYPE');
	}

}

