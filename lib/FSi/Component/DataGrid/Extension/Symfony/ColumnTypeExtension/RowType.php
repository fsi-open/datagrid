<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Symfony\ColumnTypeExtension;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @deprecated Deprecated since version 1.1, to be removed in 1.2. Use
 *             FSi\Bundle\DataGridBundle\DataGrid\Extension\ColumnTypeExtension\RowType
 *             from datagrid-bundle instead.
 */
class RowType extends AbstractType
{
    /**
     * @var array
     */
    protected $fields;

    /**
     * @param array $fields
     */
    public function __construct($fields = array())
    {
        $this->fields = $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->fields as $field) {
            $builder->add($field['name'], $field['type'], $field['options']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'row';
    }
}
