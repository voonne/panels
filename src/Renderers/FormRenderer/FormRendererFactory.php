<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Renderers\FormPanelRenderer;

use Voonne\Panels\Panels\FormPanel\FormPanel;


class FormRendererFactory
{

	/**
	 * @param FormPanel $panel
	 *
	 * @return FormRenderer
	 */
	public function create(FormPanel $panel)
	{
		return new FormRenderer($panel);
	}

}
