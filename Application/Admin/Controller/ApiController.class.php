<?php
namespace Admin\Controller;
use Think\Controller;
class ApiController  extends Controller {
	public function tradeApi(){
		$Model = M('configure_trade');
		$res = $Model->where("id = 1")->find();
		$this->assign('res',$res);
		$Model = M('technologies');
		$teches = $Model->select();
		foreach($teches as $k=>$v){
			$item = $v["techid"].". ".$v["content"]." ; ";
			$techinfo = $techinfo.$item;
		}
		$this->assign('techinfo',$techinfo);
		echo "<br><br>";
		echo $res["paypal_info"];
		echo "<br><br>";
		echo $res["guest_remark"];
		echo "<br><br>";
		echo $techinfo.$res["worker_techlist"];
		echo "<br><br>";
		echo $res["workers_notice0"];
		//$this->display(T('admin/conf_trade_list'));
	}

}
