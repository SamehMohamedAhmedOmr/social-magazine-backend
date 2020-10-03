<?php

namespace App\Providers;

use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Modules\Configuration\Entities\Tenant\Tenant;
use Modules\Configuration\Facades\TenantHelper;
use Modules\Configuration\Services\CMS\ConfigurationService;

class TenancyProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRequests();

        $this->configureQueue();
    }

    /**
     *
     */
    public function configureRequests()
    {
        if (!$this->app->runningInConsole()) {
            $sub_domain = TenantHelper::getSubDomain();

            $configuration_service = \App::make(ConfigurationService::class);

            $project_information = \Cache::get($sub_domain);

            if (!$project_information){
                $project_information = $configuration_service->baseConfiguration($sub_domain);
            }

            // to use if calling info API
            Session::put('current_sub_domain', $sub_domain);
            Session::put($sub_domain, $project_information);

            $tenant = new Tenant($project_information);

            $tenant->configure()->use();
        }
    }

    /**
     *
     */
    public function configureQueue()
    {
        $this->app['queue']->createPayloadUsing(function () {
            return $this->app['mysql'] ? ['tenant_id' => $this->app['mysql']->id] : [];
        });

        $this->app['events']->listen(JobProcessing::class, function ($event) {
            if (isset($event->job->payload()['tenant_id'])) {
                $sub_domain = TenantHelper::getSubDomain();

                $configuration_service = \App::make(ConfigurationService::class);

                $project_information = \Cache::get($sub_domain);

                if (!$project_information){
                    $project_information = $configuration_service->baseConfiguration($sub_domain);
                }

                // to use if calling info API
                \Session::put($sub_domain, $project_information);

                $tenant = new Tenant($project_information);

                $tenant->configure()->use();
            }
        });
    }

}
