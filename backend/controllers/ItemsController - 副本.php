<?php

namespace backend\controllers;

use Yii;
use backend\models\Items;
use backend\models\ItemsSearch;
use backend\models\ItemsType;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use backend\components\CController;
/**
 * ItemsController implements the CRUD actions for Items model.
 */
class ItemsController extends CController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','get'],
                ],
            ],
        ];
    }

    /**
     * Lists all Items models.
     * @return mixed
     */
    public function actionIndex()
    {
        $arr=ArrayHelper::map(ItemsType::find()->where(['is_exists'=>1,'is_delete'=>0])->orderBy('isDel,createTime desc')->all(),'typeId','typeName');
        $searchModel = new ItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type_list'=>$arr,
        ]);
    }

    /**
     * Displays a single Items model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Items model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Items();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Items model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $arr=ArrayHelper::map(ItemsType::find()->where(['is_exists'=>1,'is_delete'=>0])->orderBy('isDel,createTime desc')->all(),'vid','typeName');
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $url='https://vcloud.163.com/app/vod/video/edit';
            $post_data =array('vid'=>$model->vid,'videoName'=>$model->videoName,'vid'=>$model->vid,'description'=>$model->description);
            $return=getLiveByCURL($url,$post_data);
            $msg='更新视频失败！';
            if(!empty($return)){
                $arr=json_decode($return,true);
                if($arr['code']==200){
                      $model->save();
                      Yii::$app->session->setFlash('msg','更新视频成功！');
                      return $this->redirect(['index']);
                }else{
                    $msg.=$arr['msg'];
                }
            }
            Yii::$app->session->setFlash('msg',$msg);      
        } 
        return $this->render('update', [
                'model' => $model,
                'type_list'=>$arr,
            ]);
    }

    /**
     * Deletes an existing Items model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $is_exists=1;
        $num1=0;
        $num2=0;
        $arr=array();
        if(Yii::$app->request->isGet){
            $vid=Yii::$app->request->get('vid');
            $model=Items::findOne($vid);
            if($model->is_exists)  //存在，将外网的删除
                $arr[]=$vid;
            else{                   //不存在，将内网的删除
                $model->is_delete=1;
                $model->save();
                $is_exists=0;
            }
        }else if(Yii::$app->request->isPost){
            $arr=Yii::$app->request->post('ckbox');
        }
        if(!empty($arr)&&$is_exists){
            foreach ($arr as $key => $value) {
                $model=Items::findOne($value);
                if(!$model||!$model->is_exists)
                    continue;
                $url='https://vcloud.163.com/app/vod/video/videoDelete';
                $post_data =array('vid'=>$model->vid);
                $return=getLiveByCURL($url,$post_data);
                if(!empty($return)){
                    $arr=json_decode($return,true);
                    if($arr['code']==200){
                        $model->is_exists=0;
                        $model->is_delete=1;
                        $model->save();
                        $num1=1;
                    }else{
                        $num2=1;
                    }
                }
                
            }
            $msg='删除视频成功！';
            if($num1&&$num2)
                $msg='部分视频删除成功！';
            else if($num2&&!$num1)
                $msg='删除视频失败！';
            Yii::$app->session->setFlash('msg',$msg);
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Items model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Items the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Items::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //数据同步
    public function actionCommon()
    {
        $url='https://vcloud.163.com/app/vod/video/list';
        $post_data =array('currentPage'=>0,'pageSize'=>'-1','status'=>0,'type'=>0); //不用分页
        $return=getLiveByCURL($url,$post_data);
        if(!empty($return)){
            $ret=json_decode($return,true);
            if($ret['code']==200){
                Items::updateAll(['is_exists'=>0]);
                foreach ($ret['ret']['list'] as $key => $value) {
                    $model=Items::findOne($value['vid']);
                    if(!$model)
                        $model=new Items(); //无则新增，有则更新
                    $model->attributes=$value;
                    $model->is_exists=1;
                    $model->save();
                }

            }
        }
        Yii::$app->session->setFlash('msg','数据同步成功！');
        return $this->redirect(['index']);
    }

    //禁用与恢复
    public function actionDisabled(){
        $title='屏蔽';
        $add='videoDisable';
        $status=50;
        $status=Yii::$app->request->get('status');
        $num1=0;
        $num2=0;
        if(empty($status)){
            $add='videoDisable';
            $title='屏蔽';
            $status=50;
        }else if($status==40){
            $add='videoRecover';
            $title='恢复';
        }
        $arr=array();
        if(Yii::$app->request->isPost){
            $arr=Yii::$app->request->post('ckbox');
        }
        if(!empty($arr)){
            foreach ($arr as $key => $value) {
                $model = $this->findModel($value);
                if($model->status==$status)
                    continue;
                $url='https://vcloud.163.com/app/vod/video/'.$add;
                $post_data =array('vid'=>$model->vid);
                $return=getLiveByCURL($url,$post_data);
                if(!empty($return)){
                    $arr=json_decode($return,true);
                    if($arr['code']==200){
                          $model->status=$status;
                          $model->save();
                          $num1=1;
                    }else{
                        $num2=1;
                    }
                }
            }
            $msg=$title.'视频成功！';
            if($num1&&$num2)
                $msg='部分视频'.$title.'执行成功！';
            else if($num2&&!$num1)
                $msg=$title.'视频失败！';
             Yii::$app->session->setFlash('msg',$msg);
        }
        else
            Yii::$app->session->setFlash('msg',$title.'视频失败！');
        return $this->redirect(['index']);
    }
}
