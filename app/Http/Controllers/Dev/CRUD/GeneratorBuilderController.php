<?php

namespace App\Http\Controllers\Dev\CRUD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Artisan;
use DB;
use Schema;

class GeneratorBuilderController extends Controller
{
    public function builder () {
        return view('dev.crud.builder');
    }

    public function fieldTemplate () {
        $tables = [];
        foreach (DB::select('SHOW TABLES') as $table) {
            foreach ($table as $value)
                $tables[] = $value;
        }
        return view('dev.crud.field-template', compact('tables'));
    }

    public function _columns_in_table (Request $request) {
        $columns = Schema::getColumnListing($request->table);
        return view('dev.crud.column', compact('columns'));
    }

    public function generate (Request $request) {
        $name = $request->crudName;
        $modelName = $request->modelName;
        $controllerName = $request->controllerName;
        $viewPath = $request->viewPath;
        $routeGroup = $request->routeGroup;
        // dd($request->all());
        $fields = $request->fields;
        $fields_string = ''; $fieldValidate = ''; $fieldForeign_keys = '';
        foreach ($fields as $field) {
            $fieldInput = explode( ':', $field['fieldInput'] );
            $fieldName = strtolower($fieldInput[0]);
            $fieldType = strtolower($fieldInput[1]);
            $fields_string .= $fieldName.'#'.$fieldType.'; ';
            /*Validate*/
            $validates = trim(str_replace(';', '|', $field['validations']));
            $fieldValidate .= $fieldName.'#'.$validates.'; ';
            /*Foreignkey*/
            $foreign_keys = explode( ':', $field['foreign_keys'] );
            $table = $foreign_keys[0];
            $column = $foreign_keys[1];
            if($column != 'undefined')
                $fieldForeign_keys .= $fieldName.'#'.$column.'#'.$table.'#cascade; ';
        }
        if(\Request::ajax()) {
            Artisan::call('crud:builder', [
                'name' => $name,
                '--fields' => rtrim($fields_string, '; '),
                '--view-path' => $viewPath,
                '--controller-namespace' => $controllerName,
                '--model-namespace' => $modelName,
                '--route-group' => $routeGroup,
                '--validations' => rtrim($fieldValidate, '; '),
                '--foreign-keys' => rtrim($fieldForeign_keys, '; ')
            ]);
            return response()->json('Thành công', 200);
        }
        return null;
    }
}
