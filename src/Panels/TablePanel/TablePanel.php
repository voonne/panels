<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Panels\TablePanel;

use DateTime;
use Nette\Utils\Paginator;
use Nette\Utils\Strings;
use ReflectionClass;
use Voonne\Forms\Container;
use Voonne\Panels\FileNotFoundException;
use Voonne\Panels\InvalidArgumentException;
use Voonne\Panels\Panels\Panel;
use Voonne\Panels\Panels\TablePanel\Adapters\IAdapter;
use Voonne\Panels\Panels\TablePanel\Controls\Action;
use Voonne\Panels\Panels\TablePanel\Controls\Column;
use Voonne\Panels\Panels\TablePanel\Controls\Filter;


abstract class TablePanel extends Panel
{

	/**
	 * @var IAdapter
	 */
	private $adapter;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var integer
	 */
	private $limit = 50;

	/**
	 * @var string
	 */
	private $defaultSort = 'id';

	/**
	 * @var string
	 */
	private $defaultOrder = 'ASC';

	/**
	 * @var array
	 */
	private $columns = [];

	/**
	 * @var array
	 */
	private $actions = [];

	/**
	 * @var array
	 */
	private $customTemplates = [];

	/**
	 * @var callable|null
	 */
	private $primaryAction;

	/**
	 * @var integer
	 * @persistent
	 */
	public $page = 1;

	/**
	 * @var string
	 * @persistent
	 */
	public $sort;

	/**
	 * @var string
	 * @persistent
	 */
	public $order = self::ORDER_ASC;

	/**
	 * @var string
	 * @persistent
	 */
	public $filters;

	const ORDER_ASC = 'ASC';
	const ORDER_DESC = 'DESC';


