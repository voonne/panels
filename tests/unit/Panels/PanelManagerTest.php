<?php

namespace Voonne\TestPanels\Panels;

use Codeception\Test\Unit;
use Mockery;
use Nette\Utils\Strings;
use ReflectionClass;
use UnitTester;
use Voonne\Layouts\Layout;
use Voonne\Panels\DuplicateEntryException;
use Voonne\Panels\InvalidArgumentException;
use Voonne\Panels\Panels\BasicPanel\BasicPanel;
use Voonne\Panels\Panels\BlankPanel\BlankPanel;
use Voonne\Panels\Panels\PanelManager;


class PanelManagerTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @var PanelManager
	 */
	private $panelManager;


	protected function _before()
	{
		$this->panelManager = new PanelManager();
	}


	protected function _after()
	{
		Mockery::close();
	}


	public function testAddPanel()
	{
		$panel1 = Mockery::mock(BasicPanel::class);
		$panel2 = Mockery::mock(BlankPanel::class);

		$name1 = Strings::firstLower((new ReflectionClass($panel1))->getShortName());
		$name2 = Strings::firstLower((new ReflectionClass($panel2))->getShortName());

		$this->panelManager->addPanel($panel1, [Layout::POSITION_CENTER]);
		$this->panelManager->addPanel($panel2, [Layout::POSITION_CENTER], 110);

		$this->assertEquals([
			$name2 => $panel2,
			$name1 => $panel1
		], $this->panelManager->getPanels());
	}


	public function testAddPanelDuplicate()
	{
		$panel = Mockery::mock(BasicPanel::class);

		$this->panelManager->addPanel($panel, [Layout::POSITION_CENTER]);

		$this->expectException(DuplicateEntryException::class);
		$this->panelManager->addPanel($panel, [Layout::POSITION_CENTER]);
	}


	public function testAddPanelBadTag()
	{
		$panel = Mockery::mock(BasicPanel::class);

		$this->expectException(InvalidArgumentException::class);
		$this->panelManager->addPanel($panel, ['BAD_TAG']);
	}


	public function testGetByTag()
	{
		$panel1 = Mockery::mock(BasicPanel::class);
		$panel2 = Mockery::mock(BlankPanel::class);

		$name1 = Strings::firstLower((new ReflectionClass($panel1))->getShortName());
		$name2 = Strings::firstLower((new ReflectionClass($panel2))->getShortName());

		$this->panelManager->addPanel($panel1, [Layout::POSITION_CENTER]);
		$this->panelManager->addPanel($panel2, [Layout::POSITION_CENTER, Layout::POSITION_LEFT]);

		$this->assertEquals([
			$name2 => $panel2,
			$name1 => $panel1
		], $this->panelManager->getByTag(Layout::POSITION_CENTER));

		$this->assertEquals([
			$name2 => $panel2
		], $this->panelManager->getByTag(Layout::POSITION_LEFT));
	}

}
