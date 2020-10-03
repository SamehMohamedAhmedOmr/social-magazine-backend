<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Configuration\Entities\Tenant\Tenant;
use Modules\Configuration\Services\CMS\ConfigurationService;

class TenantsMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:migrate {tenant?} {--fresh} {--seed}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->argument('tenant')) {
            $project_information = \Cache::get($this->argument('tenant'));

            if (!$project_information){
                $configuration_service = \App::make(ConfigurationService::class);
                $project_information = $configuration_service->baseConfiguration($this->argument('tenant'));
            }

            $tenant = new Tenant($project_information);

            $tenant->configure()->use();
            $this->migrate($tenant, $project_information);

        } else {
            $configuration_service = \App::make(ConfigurationService::class);
            $projects_information = $configuration_service->getAllProjects();

            foreach ($projects_information as $project_information){
                $tenant = new Tenant($project_information);

                $tenant->configure()->use();
                $this->migrate($tenant, $project_information);
            }
        }
    }

    /**
     * @param Tenant $tenant
     * @param $project_information
     * @return void
     */
    public function migrate($tenant, $project_information)
    {
        $tenant->configure()->use();

        $this->line('');
        $this->line("-----------------------------------------");
        $this->info("Migrating Tenant #{$project_information['id']} ({$project_information['name']})");
        $this->line("-----------------------------------------");

        $options = ['--force' => true];

        if ($this->option('seed')) {
            $options['--seed'] = true;
        }

        $this->call(
            $this->option('fresh') ? 'migrate:fresh' : 'migrate',
            $options
        );

    }
}
