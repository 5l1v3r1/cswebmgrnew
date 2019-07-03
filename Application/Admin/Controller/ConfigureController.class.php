<?php
namespace Admin\Controller;
use Think\Controller;
class ConfigureController extends CommonController {
	public function exchangelist(){
		$Model = M('configure_exchange');
		$items = $Model->select();

		$output = [];
		$colors = ["bg-red","bg-blue","bg-green","bg-purple","bg-yellow"];
		$i=0;
		foreach($items as $k=>$v){
			$v['color'] = $colors[$i%5];
			$items[$k] = $v;
			array_push($output ,$items[$k]);
			$i = $i + 1;
		}
		//dump($items);
		$this->assign('currencies',$output);
		$this->display(T('admin/conf_exchange_list'));

	}
	public function techlist(){
		$Model = M('technologies');
		$teches = $Model->order('sortid asc')->select();
		$Model = M('worker_tech');
		foreach($teches as $k=>$v){
			$techcount = $Model->where('techid = '.$v['techid'])->count();
			$v['workernum'] = $techcount;
			$teches[$k] = $v;
		}

		$this->assign('teches',$teches);
		//print_r($teches);
		$this->display(T('admin/conf_tech_list'));

	}
	public function editexchangepage(){
		/*
		USD to RMB
		EUR ...
		CAD ...
		HKD ...
		AUD ...
		SGD ...
		*/
		$type = I('get.currency');
		$Model = M('configure_exchange');
		$cond['currency'] = $type;
		$result = $Model->where($cond)->find($cond);
		//dump($result);
		$this->assign('currency',$result);
		$this->display(T('admin/conf_exchange_edit'));


	}
	public function edittechpage(){
		/*
		USD to RMB
		EUR ...
		CAD ...
		HKD ...
		AUD ...
		SGD ...
		*/
		$id = I('get.techid');
		$Model = M('technologies');
		$cond['techid'] = $id;
		$result = $Model->where($cond)->find($cond);
		//dump($result);
		$this->assign('tech',$result);
		$this->display(T('admin/conf_tech_edit'));


	}
	public function editexchange(){
		$cond['id'] = I('post.currencyid');
		$data['currency'] = I('post.currency');
		$data['rating'] = I('post.rating');
		$Model = M('configure_exchange');
		$flag = $Model->where($cond)->save($data);
		$this->success('Update Exchange currency successfully!',U('Configure/exchangelist'),1);

	}
	public function edittech(){
		$cond['techid'] = I('post.techid');
		$data['sortid'] = I('post.sortid');//sort id
		$data['content'] = I('post.content');
		$data['docurls'] = I('post.docurls');//sort id
		$data['description'] = I('post.description');
		$data['attr'] = I('post.attr');
		$Model = M('technologies');
		$flag = $Model->where($cond)->save($data);
		$this->success('Update Technology successfully!',U('Configure/techlist'),1);

	}
	public function delexchange(){
		$cond['id'] = I('get.id');
		$Model = M('configure_exchange');
		$Model->where($cond)->delete();
		$this->success('Delete currency successfully!',U('Configure/exchangelist'),1);

	}
	public function deltech(){
		$cond['techid'] = I('get.techid');
		//echo $cond['techid'];
		$Model = M('worker_tech');
		$Model->where($cond)->delete();
		$Model = M('technologies');
		$Model->where($cond)->delete();
		$this->success('Delete technology successfully!',U('Configure/techlist'),1);
		/*
		$Model = M('configure_exchange');
		$Model->where($cond)->delete();
		$this->success('Delete currency successfully!',U('Configure/exchangelist'),1);
		*/

	}
	public function addexchangepage(){
		$this->display(T('admin/conf_exchange_add'));
	}
	public function addtechpage(){
		$this->display(T('admin/conf_tech_add'));
	}
	public function addexchange(){
		$data['currency'] = I('post.currency');
		$data['rating'] = I('post.rating');
		$Model = M('configure_exchange');
		$Model->data($data)->add();
		$this->success('Add currency successfully!',U('Configure/exchangelist'),1);
	}
	public function addtech(){
		$data['content'] = I('post.content');//description
		$data['sortid'] = I('post.sortid');//sort id docurls
		$data['docurls'] = I('post.docurls');//sort id
		$data['description'] = I('post.description');
		$data['attr'] = I('post.attr');
		$Model = M('technologies');
		$maxid = $Model->max('techid');
		$data['techid'] = $maxid + 1;
		$Model->data($data)->add();
		$this->success('Add technology successfully!',U('Configure/techlist'),1);
	}
	public function tradelist(){
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
		$this->display(T('admin/conf_trade_list'));
	}
	public function tradeInfoEditPage(){
		$cond['tid'] = I('get.tid');
		$pagetitle = "";
		$field_char = "";
		$techinfo = "";
		if($cond['tid'] == 0){
			$pagetitle = "Paypal Infomation ";
			$field_char = "paypal_info";

		}else if($cond['tid'] == 1){
			$pagetitle = "Guest Remark ";
			$field_char = "guest_remark";

		}else if($cond['tid'] == 2){
			$pagetitle = "worker techlist ";
			$field_char = "worker_techlist";
			$Model = M('technologies');
			$teches = $Model->select();
			foreach($teches as $k=>$v){
				$item = $v["techid"].". ".$v["content"]." ; ";
				$techinfo = $techinfo.$item;
			}


		}else if($cond['tid'] == 3){
			$pagetitle = "Worker notice0 ";
			$field_char = "workers_notice0";

		}else if($cond['tid'] == 4){
			$pagetitle = "Create Group notice ";
			$field_char = "create_group";

		}else if($cond['tid'] == 5){
			$pagetitle = "Worker notice1 ";
			$field_char = "workers_notice1";
		}else{
			$pagetitle = "Worker notice1 ";
			$field_char = "workers_notice1";
		}
		$Model = M('configure_trade');
		$res = $Model->field($field_char." as info")->where("id = 1")->find();
		//print_r($res);
		$this->assign('pagetitle',$pagetitle);
		$this->assign('tid',$cond['tid']);
		$this->assign('info',$res['info']);
		$this->assign('techinfo',$techinfo);
		$this->display(T('admin/conf_trade_edit'));
	}
	public function tradeupdate(){
		$cond['tid'] = I('get.tid');
		$pagetitle = "";
		$field_char = "";
		if($cond['tid'] == 0){
			$pagetitle = "Paypal Infomation ";
			$field_char = "paypal_info";

		}else if($cond['tid'] == 1){
			$pagetitle = "Guest Remark ";
			$field_char = "guest_remark";

		}else if($cond['tid'] == 2){

			$pagetitle = "worker techlist ";
			$field_char = "worker_techlist";

		}else if($cond['tid'] == 3){
			$pagetitle = "Worker notice0 ";
			$field_char = "workers_notice0";

		}else if($cond['tid'] == 4){
			$pagetitle = "Create Group notice ";
			$field_char = "create_group";

		}
		else if($cond['tid'] == 5){
			$pagetitle = "Worker notice1 ";
			$field_char = "workers_notice1";

		}else{
			$pagetitle = "Worker notice1 ";
			$field_char = "workers_notice1";
		}
		$data[$field_char] = str_replace("*","<br>",I('post.info'));
		//print_r($data);
		//echo $techinfo;
		$Model = M('configure_trade');
		$Model->where("id = 1")->save($data);
		$this->success('Update '.$pagetitle.' successfully!',U('Configure/tradelist'),1);

	}
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
		print_r($techinfo);
		//$this->display(T('admin/conf_trade_list'));
	}
	public function shotcutlist(){
		/*$Model = M('configure_exchange');
		$items = $Model->select();

		$output = [];
		$colors = ["bg-red","bg-blue","bg-green","bg-purple","bg-yellow"];
		$i=0;
		foreach($items as $k=>$v){
			$v['color'] = $colors[$i%5];
			$items[$k] = $v;
			array_push($output ,$items[$k]);
			$i = $i + 1;
		}
		//dump($items);
		$this->assign('currencies',$output);
		*/
		$this->display(T('admin/conf_shortcut_list'));

	}

}
