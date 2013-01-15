<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Column;

use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\DataMapper\DataMapperInterface;
use FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface;

interface ColumnTypeInterface
{
    /**
     * Get column type identity.
     */
    public function getId();

    /**
     * Get name under column is registred in data grid.
     */
    public function getName();

    /**
     * Filter value before passing it to view.
     * @param mixed $value
     */
    public function filterValue($value);

    /**
     * Create CellView object set source value on it.
     * @param mixed $object
     * @param mixed $index - row index in rowset
     */
    public function createCellView($object, $index);

    /**
     *
     * @param CellViewInterface $view
     */
    public function buildCellView(CellViewInterface $view);

    /**
     * Create HeaderView object for column.
     * @param mixed $name
     */
    public function createHeaderView();

    /**
     * Set DataMapper
     *
     * @param DataMapperInterface $dataMapper
     */
    public function setDataMapper(DataMapperInterface $dataMapper);

    /**
     * Return DataMapper
     */
    public function getDataMapper();

    /**
     * Binds data into object using DataMapper object.
     *
     * @param mixed $data
     * @param mixed $object
     * @param mixed $index
     */
    public function bindData($data, $object, $index);

    public function setOption($name, $value);

    public function getOption($name);

    public function hasOption($name);

    public function setExtensions(array $extensions);

    public function addExtension(ColumnTypeExtensionInterface $extension);

    public function getExtensions();

}