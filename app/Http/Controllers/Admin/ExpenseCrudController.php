<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExpenseRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Category;

/**
 * Class ExpenseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ExpenseCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Expense::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/expense');
        CRUD::setEntityNameStrings('expense', 'expenses');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addClause('where', 'user_id', backpack_user()->id);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => 'Descripción',
        ]);
        $this->crud->addColumn([
            'name' => 'amount',
            'label' => 'Cantidad',
        ]);
        $this->crud->addColumn([
            'name' => 'category.name',
            'label' => 'Categoría',
        ]);
        $this->crud->addColumn([
            'name' => 'date',
            'label' => 'Fecha',
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->crud->addField([
            'name' => 'description',
            'label' => 'Descripción',
        ]);
        $this->crud->addField([
            'name' => 'amount',
            'label' => 'Cantidad',
            'type' => 'number',
        ]);
        $this->crud->addField([
            'name' => 'category_id',
            'label' => 'Categoría',
            'type' => 'select',
            'entity' => 'category',
            'attribute' => 'name',
            'model' => Category::class,
            'options' => (function ($query) {
                return $query->where('user_id', backpack_user()->id)->get();
            }),
        ]);
        $this->crud->addField([
            'name' => 'date',
            'label' => 'Fecha',
            'type' => 'date',
        ]);
            $this->crud->addField([
            'name' => 'user_id',
            'type' => 'hidden',
            'value' => backpack_user()->id, 
        ]);

        $this->crud->setValidation([
            'description' => 'required',
            'amount' => 'required|numeric',
            'category_id' => 'required',
            'date' => 'required|date',
        ]);

    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
