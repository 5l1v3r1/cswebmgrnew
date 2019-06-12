<?php
namespace Admin\Controller;
use Think\Controller;
class ApiController  extends Controller {
	public function tradeApi(){
		$Model = M('configure_trade');
		$res = $Model->where("id = 1")->find();
		$this->assign('res',$res);
		$Model = M('technologies');
		$teches = $Model->order("sortid asc")->select();
		$flag = 0;
		foreach($teches as $k=>$v){
			if($v["sortid"]%100 == 0){
				$techinfo = $techinfo."*********<br>";
			}
			if($v["description"] != ""){
				$item = "[".$v["techid"]."] ".$v["content"]."/* ".$v["description"]."*/;  <br>";
			}else{
				$item = "[".$v["techid"]."] ".$v["content"]."".$v["description"].";  <br>";
			}
			$techinfo = $techinfo.$item;
		}
		$this->assign('techinfo',$techinfo);
		$this->display(T('admin/apipage'));
	}

}
