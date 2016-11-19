<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Renderers;

use Nette\DI\Container;
use ReflectionClass;
use Voonne\Panels\NotRegisteredException;
use Voonne\Panels\Panels\Panel;


class RendererManager
{

	const TAG_RENDERER = 'voonne.panel.renderer';

	/**
	 * @var Container
	 */
	private $container;


	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	/**
	 * Returns renderer corresponding to the specified type panel.
	 *
	 * @param Panel $panel
	 *
	 * @return object
	 *
	 * @throws NotRegisteredException
	 */
	public function getRendererFactory(Panel $panel)
	{
		foreach($this->getRendererFactories() as $type => $rendererFactory) {
			if($panel instanceof $type) {
				return $rendererFactory;
			}
		}

		throw new NotRegisteredException("Renderer panel for '" . get_class($panel) . "' not found.");
	}


	/**
	 * Return list of renderer factories.
	 *
	 * @return array
	 */
	private function getRendererFactories()
	{
		$rendererFactories = [];

		foreach($this->container->findByTag(self::TAG_RENDERER) as $name => $attribute) {
			$rendererFactory = $this->container->getService($name);

			$reflectionClass = new ReflectionClass(get_class($rendererFactory));

			$parameters = $reflectionClass->getMethod('create')->getParameters();

			if(isset($parameters[0])) {
				$rendererFactories[$parameters[0]->getClass()->getName()] = $rendererFactory;
			}
		}

		return $rendererFactories;
	}

}
