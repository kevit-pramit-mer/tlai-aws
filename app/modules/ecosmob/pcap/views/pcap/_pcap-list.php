<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\CommonHelper;

/* @var $dataProvider yii\data\ActiveDataProvider */

?>
 <div class="dataTables_wrapper" id="page-length-option_wrapper">

<?= GridView::widget([
    'id' => 'pcap-grid-index',
    'dataProvider' => $dataProvider,
    'layout' => Yii::$app->layoutHelper->get_layout_str('#pcap-search-form'),
    'showOnEmpty' => true,
    'pager' => [
        'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">Previous</a>',
        'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">Next</a>',
        'maxButtonCount' => 5,
    ],
    'options' => [
        'tag' => FALSE,
    ],
    'columns' => [
//         [
//             'attribute' => 'ct_id',
//             'label' => PcapModule::t('pcap', 'action'),
//             //'label'=>'Action',
//             'format' => 'raw',
//             'headerOptions' => ['class' => 'text-center'],
//             'contentOptions' => ['class' => 'text-center inline-class action_space'],
//             'enableSorting' => True,
//             'value' => function ($model) {
//                 ini_set('max_execution_time', 300);
//                 set_time_limit(0);
//                 $record_filename = $model->ct_url;
//                 /*if ($record_filename != '') {*/
//                 $end = explode('/', $record_filename);
//                 $end = array_reverse($end)[0];
//                 $audioFilePath = Url::to('@web' . '/media/pcap/');
// //                  $audioFilePath = Url::to(Yii::$app->params['adminStorageFullPath'] . 'recordings/');
//                 $url = $audioFilePath . $end;

//                 /*return '<a  href="'. $url.'" download="download"><i class="material-icons" style="color: #474747">file_download</i></a>
//                         ';
//             } else {
//                 return '-';
//             }*/

//                 return (($record_filename != '') ? Html::a('<i class="material-icons">file_download</i>',
//                     $url,
//                     [
//                         'data-toggle' => 'popover',
//                         'data-placement' => 'top',
//                         'data-trigger' => "hover",
//                         'data-content' => 'Call Path',
//                         'data-pjax' => 0,
//                         'class' => 'btn btn-danger btn-sm',
//                         //'target'         => '_blank',
//                     ]) : '-');

//             },
//         ],

        [
            'class'=>'yii\grid\ActionColumn',
            'template'=>'{update}{delete}',
            'header'=>Yii::t('app', 'action'),
            'headerOptions'=>['class'=>'center width-10'],
            'contentOptions'=>['class'=>'center width-10'],
            'buttons'=>[
                'update'=>function ($url, $model) {
                    ini_set('max_execution_time', 300);
                    set_time_limit(0);
                    $record_filename = $model->ct_url;

                    $url = Url::to('@web' . '/media/' . $GLOBALS['tenantID'] . '/pcap/' . $model->ct_filename);

                    return (($record_filename != '') ? Html::a('<i class="material-icons">file_download</i>',
                        $url,
                        [
                            'data-toggle' => 'popover',
                            'data-placement' => 'top',
                            'data-trigger' => "hover",
                            'data-content' => 'Call Path',
                            'data-pjax' => 0,
                            'class' => 'ml-5',
                            //'target'         => '_blank',
                        ]) : '-');
                },
                'delete'=>function ($url) {
                    $permissions=$GLOBALS['permissions'];
                    if (in_array('/pcap/pcap/delete', $permissions)) {
                        return (1 ? Html::a('<i class="material-icons">delete</i>',
                            $url,
                            [

                                'class'=>'ml-5',
                                'data-pjax'=>0,
                                'style'=>'color:#FF4B56',
                                'data-confirm'=>Yii::t('app', 'delete_confirm'),
                                'data-method'=>'post',
                                'title'=>Yii::t('app', 'delete'),
                            ]) : '');
                    }else{
                        return '';
                    }
                },
            ],
        ],
        [
            'attribute' => 'ct_start',
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'enableSorting'=> TRUE,
            'value' => function ($model) {
                return ($model->ct_start == NULL)
                    ?
                    "-"
                    : ($model->ct_start == "")
                        ? "-"
                        :
                        CommonHelper::tsToDt($model->ct_start);
            },

        ],
        [
            'attribute' => 'ct_stop',
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'enableSorting'=> TRUE,
            'value' => function ($model) {
                return ($model->ct_stop == NULL)
                    ?
                    "-"
                    : ($model->ct_stop == "")
                        ? "-"
                        :
                        CommonHelper::tsToDt($model->ct_stop);
            },

        ],
        [
            'attribute' => 'ct_filename',
            'enableSorting'=> TRUE,
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
        ],
    ],
    'tableOptions' => [
        'class' => 'display dataTable dtr-inline',
        
    ],
]); ?>
</div>

