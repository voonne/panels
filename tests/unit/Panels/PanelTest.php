<?php

namespace Voonne\TestPanels\Panels;

use Codeception\Test\Unit;
use Mockery;
use Mockery\MockInterface;
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
	 * @var Panel
	 */
	private $panel;


	protected function _before()
	{
		$this->user = Mockery::mock(User::class);

		$this->panel = new TestPanel();
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
