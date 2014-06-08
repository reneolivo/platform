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
        $singular = strtolower(trim($this->argument('singular')));

        if (empty($singular)) {
            return $this->error('thor:generate error: Resource (singular) name cannot be empty');
        }

//        if($this->confirm('Do you wish to create the required classes and views for ' . $singular . '? [yes|no]')) {
        $behaviours = $this->argument('behaviours');
        $generalFields = $this->ask('Non-translatable fields:', 'string:label');
        $translatableFields = $this->ask('Translatable fields (optional):', false);
        $listableFields = $this->ask('Listable fields for the index view:', false);
        $resolver = \CRUD::generate($singular, $behaviours, $generalFields, $translatableFields, $listableFields);

        if ($this->option('create-permissions')) {
            \CRUD::createPermissions($singular, false);
            $this->info("Entrust CRUD permissions created successfully. Remember to assign them to some role(s).\n");
        }

        $this->line('');
        $this->info("CRUD Generation succeed. Add this line somewhere in your routes.php file:");
        $this->line("\n\CRUD::routes('{$singular}');\n");

        $this->line("Remember to execute 'php artisan migrate' too.\n");
//        } else {
//            $this->line('Cancelled');
//        }
    }

    /**
     * Get the console cconsoleommand arguments.
     *
     * @return array
     */
    protected function getArguments()
    {

        return array(
            array('singular', InputArgument::REQUIRED, 'Resource singular name.'),
            array('behaviours', InputArgument::OPTIONAL, 'Resource model behaviours.', false)
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
            array('create-permissions', 'c', InputOption::VALUE_NONE, 'Creates CRUD permission entries for this resource in the database, using Entrust.'),
        );
    }

}
