<?php

namespace Voonne\TestPanels\Panels\TablePanel\Controls
{

	use Codeception\Test\Unit;
	use Mockery;
	use stdClass;
	use UnitTester;
	use Voonne\Panels\InvalidStateException;
	use Voonne\Panels\Panels\TablePanel\Controls\Column;


	class ColumnTest extends Unit
	{

		/**
		 * @var UnitTester
		 */
		protected $tester;

		/**
		 * @var Column
		 */
		private $column;

		public static $functions;


		protected function _before()
		{
			$this->column = new Column('name', 'Label');
			self::$functions = Mockery::mock();
		}


		protected function _after()
		{
			Mockery::close();
		}


		public function testInitialize()
		{
			$this->assertEquals('name', $this->column->getName());
			$this->assertEquals('Label', $this->column->getLabel());
		}


		public function testSorting()
		{
			$this->assertTrue($this->column->isSortable());
			$this->column->disableSorting();
			$this->assertFalse($this->column->isSortable());
			$this->column->enableSorting();
			$this->assertTrue($this->column->isSortable());
		}


		public function testTemplate()
		{
			$this->assertNull($this->column->getTemplate());
			$this->column->setTemplate('templateName');
			$this->assertEquals('templateName', $this->column->getTemplate());
		}


		public function testGetContent()
		{
			$row = Mockery::mock(stdClass::class);

			$row->shouldReceive('getName')
				->once()
				->withNoArgs()
				->andReturn('content');

			self::$functions->shouldReceive('getName')
				->once()
				->withNoArgs()
				->andReturn(true);

			self::$functions->shouldReceive('getName')
				->once()
				->withNoArgs()
				->andReturn(false);

			$this->assertEquals('content', $this->column->getContent($row));

			$this->expectException(InvalidStateException::class);
			$this->column->getContent($row);
		}

	}
}

namespace Voonne\Panels\Panels\TablePanel\Controls
{

	use Voonne\TestPanels\Panels\TablePanel\Controls\ColumnTest;


	function method_exists()
	{
		return ColumnTest::$functions->getName();
	}
}
