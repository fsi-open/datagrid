<?php

namespace FSi\Component\DataGrid\Extension\Symfony\ColumnTypeExtension;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
