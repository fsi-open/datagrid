<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Symfony\ColumnTypeExtension;

use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use Symfony\Component\Form\FormFactoryInterface;

class FormExtension extends ColumnAbstractTypeExtension
{
    /**
     * @var string
     */
    protected $formName;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * Form Objects instances created by method CreateForm.
     * @var array
     */
    protected $forms = array();

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataGrid(DataGridInterface $dataGrid)
    {
        $this->formName = $dataGrid->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function bindData(ColumnTypeInterface $column, $data, $object, $index)
    {

        if ($column->getOption('editable') === false) {
            return;
        }

        // Sometimes mapping_fields dont match the data indexes, like in entity
        // column so we need to do special check.
        switch ($column->getId()) {
            case 'entity':
                $relationField = $column->getOption('relation_field');
                if (!array_key_exists($relationField, $data)) {
                    return;
                }
                break;
            default:
                $mapping_fields = $column->getOption('mapping_fields');
                if (count(array_diff($mapping_fields, array_keys($data)))) {
                     return;
                }
            break;
        }

        $form = $this->createForm($column, $index, $object);
        $form->bind(array($index => $data));
        if ($form->isValid()) {
            $data = $form->getData();
            foreach ($data as $index => $fields) {
                foreach ($fields as $field => $value) {
                    $column->getDataMapper()->setData($field, $object, $value);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view)
    {
        if (!$column->getOption('editable')) {
            return;
        }

        $data = $view->getSource();
        $index = $view->getAttribute('row');
        $form = $this->createForm($column, $index, $data);

        $view->setAttribute('form', $form->createView());
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedColumnTypes()
    {
        return array(
            'text',
            'number',
            'datetime',
            'entity',
            'gedmo.tree'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptionsValues(ColumnTypeInterface $column)
    {
        return array('editable' => false, 'fields_options' => array());
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions(ColumnTypeInterface $column)
    {
        return array('editable');
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableOptions(ColumnTypeInterface $column)
    {
        return array('editable', 'fields_options');
    }

    /**
     * Create Form Objects for column and rowset index.
     *
     * @param ColumnTypeInterface $column
     * @param mixed $index
     * @param mixed $data
     */
    private function createForm(ColumnTypeInterface $column, $index, $data)
    {
        $formId = implode(array($column->getName(),$column->getId(), $index));
        if (array_key_exists($formId, $this->forms)) {
            return $this->forms[$formId];
        }

        // Create fields array. There are column types like entity where mapping_fields
        // should not be used to build field array.
        $fields = array();
        switch ($column->getId()) {
            case 'entity':
                    $field = array(
                        'name' => $column->getOption('relation_field'),
                        'type' => 'entity',
                        'options' => array()
                    );

                    $fields[$column->getOption('relation_field')] = $field;
                break;
            default:
                foreach ($column->getOption('mapping_fields') as $fieldName) {
                    $field = array(
                        'name' => $fieldName,
                        'type' => null,
                        'options' => array()
                    );
                    $fields[$fieldName] = $field;
                }
            break;
        }

        // Pass fields options from column into $fields array.
        $fieldsOptions = $column->getOption('fields_options');
        foreach ($fieldsOptions as $fieldNameOptions => $fieldOptions) {
            if (array_key_exists($fieldNameOptions, $fields)) {
                if (is_array($fieldOptions)) {
                    if (array_key_exists('type', $fieldOptions)) {
                        $fields[$fieldNameOptions]['type'] = $fieldOptions['type'];
                    }
                    if (array_key_exists('options', $fieldOptions)) {
                        if (is_array($fieldOptions['options'])) {
                            $fields[$fieldNameOptions]['options'] = $fieldOptions['options'];
                        }
                    }
                }
            }
        }

        // Build data array, the data array holds data that should be passed into
        // form elements
        $dataArray = array();
        switch ($column->getId()) {
            case 'datetime':
                foreach ($fields as &$field) {
                    $value = $column->getDataMapper()->getData($field['name'], $data);
                    if (!isset($field['type'])) {
                        $field['type'] = 'datetime';
                    }
                    if (is_numeric($value) && !isset($field['options']['input'])) {
                        $field['options']['input'] = 'timestamp';
                    }
                    if (is_string($value) && !isset($field['options']['input'])) {
                        $field['options']['input'] = 'string';
                    }
                    if (($value instanceof \DateTime) && !isset($field['options']['input'])) {
                        $field['options']['input'] = 'datetime';
                    }
                    $dataArray[$field['name']] = $value;
                }
                break;
            case 'entity':
                    $value = $column->getDataMapper()->getData($column->getOption('relation_field'), $data);
                    $dataArray[$column->getOption('relation_field')] = $value;
                break;
            default:
                foreach ($fields as &$field) {
                    $value = $column->getDataMapper()->getData($field['name'], $data);
                    $dataArray[$field['name']] = $value;
                }
                break;
        }

        // Create form builder
        $formBuilder = $this->formFactory->createNamedBuilder(
            $this->formName,
            'collection',
            array(
                $index => $dataArray
            ),
            array(
                'type' => new RowType($fields),
                'csrf_protection' => false
            )
        );

        // Create Form
        $this->forms[$formId] = $formBuilder->getForm();

        return $this->forms[$formId];
    }
}