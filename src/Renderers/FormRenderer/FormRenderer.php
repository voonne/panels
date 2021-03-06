<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Renderers\FormRenderer;

use Voonne\Panels\Panels\FormPanel\FormPanel;
use Voonne\Panels\Renderers\Renderer;


class FormRenderer extends Renderer
{

	/**
	 * @var FormPanel
	 */
	private $panel;


	public function __construct(FormPanel $panel)
	{
		parent::__construct();

		$this->panel = $panel;
	}


	public function startup()
	{
		parent::startup();

		$this->panel->injectPanel($this->getContainer());

		$this->addComponent($this->panel, 'panel');
	}


	public function render()
	{
		$this->template->setFile(__DIR__ . '/FormRenderer.latte');

		$this->template->panel = $this->panel;

		$this->template->render();
	}

}
