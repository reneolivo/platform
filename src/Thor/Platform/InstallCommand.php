<?php

namespace Thor\Platform;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Backend;

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
    protected $description = 'Creates initial setup for Thor CMS, including Entrust, Confide and Thor models migrations.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->output->writeln('Running migrations...');

        if (Backend::isInstalled() and ( $this->option('force-reinstall') == false)) {
            $this->output->writeln('A Thor CMS setup has been detected and cannot continue. '
                    . 'If you want to reset and reseed your database, run the command again with --force-reinstall');
        }

        if (!Backend::isInstalled()) {
            $this->call('confide:migration');
            $this->call('entrust:migration');
            $this->call('migrate:publish', array('package' => 'thor/models'));
        } else {
            $this->call('migrate:reset');
            \DB::table('migrations')->delete();
        }

        $this->call('migrate');

        $this->output->writeln('Running seeders...');
        $this->call('db:seed', array('--class' => '\\Thor\\Models\\Seeders\\DatabaseSeeder'));

        // Publish assets, configs and langs
        $this->call('asset:publish', array('package' => 'thor/platform'));
        $this->call('config:publish', array('package' => 'thor/platform'));
        $this->call('lang:publish', array('package' => 'thor/platform'));

        $this->output->writeln('<info>Thor CMS admin has been installed successfully.</info>');
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
            array('force-reinstall', 'f', InputOption::VALUE_OPTIONAL, 'If there is a current installation, all migrations are reset and seeded again', false),
        );
    }

}
