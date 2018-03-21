<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ZsinfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Zsinfos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zsinfo-index">
<div class="widget-box">
		<div class="widget-header">
			<h4>查询条件</h4>
		</div>
		<div class="widget-body">
			<div class="widget-main">
			                <?php echo $this->render('_search', ['model' => $searchModel,'tabletype'=>$tabletype]); ?>
            			</div>
		</div>
	</div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' =>  [
        ['class' => 'yii\grid\SerialColumn','header' => '序号'],

            [
                'attribute' => "plan_id",
                'format' => 'raw',
                'value' => function($model){
                        return $model->plan_id?$model->getPlan()->one()->name:'';
                },
            ],
            [
                'attribute'=>'source',
                'value' => function($model,$tabletype){
                    $tabletype=Yii::$app->params['tabletype'];
                    return $tabletype[$model->source];
                },
            ], 
            [
                'attribute' => "mid",
                'format' => 'raw',
                'value' => function($model){
                        return $model->mid?$model->getMember()->one()->name:'';
                },
            ],
            [
                'header' => "支付",
                'format' => 'raw',
                'headerOptions' => ['width' => '80'],
                'value' => function($model){
                    return $model->is_pay?'已付':'未付';
                }
            ],
            [
                'attribute' => "zs_id",
                'format' => 'raw',
                'value' => function($model){
                        return $model->zs_id?$model->getZs()->one()->name:'';
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => ' {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => '修改',
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
							'class' => 'btn btn-xs btn-info',
                        ];
                        return Html::a('<i class="icon-edit bigger-120"></i>', $url, $options);
                    },
                    'delete' => function ($url, $model, $key) {
                        $options = [
                            'title' => '删除',
                            'aria-label' => Yii::t('yii', 'Delete'),
                            //'data-confirm' => Yii::t('yii', '确认删除？'),
                            'data-pjax' => '0',
							'class' => 'btn btn-xs btn-danger',
                            'onclick' => 'sweetConfirmChange("确定要删除么","'.$url.'")',
                        ];
                        return Html::button('<i class="icon-trash bigger-120"></i>', $options);
                    },
                ]
            ],
         ],
    ]); ?>

</div>
