<?php
namespace backend\components;

use Yii;
use yii\base\Widget;
use backend\models\Admin;
use backend\models\Menu;

class LeftWidget extends Widget{
    public function init(){
       parent::init();
    }

    public function run(){
        $admin = Admin::findById(Yii::$app -> user -> id);
        //START-�õ����˵�
        $menu = new Menu();
        /****�жϵ�¼�û�ӵ�еĲ˵� START******************************************************************************/
        $auth = Yii::$app->authManager;
        $userid = Yii::$app->user->identity->id;
        $leftMenu = $menu ->getUserLeftMenu($userid);
        /****�жϵ�¼�û�ӵ�еĲ˵� END******************************************************************************/
        //END-�õ����˵�
        return $this -> render('left',['admin' => $admin,'leftMenu' => $leftMenu]);
    }

}
