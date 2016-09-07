<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Renderers\FormPanelRenderer;

use Voonne\Panels\FormPanel;
use Voonne\Panels\Renderers\PanelRenderer;


class FormPanelRenderer extends PanelRenderer
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


	public function beforeRender()
	{
		parent::beforeRender();

		$this->panel->injectPrimary($this->getContentForm());

		$this->addComponent($this->panel, 'panel');
	}


	public function render()
	{
		$this->template->setFile(__DIR__ . '/FormPanelRenderer.latte');

		$this->template->panel = $this->panel;

		$this->template->render();
	}

}
