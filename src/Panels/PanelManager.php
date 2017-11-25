<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Panels;

use Nette\SmartObject;
use Nette\Utils\Strings;
use ReflectionClass;
use Voonne\Layouts\Layout;
use Voonne\Panels\DuplicateEntryException;
use Voonne\Panels\InvalidArgumentException;


class PanelManager
{

	use SmartObject;


	private $panels = [
		'priorities' => [],
		'tags' => []
	];


	/**
	 * @param Panel $panel
	 * @param array $tags
	 * @param int $priority
	 *
	 * @throws InvalidArgumentException
	 * @throws DuplicateEntryException
	 */
	public function addPanel(Panel $panel, array $tags, $priority = 100)
	{
		foreach ($tags as $tag) {
			if (!in_array($tag, [Layout::POSITION_TOP, Layout::POSITION_BOTTOM, Layout::POSITION_LEFT, Layout::POSITION_RIGHT, Layout::POSITION_CENTER])) {
				throw new InvalidArgumentException("Tag must be '" . Layout::POSITION_TOP . "', '" . Layout::POSITION_BOTTOM . "', '" . Layout::POSITION_LEFT . "', '" . Layout::POSITION_RIGHT . "' or '" . Layout::POSITION_CENTER . "', '"  . $tag . "' given.");
			}
		}

		$name = Strings::firstLower((new ReflectionClass($panel))->getShortName());

		if (isset($this->getPanels()[$name])) {
			throw new DuplicateEntryException("Panel named '" . get_class($panel) . "' is already exists.");
		}

		$this->panels['priorities'][$priority][$name] = $panel;

		foreach ($tags as $tag) {
			$this->panels['tags'][$tag][$priority][$name] = $panel;
		}
	}


	/**
	 * @param Panel $panel
	 */
	public function removePanel(Panel $panel)
	{
		$name = Strings::firstLower((new ReflectionClass($panel))->getShortName());

		foreach ($this->panels['priorities'] as $priorityName => $priority) {
			foreach ($priority as $panelName => $panel) {
				if ($name == $panelName) {
					unset($this->panels['priorities'][$priorityName][$panelName]);
				}
			}
		}

		foreach ($this->panels['tags'] as $tagName => $tags) {
			foreach ($tags as $priorityName => $priority) {
				foreach ($priority as $panelName => $panel) {
					if ($name == $panelName) {
						unset($this->panels['tags'][$tagName][$priorityName][$panelName]);
					}
				}
			}
		}
	}


	/**
	 * @param string $tag
	 *
	 * @return array
	 */
	public function getByTag($tag)
	{
		$panels = [];

		if (isset($this->panels['tags'][$tag])) {
			krsort($this->panels['tags'][$tag]);

			foreach ($this->panels['tags'][$tag] as $priority) {
				foreach ($priority as $name => $panel) {
					$panels[$name] = $panel;
				}
			}
		}

		return $panels;
	}


	/**
	 * @return array
	 */
	public function getPanels()
	{
		$panels = [];

		krsort($this->panels['priorities']);

		foreach ($this->panels['priorities'] as $priority) {
			foreach ($priority as $name => $panel) {
				$panels[$name] = $panel;
			}
		}

		return $panels;
	}

}
