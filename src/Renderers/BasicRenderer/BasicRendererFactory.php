<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Renderers\BasicRenderer;

use Voonne\Panels\Panels\BasicPanel\BasicPanel;


class BasicRendererFactory
{

	/**
	 * @param BasicPanel $panel
	 *
	 * @return BasicRenderer
	 */
	public function create(BasicPanel $panel)
	{
		return new BasicRenderer($panel);
	}

}
