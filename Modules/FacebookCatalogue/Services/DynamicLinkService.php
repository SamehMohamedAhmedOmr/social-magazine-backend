<?php

namespace Modules\FacebookCatalogue\Services;

use Kreait\Firebase\DynamicLinks;
use Modules\FacebookCatalogue\Entities\FacebookCatalogueSettings;

class DynamicLinkService
{
    protected $dynamicLinks;
    protected $options;

    public function __construct(DynamicLinks $dynamicLinks, FacebookCatalogueSettings $catalogueSettings)
    {
        $this->dynamicLinks = $dynamicLinks;
        $this->options = $this->initialize($catalogueSettings);
    }

    public function initialize($catalogueSettings)
    {
        $data = $catalogueSettings->first();
        return [
            'androidInfo' => [
                'androidPackageName' => isset($data) ? $data->android_package_name : null,
                'androidFallbackLink' => isset($data) ? $data->android_fallback_link : null,
                'androidMinPackageVersionCode' => isset($data) ? $data->android_min_package_version_code : null,
            ],
            'iosInfo' => [
                'iosBundleId' => isset($data) ? $data->ios_bundle_id : null,
                'iosFallbackLink' => isset($data) ? $data->ios_fallback_link : null,
            ]
        ];
    }

    public function configuration($url)
    {
        $domain_prefix = explode($url, '.');
        return array_merge([
            'dynamicLinkInfo' => [
                'domainUriPrefix' => $domain_prefix[0].'.page.link',
                'link' => $url,
            ],
            'suffix' => ['option' => 'SHORT'],
        ], $this->options);
    }

    public function createDynamicLink(string $url)
    {
        try {
            $config = $this->configuration($url);
            $link = $this->dynamicLinks->createDynamicLink($config);
            return (string) $link;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
