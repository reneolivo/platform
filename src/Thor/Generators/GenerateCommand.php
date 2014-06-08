<?php

namespace Thor\Generators;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GenerateCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'thor:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates basic CRUD files for a resource: migration, model, controller and backend views.';

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
    public function fire()
    {
        $singular = strtolower(trim($this->argument('resource')));
        $fields = strtolower(trim($this->argument('fields'), ', '));
        $transFields = strtolower(trim($this->argument('transfields'), ', '));

        if(empty($singular)) {
            return $this->error('thor:generate error: Resource (singular) name cannot be empty');
        }
        
        if($this->option('force') or $this->confirm('Do you wish to create the required classes and views for ' . $singular . '? [yes|no]')) {
            $fields = $this->parseFields($fields);
            $transFields = $this->parseFields($transFields);

            \CRUD::createResourceFiles($singular, $fields, $transFields, $this->option('pageable'), $this->option('imageable'));

            if($this->option('create-permissions')) {
                \CRUD::createPermissions($singular);
                $this->info("Entrust CRUD permissions created successfully. Remember to add them to some role(s).\n");
            }

            $this->line('');
            $this->info("CRUD Generation succeed. Add this line somewhere in your routes.php file:");
            $this->line("\n\CRUD::createResourceRoutes('{$singular}');\n");

            $this->line("Remember to execute 'php artisan migrate' too.\n");
        } else {
            $this->line('Cancelled');
        }
    }

    protected function parseFields($fieldsStr)
    {
        if(empty($fieldsStr) or ($fieldsStr==false)) {
            return array();
        }
        $fields = explode(',', $fieldsStr);
        $fieldsAssoc = array();
        foreach($fields as $k => $v) {
            $v = explode(':', $v, 3);
            if(count($v) < 2) {
                return $this->error('thor:generate error: Wrong fields format');
            }
            if(count($v) == 2) {
                $v[] = 'text';
            }
            $fieldsAssoc[$v[1]] = $v;
        }
        return $fieldsAssoc;
    }

    /**
     * Get the console cconsoleommand arguments.
     *
     * @return array
     */
    protected function getArguments()
    {

        return array(
            array('resource', InputArgument::REQUIRED, 'Resource (singular) name.'),
            array('fields', InputArgument::REQUIRED, 'Comma-separated list of blueprintFn:fieldName:inputType,blueprintFn:fieldName:inputType'),
            array('transfields', InputArgument::OPTIONAL, 'Comma-separated list of blueprintFn:fieldName:inputType,blueprintFn:fieldName:inputType', false)
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('force', 'f', InputOption::VALUE_NONE, 'Forces the command without confirmation.'),
            array('pageable', 'p', InputOption::VALUE_NONE, 'Generates a pageable resource (with _texts table and common pageable fields).'),
            array('imageable', 'i', InputOption::VALUE_NONE, 'Generates an imageable resource.'),
            array('create-permissions', 'c', InputOption::VALUE_NONE, 'Creates CRUD permission entries for this resource in the database, using Entrust.'),
        );
    }

}
