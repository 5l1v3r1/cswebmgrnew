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
	public function editexchange(){
		$cond['id'] = I('post.currencyid');
		$data['currency'] = I('post.currency');
		$data['rating'] = I('post.rating');
		$Model = M('configure_exchange');
		$flag = $Model->where($cond)->save($data);
		$this->success('Update Exchange currency successfully!',U('Configure/exchangelist'),1);

	}
	public function delexchange(){
		$cond['id'] = I('get.id');
		$Model = M('configure_exchange');
		$Model->where($cond)->delete();
		$this->success('Delete currency successfully!',U('Configure/exchangelist'),1);

	}
	public function addexchangepage(){
		$this->display(T('admin/conf_exchange_add'));
	}
	public function addexchange(){
		$data['currency'] = I('post.currency');
		$data['rating'] = I('post.rating');
		$Model = M('configure_exchange');
		$Model->data($data)->add();
		$this->success('Add currency successfully!',U('Configure/exchangelist'),1);
	}

}
