<?php

namespace Thor\Platform;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'thor:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates initial setup for Thor CMS, including Thor models migrations.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->output->writeln('Running migrations...');

        if (ThorFacade::isInstalled() and ( $this->option('force-reinstall') == false)) {
            $this->output->writeln('A Thor CMS setup has been detected and cannot continue. '
                    . 'If you want to reset and reseed your database, run the command again with --force-reinstall');
        }

        if (!ThorFacade::isInstalled()) {
            if($this->option('migrations')){
                $this->output->writeln('Running migrations...');
                $this->call('migrate:publish', array('package' => 'thor/platform'));
                $this->call('migrate');
            }
            if($this->option('seeders')){
                $this->output->writeln('Running seeders...');
                $this->call('db:seed', array('--class' => '\\Thor\\Models\\Seeders\\DatabaseSeeder'));
            }
        }

        // Publish assets, configs and langs
        $this->call('asset:publish', array('package' => 'thor/platform'));
        $this->call('config:publish', array('package' => 'thor/platform'));
        $this->call('thor:lang-publish', array('package' => 'thor/platform'));


        // Publish backend routes file
        $package_src = realpath(__DIR__ . '/../../');

        if (!file_exists(app_path('routes'))) {
            mkdir(app_path('routes'), 0755, true);
        }
        file_put_contents(app_path('routes/backend.php'), file_get_contents($package_src . '/routes.php'));


        $this->output->writeln('<info>Thor CMS has been installed successfully.</info>');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
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
            array('migrations', 'm', InputOption::VALUE_OPTIONAL, 'Publishes and runs all migrations', true),
            array('seeders', 's', InputOption::VALUE_OPTIONAL, 'Runs all thor/platform seeders', true),
        );
    }

}
