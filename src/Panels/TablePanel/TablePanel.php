<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels\Panels\TablePanel;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Nette\Utils\Paginator;
use Voonne\Panels\FileNotFoundException;
use Voonne\Panels\InvalidArgumentException;
use Voonne\Panels\InvalidStateException;
use Voonne\Panels\Panels\Panel;
use Voonne\Panels\Panels\TablePanel\Controls\Action;
use Voonne\Panels\Panels\TablePanel\Controls\Column;


abstract class TablePanel extends Panel
{

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * @var QueryBuilder
	 */
	private $queryBuilder;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var int
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
	 * @var array
	 */
	public $onQueryCreate = [];

	/**
	 * @var int
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

	const ORDER_ASC = 'ASC';
	const ORDER_DESC = 'DESC';


	public function injectPanel(EntityManagerInterface $entityManager)
	{
		if($this->entityManager !== null) {
			throw new InvalidStateException('Method ' . __METHOD__ . ' is intended for initialization and should not be called more than once.');
		}

		$this->entityManager = $entityManager;
		$this->queryBuilder = $entityManager->createQueryBuilder();
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


	public function render()
	{
		$this->template->setFile(__DIR__ . '/TablePanel.latte');

		$this->onQueryCreate($this->queryBuilder);

		if (!isset($this->queryBuilder->getDQLPart('from')[0])) {
			throw new InvalidArgumentException('From statement must be declared in QueryBuilder.');
		}

		$paginator = new Paginator();
		$paginator->setItemsPerPage($this->limit);
		$paginator->setPage($this->page);
		$paginator->setItemCount((new \Doctrine\ORM\Tools\Pagination\Paginator($this->queryBuilder))->count());

		$this->queryBuilder->orderBy($this->queryBuilder->getDQLPart('from')[0]->getAlias() . '.' . $this->getSort(), $this->getOrder());
		$this->queryBuilder->setMaxResults($paginator->getItemsPerPage());
		$this->queryBuilder->setFirstResult(($paginator->getItemsPerPage() * $paginator->getPage()) - $paginator->getItemsPerPage());

		$this->template->columns = $this->columns;
		$this->template->actions = $this->actions;
		$this->template->sort = $this->sort;
		$this->template->order = $this->order;
		$this->template->customTemplates = $this->customTemplates;
		$this->template->rows = $this->queryBuilder->getQuery()->getResult();
		$this->template->paginator = $paginator;

		$this->template->render();
	}

}
