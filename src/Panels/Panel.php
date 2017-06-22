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
use Voonne\Forms\Container;
use Voonne\Panels\InvalidStateException;
use Voonne\Security\User;


abstract class Panel extends Control
{

	/**
	 * @var User
	 */
	private $user;


	/**
	 * @param User
	 */
	public function injectPrimary(User $user)
	{
		if($this->user !== null) {
			throw new InvalidStateException('Method ' . __METHOD__ . ' is intended for initialization and should not be called more than once.');
		}

		$this->user = $user;
	}


	/**
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}


	/**
	 * Adjusted ContentFrom for use in a panel.
	 *
	 * @param Container $container
	 */
	public function setupForm(Container $container)
	{

	}

}
