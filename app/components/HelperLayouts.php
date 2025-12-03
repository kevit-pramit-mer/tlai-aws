<?php
/**conflicts*/

namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Html;

/**
 * Class HelperLayouts
 *
 * @package app\components
 */
class HelperLayouts extends Component
{
    /**
     * To get common layout for Grid View
     *
     * @param $search_form_selector
     * @return string
     */
    public static function get_layout_str($search_form_selector)
    {
        return '
                <div class="table-responsive">
                {items}
                </div>
                <div class="row mt-2 mb-2">
                    <div class="col s12 m6 ">
                    <div class="d-flex align-items-center gap-10 pl-3">
                       
                         ' . Html::dropDownList('records_per_page', Yii::$app->session->get('per-page-result'),
                Yii::$app->params['records_per_page'], [
                    'class' => 'form-control',
                    'id' => 'change-records-per-page',
                    'onchange' => 'change_records_per_page($(this), "' . $search_form_selector . '")',
                ]) . '
                    {summary}
                    </div> </div>
                    <div class="col s12 m6 text-right">
                        {pager}
                    </div>
                   
                </div>';
    }

    /**
     * Set number of records per page
     *
     * @return mixed
     */
    public static function get_per_page_record_count()
    {
        if (Yii::$app->request->get('per-page')) {
            Yii::$app->session->set('per-page-result', Yii::$app->request->get('per-page'));
        } else {
            if (!Yii::$app->session->get('per-page-result')) {
                Yii::$app->session->set('per-page-result', Yii::$app->params['defaultPageSize']);
            }
        }

        return Yii::$app->session->get('per-page-result');
    }

    /**
     * @param $httpCode
     * @param $message
     * @param $data
     * @return array
     */
    public static function apiResponse($httpCode, $message, $data)
    {
        if ($data == null) {
            return [
                'status' => $httpCode,
                'message' => $message,
            ];
        }
        return [
            'status' => $httpCode,
            'message' => $message,
            'data' => $data,
        ];
    }

    /**
     * To get common layout for Grid View
     *
     * @return string
     */
    public static function get_layout_without_pager()
    {
        return '<div class="page-length-option_filter table-responsive" id="dataTables_filter">
        {items}
        </div>
        ';
    }

    public static function get_layout_str_license()
    {
        return '
                <div class="table-responsive">
                {items}
                </div>
                <div class="row mt-2 mb-2">
                    <div class="col s12 m6 ">
                    <div class="d-flex align-items-center gap-10 pl-3">
                    {summary}
                    </div> </div>
                    <div class="col s12 m6 text-right">
                        {pager}
                    </div>
                </div>';
    }
}
