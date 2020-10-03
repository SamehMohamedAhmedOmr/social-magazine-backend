<?php

namespace Modules\WareHouse\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager as Session;
use Modules\WareHouse\Entities\Country;

class DetectCountry
{
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $country_id = $request->header('country-id');
        $country_id = trim($country_id);
        $country_id = (int)$country_id;

        $country = Country::where([
            'id' => $country_id,
            'is_active' => 1
        ])->first();

        if (!$country) {
            $country_code = config('base.default_country');
            $country = Country::where('country_code', $country_code)->first();
            $country_id = $country->id;
        }

        $this->session->put('country_id', $country_id);

        return $next($request);
    }
}
