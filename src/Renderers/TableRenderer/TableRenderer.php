<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Renderers\TableRenderer;

use Doctrine\ORM\EntityManagerInterface;
use Voonne\Panels\Panels\TablePanel\TablePanel;
use Voonne\Panels\Renderers\Renderer;


class TableRenderer extends Renderer
{

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * @var TablePanel
	 */
	private $panel;


	public function __construct(EntityManagerInterface $entityManager, TablePanel $panel)
	{
		parent::__construct();

		$this->entityManager = $entityManager;
		$this->panel = $panel;
	}


	public function startup()
	{
		parent::startup();

		$this->panel->injectPanel($this->entityManager);

		$this->addComponent($this->panel, 'panel');
	}


	public function render()
	{
		$this->template->setFile(__DIR__ . '/TableRenderer.latte');

		$this->template->panel = $this->panel;

		$this->template->render();
	}

}
