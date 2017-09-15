<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Panels\FormPanel;

use Nette\Forms\Controls\BaseControl;
use Voonne\Forms\Container;
use Voonne\Forms\Controls\SubmitButton;
use Voonne\Forms\Form;
use Voonne\Panels\InvalidStateException;
use Voonne\Panels\Panels\Panel;


abstract class FormPanel extends Panel
{

	/**
	 * @var Container
	 */
	private $container;


	/**
	 * @var string
	 */
	private $title;


	/**
	 * @param Container $container
	 */
	public function injectPanel(Container $container)
	{
		if($this->container !== null) {
			throw new InvalidStateException('Method ' . __METHOD__ . ' is intended for initialization and should not be called more than once.');
		}

		$this->container = $container;
	}


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


	public function render()
	{
		$this->template->setFile(__DIR__ . '/FormPanel.latte');

		$this->template->container = $this->container;

		$this->template->render();
	}


	public function getButtons($components)
	{
		$result = [];

		foreach ($components as $component) {
			if ($component instanceof SubmitButton) {
				$result[] = $component;
			}
		}

		return $result;
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
