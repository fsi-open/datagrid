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

interface HeaderViewInterface
{
    /**
     * Set view attribute
     *
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value);

    /**
     * Get view attribute
     *
     * @param string $name
     * @return mixed
     */
    public function getAttribute($name);

    /**
     * Check if view attribute exists.
     *
     * @param string $name
     * @return boolean
     */
    public function hasAttribute($name);

    /**
     * Return all view attributes.
     *
     * @return array
     */
    public function getAttributes();

    /**
     * Get view value. In most cases it should be simple string.
     *
     * @return mixed
     */
    public function getLabel();

    /**
     * Set view value.
     *
     * @param mixed $value
     */
    public function setLabel($value);

    /**
     * Get column name.
     *
     * @return string
     */
    public function getName();

    /**
     * Get column type.
     *
     * @return string
     */
    public function getType();
}