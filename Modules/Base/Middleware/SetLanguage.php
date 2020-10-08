<?php

namespace Modules\Base\Middleware;

use Closure;
use Illuminate\Foundation\Application as App;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager as Session;

class SetLanguage
{
    protected $app;
    protected $session;
    protected $language_repository;

    /**
     * Init new object.
     *
     * @param   App $app
     * @param   Session $session
     *
     * @return  void
     */
    public function __construct(App $app, Session $session)
    {
        $this->app = $app;
        $this->session = $session;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $acceptLanguage = 'ar';
        $this->app->setLocale($acceptLanguage);
       // $this->session->put('language_id', $this->language_repository->getLangId($acceptLanguage));

        return $next($request);
    }
}
