<?php

namespace backend\controllers;

use Yii;
use backend\models\Jianding;
use backend\models\JiandingSearch;
use backend\models\Plan;
use backend\models\ScoreImport;
use yii\data\ArrayDataProvider;
use backend\components\CController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Writer_Excel2007;
use PHPExcel_Style_Alignment;
use yii\web\UploadedFile;

/**
 * JiandingController implements the CRUD actions for Jianding model.
 */
class JiandingController extends CController
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
    public $arr = [];
    public $importdata = [];

    public function init(){
        $this->arr = ArrayHelper::map(Plan::find()->where(['is_delete'=>0])->orderBy('id desc')->all(),'id','name');
    }

    /**
     * Lists all Jianding models.
     * @return mixed
     */
    public function actionIndex()
    {
        $arr=ArrayHelper::map(Plan::find()->where(['is_delete'=>0,'tabletype'=>2])->orderBy('id desc')->all(),'id','name');
        $searchModel = new JiandingSearch();
        $where = " and is_sh = 1";
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'planlist' => $arr,
        ]);
    }

    /**
     * Lists no shenghe Jiangding models.
     * @return [type] [description]
     */
    public function actionNosh(){
        $arr=ArrayHelper::map(Plan::find()->where(['is_delete'=>0,'tabletype'=>2])->orderBy('id desc')->all(),'id','name');
        $searchModel = new JiandingSearch();
        $where = " and is_sh = 0";
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);

        return $this->render('nosh', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'planlist' => $arr,
        ]);
    }

    /**
     * Displays a single Jianding model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->layout = false;
        $model = $this->findModel($id);
        $education = $model->education;
        $job = $model->job;
        $edu_arr = $job_arr = array();
        if($education){
            $edu_arr = json_decode($education,true);
        }
        if($job){
            $job_arr = json_decode($job,true);
        }
        return $this->render('print1', [
            'model' => $model,
            'edu' => $edu_arr,
            'job' => $job_arr,
        ]);
    }

    /**
     * Creates a new Jianding model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Jianding();

        if ($model->load(Yii::$app->request->post())) {
            $educationsj = Yii::$app->request->post('educationsj');
            $educationxx = Yii::$app->request->post('educationxx');
            $educationzy = Yii::$app->request->post('educationzy');
            $educationxl = Yii::$app->request->post('educationxl');
            foreach ($educationsj as $key => $value) {
                $education[] = [$educationsj[$key],$educationxx[$key],$educationzy[$key],$educationxl[$key]];
            }
            $edustr = json_encode($education);
            $model->education = $edustr;
            //exit();
            $model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'planlist' => $this->arr,
            ]);
        }
    }

    /**
     * Updates an existing Jianding model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'planlist' => $this->arr,
            ]);
        }
    }

    /**
     * Deletes an existing Jianding model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model['is_delete'] = 1;
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Jianding model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Jianding the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Jianding::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * [actionShenhe description]
     * @return [type] [description]
     */
    public function actionShenhe(){
        $request = Yii::$app->request;
        $ckbox = implode(',', $request->post('ckbox'));
        $sql_where = "id in ($ckbox)";
        //echo $sql_where;
        $query = Jianding::find()->where($sql_where)->all();
        foreach($query as $key=>$val){
            $val->is_sh = 1;
            $val->save();
        }
        return $this->redirect(['nosh']);
    }

    /**
     * [actionOnecun description]
     * @return [type] [description]
     */
    public function actionOnecun(){
        $this->layout = false;
        $request = Yii::$app->request;
        $ckbox = implode(',', $request->post('ckbox'));
        $sql_where = "id in ($ckbox)";
        $query = Jianding::find()->where($sql_where)->all();

        return $this->render('onecun', [
            'models' => $query,
        ]);
    }

    /**
     * [actionOnecun description]
     * @return [type] [description]
     */
    public function actionTwocun(){
        $this->layout = false;
        $request = Yii::$app->request;
        $ckbox = implode(',', $request->post('ckbox'));
        $sql_where = "id in ($ckbox)";
        $query = Jianding::find()->where($sql_where)->all();

        return $this->render('twocun', [
            'models' => $query,
        ]);
    }

    /**
     * [actionOnecun description]
     * @return [type] [description]
     */
    public function actionSfz(){
        $this->layout = false;
        $request = Yii::$app->request;
        $ckbox = implode(',', $request->post('ckbox'));
        $sql_where = "id in ($ckbox)";
        $query = Jianding::find()->where($sql_where)->all();

        return $this->render('sfz', [
            'models' => $query,
        ]);
    }

    /**
     * 打印表单
     */
    public function actionPrint(){
        $this->layout = false;
        $request = Yii::$app->request;
        $ckbox = implode(',', $request->post('ckbox'));
        $sql_where = "id in ($ckbox)";
        $query = Jianding::find()->where($sql_where)->all();

        return $this->render('mprint1', [
            'models' => $query,
            //'edu' => $edu_arr,
            //'job' => $job_arr,
        ]);
        /*foreach($query as $key=>$val){
            $val->taxis = $request->post("taxis_".$val['id']);
            $val->save();
        }
        return $this->redirect(['index']);*/
    }

    /**
     * 打印表单
     */
    public function actionPlanprint($id){
        $this->layout = false;
        $sql_where = "plan_id = ".$id;
        $query = Jianding::find()->where($sql_where)->all();

        return $this->render('mprint1', [
            'models' => $query,
        ]);
    }

    /**
     * 数据导出
     * @return [type] [description]
     */
    public function actionStatistics(){
        //设置内存
        ini_set("memory_limit", "2048M");
        set_time_limit(0);

        //获取用户ID
        $id         =   Yii::$app->user->identity->getId();

        //去用户表获取用户信息
        //$user       =   Employee::find()->where(['id'=>$id])->one();

        //获取传过来的信息（时间，公司ID之类的，根据需要查询资料生成表格）
        $params     =   Yii::$app->request->get();
        $objectPHPExcel = new \PHPExcel();

        //设置表格头的输出

        $objectPHPExcel->setActiveSheetIndex()->setCellValue('A1', '序号');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('I1', '所属活动');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('B1', '申报单位');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('C1', '姓名');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('D1', '性别');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('E1', '民族');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('F1', '出生日期');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('G1', '身份证');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('H1', '联系方式');


        //跳转到recharge这个model文件的statistics方法去处理数据
        $data = Jianding::statistics($params);
        /*var_dump($data);
        exit();*/
        //指定开始输出数据的行数
        $n = 2;
        foreach ($data as $v){
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.($n) ,$n-1);
            $objectPHPExcel->getActiveSheet()->setCellValue('I'.($n) ,$v['plan_name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('B'.($n) ,$v['company']);
            $objectPHPExcel->getActiveSheet()->setCellValue('C'.($n) ,$v['name']);
            $objectPHPExcel->getActiveSheet()->setCellValue('D'.($n) ,$v['sex']);
            $objectPHPExcel->getActiveSheet()->setCellValue('E'.($n) ,$v['nation']);
            $objectPHPExcel->getActiveSheet()->setCellValue('F'.($n) ,$v['birthday']);
            $objectPHPExcel->getActiveSheet()->setCellValue('G'.($n) ,$v['sfz']);
            $objectPHPExcel->getActiveSheet()->setCellValue('H'.($n) ,$v['tel']);
            $n = $n +1;
        }
        ob_end_clean();
        ob_start();
        header('Content-Type : application/vnd.ms-excel');

        //设置输出文件名及格式
        header('Content-Disposition:attachment;filename="鉴定表'.date("YmdHis").'.xls"');

        //导出.xls格式的话使用Excel5,若是想导出.xlsx需要使用Excel2007
        $objWriter= \PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
        ob_end_flush();

        //清空数据缓存
        unset($data);
    }

    /**
     * 数据预览
     */
    public function actionImport()  
    {  
        $model = new ScoreImport();
        $arr=ArrayHelper::map(Plan::find()->where(['is_delete'=>0,'tabletype'=>2])->orderBy('id desc')->all(),'id','name');
        $ok = "";  
        $path = Yii::$app->basePath.'/uploads/';
        $importdata = $errordata = []; 
        if ($model->load(Yii::$app->request->post())) {  
            $file = UploadedFile::getInstance($model, 'file' );  //获取上传的文件实例  
            $plan_id = $model->plan_id;
            if ($file) {
                $filename = date('Y-m-d',time()).'_'.rand(1,9999).".". $file->extension;  
                $file->saveAs($path.$filename);   //保存文件  
      
                $format = $file->extension;  
                if(in_array($file->extension,array('xls','xlsx'))){  
                    $excelFile = Yii::getAlias($path.$filename.'');//获取文件名  
  
                    // $phpexcel=new \PHPExcel();  
                    // if ($format == "xls") {  
                    //    $excelReader = \PHPExcel_IOFactory::createReader('Excel5');  
                    // } else {  
                    //    $excelReader = \PHPExcel_IOFactory::createReader('Excel2007');   
                    // }  
                      
                    $fileType   = \PHPExcel_IOFactory::identify($excelFile); //文件名自动判断文件类型  
                    $excelReader  = \PHPExcel_IOFactory::createReader($fileType);  
  
                      
                    $phpexcel    = $excelReader->load($excelFile)->getSheet(0);//载入文件并获取第一个sheet  
                    $total_line  = $phpexcel->getHighestRow();//总行数  
                    $total_column= $phpexcel->getHighestColumn();//总列数  
                    // $highestColumn = $objWorksheet->getHighestColumn();//最大列数 为数字  
                    // $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn); //将字母变为数字  
                    $column = array(
                        'A' => 'name',
                        'B' => 'sfz',
                        'C' => 'score',
                    );
                    if($total_line > 1){  
                        $jianarr=ArrayHelper::map(Jianding::find()->where(['is_delete'=>0,'is_sh'=>1,'plan_id'=>$plan_id])->all(),'id','sfz');
                        for($row = 2;$row <= $total_line; $row++){ 
                            $sfz = trim($phpexcel->getCell('B'.$row)->getValue());
                            if(in_array($sfz, $jianarr)){
                               foreach ($column as $key => $value) {
                                    //for($column = 'A'; $column < $total_column; $column++){

                                    $importdata[$row][$value] = trim($phpexcel->getCell($key.$row)->getValue()); 
                                } 
                            }else{
                                foreach ($column as $key => $value) {
                                    //for($column = 'A'; $column < $total_column; $column++){

                                    $errordata[$row][$value] = trim($phpexcel->getCell($key.$row)->getValue()); 
                                }
                            }
                            
  
                            //一行行的插入数据库操作  
                            /*$_model = new Quenstion;  
                            $_model->content = $data[0];  
                            $_model->create  = time();  
                            if ($_model->save()) {  
                                $ok = 1;  
                            } */ 
                        }   
                    }  
                    //var_dump($data);
                    //exit();
                    /*if($ok == 1){
                        //$this->redirect(array('index']);  
                    }else{  
                        echo "<script>alert('操作失败');window.history.back();</script>";  
                    }*/  
                }  
            } 

            
            //$resultData = Jianding::find()->where(['plan_id' => $plan_id,'is_delete' => 0,'is_sh' => 1])->all();

            $dataProvider = new ArrayDataProvider([
                //'key'=>'id',
                'allModels' => $importdata,
                'pagination' => false, // 可选 不分页
                'sort' => [
                    'attributes' => ['score'],
                ],
            ]); 
            $dataProvidererror = new ArrayDataProvider([
                //'key'=>'id',
                'allModels' => $errordata,
                'pagination' => false, // 可选 不分页
                'sort' => [
                    'attributes' => ['score'],
                ],
            ]);
            /*$dataProvider = new ArrayDataProvider([
                'dataProvider' => $query,
            ]); */     
              
        }else{
            /*$searchModel = new JiandingSearch();
            $where = " and is_sh = 1";
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where); */
            $dataProvider = new ArrayDataProvider([
                //'key'=>'id',
                'allModels' => array(),
                'pagination' => false, // 可选 不分页
                'sort' => [
                    'attributes' => ['score'],
                ],
            ]); 
            $dataProvidererror = new ArrayDataProvider([
                //'key'=>'id',
                'allModels' => array(),
                'pagination' => false, // 可选 不分页
                'sort' => [
                    'attributes' => ['score'],
                ],
            ]);
            $plan_id = 0;
        }              
        /*$searchModel = new JiandingSearch();
        $where = " and is_sh = 0";
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$where);*/
        

        return $this->render('import', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvidererror' => $dataProvidererror,
            'planlist' => $arr,
            'model' => $model,
            'plan_id' => $plan_id,
        ]);
    } 

    /**
     * 导入数据
     */
    public function actionImportdata(){
        $request = Yii::$app->request;
        $scorearr = $request->post('score');
        $plan_id = $request->post('plan_id');
        if(!empty($scorearr)){
            foreach ($scorearr as $key => $value) {
                $user = explode('-', $value);
                $cid[] = $user[1];
                $sco[$user[1]] = $user[0];
            }
        }
        $ckbox = implode(',', $cid);
        $sql_where = "plan_id = $plan_id and sfz in ($ckbox)";
        /*var_dump($sco);
        echo $sql_where;*/
        $query = Jianding::find()->where($sql_where)->all();
        foreach($query as $key=>$val){
            $val->score = $sco[$val->sfz];
            $val->save();
        }
        //return $this->redirect(['index']);
    }

    public function actionDownload()
    {
        $filename = "成绩导入模板.xlsx"; //输出内容
        $filepath = Yii::$app->basePath .DIRECTORY_SEPARATOR . 'web'. DIRECTORY_SEPARATOR."import".DIRECTORY_SEPARATOR."score.xlsx";
        $response = Yii::$app->response;
        $response->sendFile($filepath, $filename);
    }
}
