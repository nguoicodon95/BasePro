<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class CRUDGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:builder
                            {name : The name of the Crud.}
                            {--fields= : Fields name for the form & migration.}
                            {--fields_from_file= : Fields from a json file.}
                            {--validations= : Validation details for the fields.}
                            {--controller-namespace= : Namespace of the controller.}
                            {--model-namespace= : Namespace of the model inside "app" dir.}
                            {--pk=id : The name of the primary key.}
                            {--pagination=25 : The amount of models per page for index pages.}
                            {--indexes= : The fields to add an index to.}
                            {--foreign-keys= : The foreign keys for the table.}
                            {--relationships= : The relationships for the model.}
                            {--route=yes : Include Crud route to routes.php? yes|no.}
                            {--route-group= : Prefix of the route group.}
                            {--view-path= : The name of the view path.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CRUD Generator builder, auto creating file model, controller and view.';

    /** @var string  */
    protected $routeName = '';
    /** @var string  */
    protected $controller = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        /* -- Model -- */
        /*@Name*/
        $modelName = str_singular($name);
        /*@Namespace*/
        $modelNamespace = ($this->option('model-namespace')) ? trim($this->option('model-namespace')) . '\\' : '';
        /* Migrate name */
        $migrationName = str_plural(snake_case($name));
        /* Table name */
        $tableName = $migrationName;
        /* @route */
        $routeGroup = $this->option('route-group');
        $this->routeName = ($routeGroup) ? $routeGroup . '/' . snake_case($name, '-') : snake_case($name, '-');
        $perPage = intval($this->option('pagination'));
        $controllerNamespace = ($this->option('controller-namespace')) ? $this->option('controller-namespace') . '\\' : '';
        $fields = rtrim($this->option('fields'), ';');
        if ($this->option('fields_from_file')) {
            $fields = $this->processJSONFields($this->option('fields_from_file'));
        }
        $primaryKey = $this->option('pk');
        $viewPath = $this->option('view-path');
        $foreignKeys = $this->option('foreign-keys');
        $fieldsArray = explode(';', $fields);
        $fillableArray = [];
        foreach ($fieldsArray as $item) {
            $spareParts = explode('#', trim($item));
            $fillableArray[] = $spareParts[0];
        }
        $commaSeparetedString = implode("', '", $fillableArray);
        $fillable = "['" . $commaSeparetedString . "']";
        $indexes = $this->option('indexes');
        $relationships = $this->option('relationships');
        $validations = trim($this->option('validations'));
        
        $this->call('crud:controller', [
            'name' => $controllerNamespace . $name . 'Controller',
            '--crud-name' => $name,
            '--model-name' => $modelName,
            '--model-namespace' => $modelNamespace,
            '--view-path' => $viewPath,
            '--route-group' => $routeGroup,
            '--pagination' => $perPage,
            '--fields' => $fields,
            '--validations' => $validations
        ]);

        $this->call('crud:model', [
            'name' => $modelNamespace . $modelName,
            '--fillable' => $fillable,
            '--table' => $tableName,
            '--pk' => $primaryKey,
            '--relationships' => $relationships
        ]);
        $this->call('crud:migration',[
            'name' => $migrationName,
            '--schema' => $fields,
            '--pk' => $primaryKey,
            '--indexes' => $indexes,
            '--foreign-keys' => $foreignKeys
        ]);
        $this->call('crud:view', [
            'name' => $name,
            '--fields' => $fields,
            '--validations' => $validations,
            '--view-path' => $viewPath,
            '--route-group' => $routeGroup,
            '--pk' => $primaryKey
        ]);

        $this->callSilent('optimize');

        $routeFile = base_path('routes/web.php');

        if (file_exists($routeFile) && (strtolower($this->option('route')) === 'yes')) {
            $this->controller = ($controllerNamespace != '') ? $controllerNamespace . '\\' . $name . 'Controller' : $name . 'Controller';
            $isAdded = File::append($routeFile, "\n" . implode("\n", $this->addRoutes()));
            if ($isAdded) {
                $this->info('Crud/Resource route added to ' . $routeFile);
            } else {
                $this->info('Unable to add the route to ' . $routeFile);
            }
        }
        // dd($this->arguments());
        // dd($this->options());
    }

    /**
     * Add routes.
     *
     * @return  array
     */
    protected function addRoutes()
    {
        return ["Route::resource('" . $this->routeName . "', '" . $this->controller . "');"];
    }
    /**
     * Process the JSON Fields.
     *
     * @param  string $file
     *
     * @return string
     */
    protected function processJSONFields($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);
        $fieldsString = '';
        foreach ($fields->fields as $field) {
            if ($field->type == 'select') {
                $fieldsString .= $field->name . '#' . $field->type . '#options=' . implode(',', $field->options) . ';';
            } else {
                $fieldsString .= $field->name . '#' . $field->type . ';';
            }
        }
        $fieldsString = rtrim($fieldsString, ';');
        return $fieldsString;
    }

}
