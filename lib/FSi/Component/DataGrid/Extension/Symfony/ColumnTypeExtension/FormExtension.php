<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Symfony\ColumnTypeExtension;

use Doctrine\Common\Util\ClassUtils;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @deprecated Deprecated since version 1.1, to be removed in 1.2. Use
 *             FSi\Bundle\DataGridBundle\DataGrid\Extension\ColumnTypeExtension\FormExtension
 *             from datagrid-bundle instead.
 */
class FormExtension extends ColumnAbstractTypeExtension
{
    /**
     * @var string
     */
    protected $formName;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * Form Objects instances created by method CreateForm.
     *
     * @var array
     */
    protected $forms = array();

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     */
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

        $formData = array();
        switch ($column->getId()) {
            case 'entity':
                $relationField = $column->getOption('relation_field');
                if (!isset($data[$relationField])) {
                    return;
                }

                $formData[$relationField] = $data[$relationField];
                break;

            default:
                $fieldMapping = $column->getOption('field_mapping');
                foreach ($fieldMapping as $field) {
                    if (!isset($data[$field])) {
                        return;
                    }

                    $formData[$field] = $data[$field];
                }
        }

        /** @var FormInterface $form */
        $form = $this->createForm($column, $index, $object);
        $form->submit(array($index => $formData));
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
            'boolean',
            'number',
            'datetime',
            'entity',
            'gedmo_tree',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setDefaults(array(
            'editable' => false,
            'form_options' => array(),
            'form_type' => array(),
        ));

        $column->getOptionsResolver()->setAllowedTypes('editable', 'bool');
        $column->getOptionsResolver()->setAllowedTypes('form_options', 'array');
        $column->getOptionsResolver()->setAllowedTypes('form_type', 'array');
    }

    /**
     * Create Form Objects for column and rowset index.
     *
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param mixed $index
     * @param mixed $data
     * @return FormInterface
     */
    private function createForm(ColumnTypeInterface $column, $index, $data)
    {
        $formId = implode(array($column->getName(),$column->getId(), $index));
        if (array_key_exists($formId, $this->forms)) {
            return $this->forms[$formId];
        }

        //Create fields array. There are column types like entity where field_mapping
        //should not be used to build field array.
        $fields = array();
        switch ($column->getId()) {
            case 'entity':
                $field = array(
                    'name' => $column->getOption('relation_field'),
                    'type' => 'entity',
                    'options' => array(),
                );

                $fields[$column->getOption('relation_field')] = $field;
                break;

            default:
                foreach ($column->getOption('field_mapping') as $fieldName) {
                    $field = array(
                        'name' => $fieldName,
                        'type' => null,
                        'options' => array(),
                    );
                    $fields[$fieldName] = $field;
                }
        }

        //Pass fields form options from column into $fields array.
        $fieldsOptions = $column->getOption('form_options');
        foreach ($fieldsOptions as $fieldName => $fieldOptions) {
            if (array_key_exists($fieldName, $fields)) {
                if (is_array($fieldOptions)) {
                    $fields[$fieldName]['options'] = $fieldOptions;
                }
            }
        }

        //Pass fields form type from column into $fields array.
        $fieldsTypes = $column->getOption('form_type');
        foreach ($fieldsTypes as $fieldName => $fieldType) {
            if (array_key_exists($fieldName, $fields)) {
                if (is_string($fieldType)) {
                    $fields[$fieldName]['type'] = $fieldType;
                }
            }
        }

        //Build data array, the data array holds data that should be passed into
        //form elements.
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
                }
                break;
        }

        $formBuilderOptions = array(
            'type' => new RowType($fields),
            'csrf_protection' => false,

        );

        if (null !== $data) {
            $formBuilderOptions['options'] = array(
                'data_class' => ClassUtils::getRealClass(get_class($data))
            );
        }

        //Create form builder.
        $formBuilder = $this->formFactory->createNamedBuilder(
            $this->formName,
            'collection',
            array($index => $data),
            $formBuilderOptions
        );

        //Create Form.
        $this->forms[$formId] = $formBuilder->getForm();

        return $this->forms[$formId];
    }
}
