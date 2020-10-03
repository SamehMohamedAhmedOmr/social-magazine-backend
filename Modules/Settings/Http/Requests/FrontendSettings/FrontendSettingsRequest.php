<?php

namespace Modules\Settings\Http\Requests\FrontendSettings;

use Illuminate\Foundation\Http\FormRequest;

class FrontendSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
        {
            $active_languages = implode(',', getActiveISO());

            $font_rule = 'integer|exists:fonts,id';

            $delete_check = ',deleted_at,NULL';

            switch ($this->getMethod()) {
                case 'POST':
                    $rules = [
                        // colors
                        'colors.main_color' => 'string|max:255',
                        'colors.second_color' => 'string|max:255',
                        'colors.third_color' => 'string|max:255',

                        /* files */
                        'typography.main_font' => $font_rule,
                        'typography.bold_font' => $font_rule,
                        'typography.regular_font' => $font_rule,
                        'typography.italic_font' => $font_rule,

                        'favicon' => 'integer|exists:gallery,id',
                        'logo' => 'integer|exists:gallery,id',
                        'social_sharing_img' => 'integer|exists:gallery,id',

                        'app_nav_structure_id' => 'integer|exists:application_navigation_structure,id'.$delete_check,

                        /* Social Media */
                        'social_media.facebook' => 'string|url|max:255',
                        'social_media.twitter' => 'string|url|max:255',
                        'social_media.instagram' => 'string|url|max:255',
                        'social_media.youtube' => 'string|url|max:255',
                        'social_media.google_plus' => 'string|url|max:255',
                        'social_media.pinterest' => 'string|url|max:255',
                        'social_media.linkedin' => 'string|url|max:255',

                        'google_analytics_id' => 'string|max:255',
                        'facebook_pixel_id' => 'string|max:255',
                        'enable_recaptcha' => 'boolean',

                        'data' => 'array',
                        'data.*.lang' => 'required|string|in:'.$active_languages,
                        'data.*.home_page_title' => 'required|string|max:255',
                        'data.*.home_page_meta_desc' => 'required|string|max:500',
                    ];
                break;
                default:
                    $rules = [];
                    break;
            }
            return $rules;
        }


    public function attributes()
    {
        return [
            // colors
            'colors.main_color' => trans('settings::attributes.main_color'),
            'colors.second_color' => trans('settings::attributes.second_color'),
            'colors.third_color' => trans('settings::attributes.third_color'),

            /* files */
            'typography.main_font' => trans('settings::attributes.main_font'),
            'typography.bold_font' => trans('settings::attributes.bold_font'),
            'typography.regular_font' => trans('settings::attributes.regular_font'),
            'typography.italic_font' => trans('settings::attributes.italic_font'),

            'favicon' => trans('settings::attributes.favicon'),
            'logo' => trans('settings::attributes.logo'),
            'social_sharing_img' => trans('settings::attributes.social_sharing_img'),

            'app_nav_structure_id' => trans('settings::attributes.app_nav_structure_id'),

            /* */
            'social_media.facebook' => trans('settings::attributes.facebook'),
            'social_media.twitter' => trans('settings::attributes.twitter'),
            'social_media.instagram' => trans('settings::attributes.instagram'),
            'social_media.youtube' => trans('settings::attributes.youtube'),
            'social_media.google_plus' => trans('settings::attributes.googleplus'),
            'social_media.pinterest' => trans('settings::attributes.pinterest'),
            'social_media.linkedin' => trans('settings::attributes.linkedin'),

            'google_analytics_id' => trans('settings::attributes.google_analytics_id'),
            'facebook_pixel_id' => trans('settings::attributes.facebook_pixel_id'),
            'enable_recaptcha' => trans('settings::attributes.enable_recaptcha'),

            'data' => trans('settings::attributes.data'),
            'data.*.lang' => trans('settings::attributes.lang'),
            'data.*.home_page_title' => trans('settings::attributes.home_page_title'),
            'data.*.home_page_meta_desc' => trans('settings::attributes.home_page_meta_desc'),
        ];
    }

    public function prepareForValidation()
    {
        prepareBeforeValidation($this, ['data']);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
