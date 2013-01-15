<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Symfony;

use FSi\Component\DataGrid\DataGridAbstractExtension;
use FSi\Component\DataGrid\Extension\Symfony\ColumnTypeExtension;
use Symfony\Component\Form\FormFactoryInterface;

class FormExtension extends DataGridAbstractExtension
{
    /**
     * FormFactory used by extension to build forms.
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @param FormFactory $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypesExtensions()
    {
        return array(
            new ColumnTypeExtension\FormExtension($this->formFactory)
        );
    }
}
