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


class FormPanelRendererFactory
{

	/**
	 * @param FormPanel $panel
	 *
	 * @return FormPanelRenderer
	 */
	public function create(FormPanel $panel)
	{
		return new FormPanelRenderer($panel);
	}

}
