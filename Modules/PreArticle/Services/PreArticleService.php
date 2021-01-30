<?php

namespace Modules\PreArticle\Services;

use Modules\Base\Facade\CacheHelper;
use Modules\Base\ResponseShape\ApiResponse;
use Modules\Base\Services\Classes\LaravelServiceClass;
use Modules\PreArticle\Facades\PreArticleCache;
use Modules\PreArticle\Repositories\ArticleFilterRepository;
use Modules\PreArticle\Repositories\ArticleStatusTypeRepository;
use Modules\PreArticle\Repositories\ArticleSubjectRepository;
use Modules\PreArticle\Repositories\ArticleTypeRepository;
use Modules\PreArticle\Repositories\AttachmentTypeRepository;
use Modules\PreArticle\Repositories\CurrencyTypeRepository;
use Modules\PreArticle\Repositories\PaymentMethodRepository;
use Modules\PreArticle\Repositories\PriceTypeRepository;
use Modules\PreArticle\Repositories\RefereesRecommendationsRepository;
use Modules\PreArticle\Transformers\ArticleFilter;
use Modules\PreArticle\Transformers\ArticleStatusType;
use Modules\PreArticle\Transformers\ArticleSubject;
use Modules\PreArticle\Transformers\ArticleType;
use Modules\PreArticle\Transformers\AttachmentType;
use Modules\PreArticle\Transformers\CurrencyType;
use Modules\PreArticle\Transformers\PaymentMethod;
use Modules\PreArticle\Transformers\PriceType;
use Modules\PreArticle\Transformers\RefereesRecommendations;

class PreArticleService extends LaravelServiceClass
{
    private $articleStatusRepository, $articleFilterRepository,
            $articleSubjectRepository, $articleTypeRepository,
            $attachmentTypeRepository, $currencyTypeRepository,
            $paymentMethodRepository, $priceTypeRepository,
            $refereesRecommendationsRepository;

    public function __construct(ArticleStatusTypeRepository $articleStatusRepository,
                                ArticleFilterRepository $articleFilterRepository,
                                ArticleSubjectRepository $articleSubjectRepository,
                                ArticleTypeRepository $articleTypeRepository,
                                AttachmentTypeRepository $attachmentTypeRepository,
                                CurrencyTypeRepository $currencyTypeRepository,
                                PaymentMethodRepository $paymentMethodRepository,
                                PriceTypeRepository $priceTypeRepository,
                                RefereesRecommendationsRepository $refereesRecommendationsRepository)
    {
        $this->articleStatusRepository = $articleStatusRepository;
        $this->articleFilterRepository = $articleFilterRepository;
        $this->articleSubjectRepository = $articleSubjectRepository;
        $this->articleTypeRepository = $articleTypeRepository;
        $this->attachmentTypeRepository = $attachmentTypeRepository;
        $this->currencyTypeRepository = $currencyTypeRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->priceTypeRepository = $priceTypeRepository;
        $this->refereesRecommendationsRepository = $refereesRecommendationsRepository;
    }

    public function index()
    {
        $article_status = $this->articleStatus();
        $article_filter = $this->articleFilter();

        $article_subject = $this->articleSubject();
        $article_type = $this->articleType();
        $attachment_type = $this->attachmentType();
        $currency_type = $this->currencyType();
        $price_type = $this->priceType();
        $payment_method = $this->paymentMethod();
        $referees_recommendations = $this->RefereesRecommendations();


        return ApiResponse::format(200, [
            'article_status' => $article_status,
            'article_filter' => $article_filter,

            'article_subject' => $article_subject,
            'article_type' => $article_type,
            'attachment_type' => $attachment_type,
            'currency_type' => $currency_type,
            'price_type' => $price_type,
            'payment_method' => $payment_method,
            'referees_recommendations' => $referees_recommendations,
        ]);
    }


    public function articleStatus()
    {
        $content = CacheHelper::getCache(PreArticleCache::statusList());

        if (!$content) {
            $content = $this->articleStatusRepository->all();

            $content->load([
                'statusList'
            ]);

            CacheHelper::putCache(PreArticleCache::statusList(), $content);
        }


        return ArticleStatusType::collection($content);
    }

    public function articleFilter()
    {
        $content = CacheHelper::getCache(PreArticleCache::statusFilter());

        if (!$content) {
            $content = $this->articleFilterRepository->all();

            CacheHelper::putCache(PreArticleCache::statusFilter(), $content);
        }


        return ArticleFilter::collection($content);
    }

    public function articleSubject()
    {
        $content = CacheHelper::getCache(PreArticleCache::articleSubject());

        if (!$content) {
            $content = $this->articleSubjectRepository->all();

            CacheHelper::putCache(PreArticleCache::articleSubject(), $content);
        }


        return ArticleSubject::collection($content);
    }

    public function articleType()
    {
        $content = CacheHelper::getCache(PreArticleCache::articleType());

        if (!$content) {
            $content = $this->articleTypeRepository->all();

            CacheHelper::putCache(PreArticleCache::articleType(), $content);
        }


        return ArticleType::collection($content);
    }

    public function attachmentType()
    {
        $content = CacheHelper::getCache(PreArticleCache::attachmentsType());

        if (!$content) {
            $content = $this->attachmentTypeRepository->all();

            CacheHelper::putCache(PreArticleCache::attachmentsType(), $content);
        }


        return AttachmentType::collection($content);
    }

    public function currencyType()
    {
        $content = CacheHelper::getCache(PreArticleCache::currencyType());

        if (!$content) {
            $content = $this->currencyTypeRepository->all();

            CacheHelper::putCache(PreArticleCache::currencyType(), $content);
        }


        return CurrencyType::collection($content);
    }

    public function priceType()
    {
        $content = CacheHelper::getCache(PreArticleCache::priceType());

        if (!$content) {
            $content = $this->priceTypeRepository->all();

            CacheHelper::putCache(PreArticleCache::priceType(), $content);
        }


        return PriceType::collection($content);
    }

    public function paymentMethod()
    {
        $content = CacheHelper::getCache(PreArticleCache::paymentMethod());

        if (!$content) {
            $content = $this->paymentMethodRepository->all();

            CacheHelper::putCache(PreArticleCache::paymentMethod(), $content);
        }


        return PaymentMethod::collection($content);
    }

    public function RefereesRecommendations()
    {
        $content = CacheHelper::getCache(PreArticleCache::refereesRecommendation());

        if (!$content) {
            $content = $this->refereesRecommendationsRepository->all();

            CacheHelper::putCache(PreArticleCache::refereesRecommendation(), $content);
        }


        return RefereesRecommendations::collection($content);
    }



}
