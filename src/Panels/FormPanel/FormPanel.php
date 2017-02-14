<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Panels\FormPanel;

use Nette\Forms\Container;
use Nette\Forms\Controls\BaseControl;
use Voonne\Forms\Form;
use Voonne\Panels\InvalidArgumentException;
use Voonne\Panels\InvalidStateException;
use Voonne\Panels\Panels\Panel;
use Voonne\Voonne\Content\ContentForm;


abstract class FormPanel extends Panel
{

	/**
	 * @var string
	 */
	private $title;


	/**
	 * @var Container
	 */
	private $container;


	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}


	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}


	/**
	 * @param Container $container
	 */
	public function injectPrimary(Container $container)
	{
		if($this->container !== null) {
			throw new InvalidStateException('Method ' . __METHOD__ . ' is intended for initialization and should not be called more than once.');
		}

		$this->container = $container;
	}


	public function render()
	{
		$this->template->setFile(__DIR__ . '/FormPanel.latte');

		$this->template->container = $this->container;

		$this->template->render();
	}


	public function getFullName(BaseControl $component)
	{
		$recursive = function($component) use (&$recursive) {
			$result = [];

			if(!($component instanceof Form)) {
				$result[] = $component->name;

				$result = array_merge($recursive($component->getParent()), $result);
			}

			return $result;
		};

		return implode('-', $recursive($component));
	}

}
