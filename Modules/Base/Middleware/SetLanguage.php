<?php

namespace Modules\Base\Middleware;

use Closure;
use Illuminate\Foundation\Application as App;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager as Session;
use Modules\Settings\Repositories\LanguageRepository;
use Languages;

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
     * @param   LanguageRepository $language_repository
     *
     * @return  void
     */
    public function __construct(App $app, Session $session, LanguageRepository $language_repository)
    {
        $this->app = $app;
        $this->session = $session;
        $this->language_repository = $language_repository;
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
        $acceptLanguage = $request->header('Accept-Language');
        $locale = in_array($acceptLanguage, Languages::getActiveISO()) ? $acceptLanguage : config('app.fallback_locale');
        $this->app->setLocale($locale);
        $this->session->put('language_id', $this->language_repository->getLangId($locale));

        return $next($request);
    }
}
