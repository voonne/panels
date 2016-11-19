<?php
/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Renderers;

use Nette\ComponentModel\IComponent;
use Voonne\Controls\Control;
use Voonne\Panels\InvalidStateException;
use Voonne\Panels\Panels\Panel;
use Voonne\Voonne\Content\ContentForm;


abstract class Renderer extends Control
{

	/**
	 * @var ContentForm
	 */
	private $contentForm;


	/**
	 * @return ContentForm
	 */
	public function getContentForm()
	{
		return $this->contentForm;
	}


	/**
	 * @param ContentForm $contentForm
	 */
	public function injectPrimary(ContentForm $contentForm)
	{
		if($this->contentForm !== null) {
			throw new InvalidStateException('Method ' . __METHOD__ . ' is intended for initialization and should not be called more than once.');
		}

		$this->contentForm = $contentForm;
	}


	/**
	 * @param IComponent $component
	 * @param string $name
	 * @param string|null $insertBefore
	 *
	 * @return $this
	 */
	public function addComponent(IComponent $component, $name, $insertBefore = null)
	{
		parent::addComponent($component, $name, $insertBefore);

		if ($component instanceof Panel) {
			// FORM SETUP
			$component->setupForm($this->getContentForm());
		}

		return $this;
	}

}
