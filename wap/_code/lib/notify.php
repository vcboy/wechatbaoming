<?php
ini_set('date.timezone','Asia/Shanghai');
ini_set("display_errors", "1");
error_reporting(E_ALL);
/*echo 'aaa';
exit(Q::ini('app_config/PAY_DIR'));*/
require_once Q::ini('app_config/PAY_DIR')."../lib/WxPay.Api.php";
require_once Q::ini('app_config/PAY_DIR')."../lib/WxPay.Notify.php";
require_once Q::ini('app_config/PAY_DIR').'log.php';

//初始化日志
$logHandler= new CLogFileHandler(Q::ini('app_config/PAY_DIR')."../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	public $dbo;
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		//平台逻辑
		//$data中各个字段在return_code为SUCCESS的时候有返回 SUCCESS/FAIL
		//此字段是通信标识，非交易标识，交易是否成功需要查看result_code来判断
		//成功后写入自己的数据库
		if ($data['return_code'] == 'SUCCESS' && $data['result_code'] == 'SUCCESS') {
			//自己的业务逻辑 
			$out_trade_no = $data['out_trade_no']; 
			$transaction_id = $data['transaction_id'];
			$bank_type = $data['bank_type'];
			$fee_type = $data['fee_type'];
			$time_end = $data['time_end'];
			$amount = $data['total_fee'];
			
			/*$con = mysqli_connect("127.0.0.1","root","root");
			if (!$con)
			{
			    die('Could not connect: ' . mysqli_error());
			}
			mysqli_select_db("wechatbaoming", $con);
			$sql = "update wx_order"*/
			$sql= "update wx_order set state = 1 where order_no = '".$out_trade_no."'";
			$this->dbo->execute ($sql);

			$sql = "select plan_id,source,sid from wx_order where order_no = '".$out_trade_no."'";
			$orderinfo = $this->dbo->getAll($sql);
			$source = $orderinfo[0]['source'];
			$sid = $orderinfo[0]['sid'];
			$plan_id = $orderinfo[0]['plan_id'];
			//更新招生表
			$sql= "update wx_zsinfo set is_pay = 1 where plan_id = ".$plan_id." and sid = ".$sid;
			$this->dbo->execute ($sql);

			//更新报名表
			switch ($source) {
				case '1':
					
					break;
				case '2':
					$sql= "update wx_jianding_table set is_pay = 1 where  id = ".$sid;
					$this->dbo->execute ($sql);
					break;
				case '3':
					
					break;
				case '4':
					
					break;
				default:
					# code...
					
					break;
			}
		} 

		return true;
	}


	public function testdb($out_trade_no){
		$sql= "update wx_order set state = 1 where order_no = '".$out_trade_no."'";
		$this->dbo->execute ($sql);

		$sql = "select plan_id,source,sid from wx_order where order_no = '".$out_trade_no."'";
		$orderinfo = $this->dbo->getAll($sql);
		$source = $orderinfo[0]['source'];
		$sid = $orderinfo[0]['sid'];
		$plan_id = $orderinfo[0]['plan_id'];
		//更新招生表
		$sql= "update wx_zsinfo set is_pay = 1 where plan_id = ".$plan_id." and sid = ".$sid;
		$this->dbo->execute ($sql);

		//更新报名表
		switch ($source) {
			case '1':
				
				break;
			case '2':
				$sql= "update wx_jianding_table set is_pay = 1 where  id = ".$sid;
				$this->dbo->execute ($sql);
				break;
			case '3':
				
				break;
			case '4':
				
				break;
			default:
				# code...
				
				break;
		}
	}
}

Log::DEBUG("begin notify");

