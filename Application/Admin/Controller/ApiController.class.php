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
			$item = $v["techid"].". ".$v["content"].$v["description"]." ; ";
			$techinfo = $techinfo.$item;
		}
		$this->assign('techinfo',$techinfo);
		$this->display(T('admin/apipage'));
	}

}
