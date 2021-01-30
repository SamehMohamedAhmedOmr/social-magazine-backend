<?php

namespace Modules\PreArticle\Helpers;

use Modules\PreArticle\Facades\StatusTypesHelper;
use Modules\PreArticle\Facades\StatusListHelper;


class StatusListCollection
{

    public function newStatus()
    {
        return [
            'type' => StatusTypesHelper::new(),
            'attributes' => [
                [
                    'name' => 'غير مكتمل',
                    'key' => StatusListHelper::NOT_COMPLETED(),
                    'description' => 'غير مكتمل'
                ],
                [
                    'name' => 'جديد',
                    'key' => StatusListHelper::NEW(),
                    'description' => 'مرسل بواسطة المؤلف'
                ],
            ]
        ];
    }

    public function specializedStatus()
    {
        return [
            'type' => StatusTypesHelper::specialized(),
            'attributes' => [
                [
                    'name' => 'مخصص لمحرر المجلة',
                    'key' => StatusListHelper::SPECIALIZED_FOR_EDITOR(),
                    'description' => 'تخصيص لمحرر'
                ],
                [
                    'name' => 'مخصص للتحكيم',
                    'key' => StatusListHelper::SPECIALIZED_FOR_REFEREES(),
                    'description' => 'تخصيص للمحكمين'
                ],
            ]
        ];
    }

    public function reviewStatus()
    {
        return [
            'type' => StatusTypesHelper::review(),
            'attributes' => [
                [
                    'name' => 'تحتاج الى اعادة ارسال',
                    'key' => StatusListHelper::NEED_FOR_RESENT(),
                    'description' => 'تحتاج الى اعادة ارسال من المؤلف'
                ],
                [
                    'name' => 'المقالة تحتاج الى مراجعة كبيرة',
                    'key' => StatusListHelper::NEED_FOR_BIG_REVIEW(),
                    'description' => 'المقالة تحتاج الى مراجعة كبيرة'
                ],
                [
                    'name' => 'المقالة تحتاج الى مراجعة ضئيلة',
                    'key' => StatusListHelper::NEED_FOR_SMALL_REVIEW(),
                    'description' => 'المقالة تحتاج الى مراجعة ضئيلة'
                ],
                [
                    'name' => 'المقالة موافق عليها لكن تحتاج الى مراجعة ضئيلة',
                    'key' => StatusListHelper::ACCEPTED_WITH_NEED_FOR_SMALL_REVIEW(),
                    'description' => 'المقالة موافق عليها لكن تحتاج الى مراجعة ضئيلة'
                ],
            ]
        ];
    }

    public function rejectedStatus()
    {
        return [
            'type' => StatusTypesHelper::rejected(),
            'attributes' => [
                [
                    'name' => 'رفض للأهداف و النطاق',
                    'key' => StatusListHelper::REJECTED_DUE_GOALS(),
                    'description' => 'رفض المقالة للأهداف و النطاق',
                ],
                [
                    'name' => 'رفض للارسال المتكرر',
                    'key' => StatusListHelper::REJECTED_DUE_MANY_RESENT(),
                    'description' => 'رفض المقالة للارسال المتكرر',
                ],
                [
                    'name' => 'رفض للنتائج المتشابهة',
                    'key' => StatusListHelper::REJECTED_DUPLICATE(),
                    'description' => 'رفض المقالة للنتائج المتشابهة',
                ],
                [
                    'name' => 'رفض لعدم الأولوية',
                    'key' => StatusListHelper::REJECTED_DUE_NO_PRIORITY(),
                    'description' => 'رفض المقالة لعدم الأولوية',
                ],
                [
                    'name' => 'رفض لمشاكل ادبية',
                    'key' => StatusListHelper::REJECTED_DUE_LITERARY_PROBLEMS(),
                    'description' => 'رفض المقالة لمشاكل ادبية',
                ],
                [
                    'name' => 'رفض بسبب  كل المحكمين رفضوا التحكيم',
                    'key' => StatusListHelper::REJECTED_DUE_ALL_ARBITRATORS_REFUSED_THE_ARBITRATION(),
                    'description' => 'رفض المقالة بسبب  كل المحكمين رفضوا التحكيم',
                ],
                [
                    'name' => 'رفض بسبب توصيات المحكمين/المحرر',
                    'key' => StatusListHelper::REJECTED_DUE_REFEREES_RECOMMENDATIONS_OR_EDITOR(),
                    'description' => 'رفض المقالة بسبب توصيات المحكمين/المحرر',
                ],
                [
                    'name' => 'رفض',
                    'key' => StatusListHelper::REJECTED(),
                    'description' => 'رفض المقالة',
                ],
            ]
        ];
    }

    public function acceptedStatus()
    {
        return [
            'type' => StatusTypesHelper::accepted(),
            'attributes' => [
                [
                    'name' => 'مرسلة للسداد',
                    'key' => StatusListHelper::SENT_FOR_PAYMENT(),
                    'description' => 'ارسال المقالة للمؤلف للسداد',
                ],
                [
                    'name' => 'قبول المقالة علميا',
                    'key' => StatusListHelper::ACCEPTED_SCIENTIFICALLY(),
                    'description' => 'قبول المقالة علميا',
                ],
                [
                    'name' => 'قبول المقالة بشكل نهائي',
                    'key' => StatusListHelper::FINALLY_ACCEPTED(),
                    'description' => 'قبول المقالة بشكل نهائي',
                ],
            ]
        ];
    }

    public function withdrawalStatus()
    {
        return [
            'type' => StatusTypesHelper::withdrawal(),
            'attributes' => [
                [
                    'name' => 'سحب',
                    'key' => StatusListHelper::WITHDRAWAL(),
                    'description' => 'سحب',
                ],
            ]
        ];
    }

}
