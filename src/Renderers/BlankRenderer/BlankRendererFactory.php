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


class BlankRendererFactory
{

	/**
	 * @param BlankPanel $panel
	 *
	 * @return BlankRenderer
	 */
	public function create(BlankPanel $panel)
	{
		return new BlankRenderer($panel);
	}

}
