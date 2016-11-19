<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Renderers\BlankRenderer;

use Voonne\Panels\Panels\BlankPanel\BlankPanel;
use Voonne\Panels\Renderers\Renderer;


class BlankRenderer extends Renderer
{

	/**
	 * @var BlankPanel
	 */
	private $panel;


	public function __construct(BlankPanel $panel)
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
		$this->template->setFile(__DIR__ . '/BlankRenderer.latte');

		$this->template->panel = $this->panel;

		$this->template->render();
	}

}
