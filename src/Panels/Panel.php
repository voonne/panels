<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Panels;

use Voonne\Controls\Control;
use Voonne\Forms\Form;


abstract class Panel extends Control
{

	/**
	 * Adjusted ContentFrom for use in a panel.
	 *
	 * @param Form $form
	 */
	public function setupForm(Form $form)
	{

	}

}
