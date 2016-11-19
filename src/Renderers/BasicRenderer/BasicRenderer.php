<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Renderers\BasicPanelRenderer;

use Voonne\Panels\Panels\BasicPanel\BasicPanel;
use Voonne\Panels\Renderers\Renderer;


class BasicRenderer extends Renderer
{

	/**
	 * @var BasicPanel
	 */
	private $panel;


	public function __construct(BasicPanel $panel)
	{
		parent::__construct();

		$this->panel = $panel;
	}


	public function beforeRender()
	{
		parent::beforeRender();

		$this->addComponent($this->panel, 'panel');
	}


	public function render()
	{
		$this->template->setFile(__DIR__ . '/BasicRenderer.latte');

		$this->template->panel = $this->panel;

		$this->template->render();
	}

}
