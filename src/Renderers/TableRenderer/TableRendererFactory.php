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


class TableRendererFactory
{

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;


	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}


	/**
	 * @param TablePanel $panel
	 *
	 * @return TableRenderer
	 */
	public function create(TablePanel $panel)
	{
		return new TableRenderer($this->entityManager, $panel);
	}

}