	/**
	 * @param IAdapter $adapter
	 */
	public function setAdapter(IAdapter $adapter)
	{
		$this->adapter = $adapter;

		$this->adapter->setLimit($this->limit);
		$this->adapter->setOffset(($this->limit * $this->page) - $this->limit);
	}


	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}


	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}


	/**
	 * @return integer
	 */
	public function getLimit()
	{
		return $this->limit;
	}


	/**
	 * @param integer $limit
	 */
	public function setLimit($limit)
	{
		$this->limit = $limit;

		if ($this->adapter instanceof IAdapter) {
			$this->adapter->setLimit($this->limit);
			$this->adapter->setOffset(($this->limit * $this->page) - $this->limit);
		}
	}


	/**
	 * @param string $name
	 * @param string $label
	 *
	 * @return Column
	 */
	public function addColumn($name, $label)
	{
		$column = new Column($name, $label);

		return $this->columns[$name] = $column;
	}


	/**
	 * @param string $name
	 * @param string $label
	 * @param callable $linkCallback
	 *
	 * @return Action
	 */
	public function addAction($name, $label, callable $linkCallback)
	{
		$action = new Action($name, $label, $linkCallback);

		return $this->actions[$name] = $action;
	}


	/**
	 * @param string $template
	 *
	 * @return $this
	 */
	public function addTemplate($template)
	{
		if(!file_exists($template)) {
			throw new FileNotFoundException('Template file ' . $template . ' doesn\'t exists.');
		}

		$this->customTemplates[] = $template;

		return $this;
	}


	/**
	 * @param string $row
	 *
	 * @return array
	 */
	public function getActions($row)
	{
		$actions = [];

		foreach ($this->actions as $action) {
			/** @var Action $action */
			if (!empty($action->getLink($row))) {
				$actions[] = $action;
			}
		}

		return $actions;
	}


	/**
	 * @param string $sort
	 */
	public function setDefaultSort($sort)
	{
		$this->defaultSort = $sort;
	}


	/**
	 * @param string $order
	 */
	public function setDefaultOrder($order)
	{
		if(in_array(strtoupper($order), [self::ORDER_ASC, self::ORDER_DESC])) {
			$this->defaultOrder = strtoupper($order);
		} else {
			throw new InvalidArgumentException('Argument order must have a value ' . self::ORDER_ASC . ' or ' . self::ORDER_DESC . '.');
		}
	}


	/**
	 * @param callable $callback
	 */
	public function setPrimaryAction(callable $callback)
	{
		$this->primaryAction = $callback;
	}


	/**
	 * @return string
	 */
	private function getSort()
	{
		if(!in_array($this->order, [self::ORDER_ASC, self::ORDER_DESC])) {
			$this->order = self::ORDER_ASC;
		}

		return (empty($this->sort) && $this->order == self::ORDER_ASC) ? $this->defaultSort : $this->sort;
	}


	/**
	 * @return string
	 */
	private function getOrder()
	{
		return (empty($this->sort) && $this->order == self::ORDER_ASC) ? $this->defaultOrder : $this->order;
	}


	/**
	 * @return boolean
	 */
	public function hasFilters()
	{
		foreach ($this->columns as $column) {
			/** @var Column $column */

			if ($column->getFilter()) {
				return true;
			}
		}

		return false;
	}


	/**
	 * @return boolean
	 */
	public function hasActions()
	{
		return !empty($this->actions);
	}


	/**
	 * @return array
	 */
	private function getFilters()
	{
		$filters = unserialize($this->filters);

		if ($filters) {
			foreach ($filters as $name => $filter) {
				switch ($this->columns[$name]->getFilter()->getType()) {
					case Filter::TYPE_TEXT:
						$filters[$name] = $filter;

						break;
					case Filter::TYPE_SELECT:
						$filters[$name] = $filter;

						break;
					case Filter::TYPE_DATETIME:
						$filters[$name] = [
							'from' => !empty($filters[$name]['from']) ? (new DateTime())->setTimestamp($filters[$name]['from']) : null,
							'to' => !empty($filters[$name]['to']) ? (new DateTime())->setTimestamp($filters[$name]['to']) : null
						];

						break;
				}
			}

			return $filters;
		} else {
			return [];
		}
	}


	public function render()
	{
		$this->template->setFile(__DIR__ . '/TablePanel.latte');

		$filters = $this->getFilters();

		$paginator = new Paginator();
		$paginator->setItemsPerPage($this->limit);
		$paginator->setPage($this->page);
		$paginator->setItemCount($this->adapter->getCount($filters));

		$this->template->columns = $this->columns;
		$this->template->actions = $this->actions;
		$this->template->sort = $this->sort;
		$this->template->order = $this->order;
		$this->template->customTemplates = $this->customTemplates;
		$this->template->primaryAction = $this->primaryAction;
		$this->template->rows = $this->adapter->getResults($filters, $this->getSort(), $this->getOrder());
		$this->template->paginator = $paginator;
		$this->template->container = Strings::firstLower((new ReflectionClass($this))->getShortName());

		$this->template->render();
	}


	public function setupForm(Container $container)
	{
		$filter = $this->getFilters();

		foreach ($this->columns as $column) {
			/** @var Column $column */

			if ($column->getFilter()) {
				switch ($column->getFilter()->getType()) {
					case Filter::TYPE_TEXT:
						$container->addText($column->getName())
							->setDefaultValue(isset($filter[$column->getName()]) ? $filter[$column->getName()] : null);

						break;
					case Filter::TYPE_SELECT:
						$container->addSelect($column->getName(), null, $column->getFilter()->getItems())
							->setPrompt('')
							->setDefaultValue(isset($filter[$column->getName()]) ? $filter[$column->getName()] : null);

						break;
					case Filter::TYPE_DATETIME:
						$dateContainer = $container->addContainer($column->getName());

						$dateContainer->addDateTimePicker('from')
							->setAttribute('placeholder', 'Od')
						->setDefaultValue(isset($filter[$column->getName()]) ? $filter[$column->getName()]['from'] : null);

						$dateContainer->addDateTimePicker('to')
							->setAttribute('placeholder', 'Do')
							->setDefaultValue(isset($filter[$column->getName()]) ? $filter[$column->getName()]['to'] : null);

						break;
				}
			}
		}

		$container->addSubmit('submit', 'Hledat');

		$container->onSuccess[] = [$this, 'success'];
	}


	public function success(Container $container, $values)
	{
		$filters = [];

		foreach ($this->columns as $column) {
			/** @var Column $column */

			if ($column->getFilter() && !empty($values->{$column->getName()})) {
				$filter = $column->getFilter();

				switch ($filter->getType()) {
					case Filter::TYPE_TEXT:
						$filters[$column->getName()] = $values->{$column->getName()};

						break;
					case Filter::TYPE_SELECT:
						$filters[$column->getName()] = $values->{$column->getName()};

						break;
					case Filter::TYPE_DATETIME:
						$filters[$column->getName()] = [
							'from' => !empty($values->{$column->getName()}->from) ? $values->{$column->getName()}->from->getTimestamp() : null,
							'to' => !empty($values->{$column->getName()}->to) ? $values->{$column->getName()}->to->getTimestamp() : null
						];

						break;
				}
			}
		}

		if(!empty($filters)) {
			$this->filters = serialize($filters);
		} else {
			$this->filters = null;
		}

		$this->redirect('this');
	}

}
