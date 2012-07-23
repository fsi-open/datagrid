<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Table\Extension\Symfony;

use FSi\Component\Table\TableAbstractExtension;
use FSi\Component\Table\Extension\Symfony\EventListener;
use FSi\Component\Table\Extension\Symfony\ColumnTypeExtension;
use Symfony\Component\Form\FormFactory;

class SymfonyExtension extends TableAbstractExtension
{
    protected $formFactory;

    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }
    
    protected function loadListeners()
    {
        return array(
            new EventListener\BindRequest()
        );
    }

    protected function loadColumnTypesExtensions()
    {
        return array(
            new ColumnTypeExtension\FormExtension($this->formFactory)
        );
    }
}
