<?php
namespace Admin\Controller;
use Think\Controller;
class TransactionController extends CommonController {
	/*trans list*/
	public function translist()
	{
		$search =I('post.search');
        $Model = M('transaction');
        if(!empty($search))
        {
            $condition['transactionID'] = array('like','%'.$search.'%');
            $condition['clientname'] = array('like','%'.$search.'%');
            $condition['orderID'] = array('like','%'.$search.'%');
            $condition['paymethod'] = array('like','%'.$search.'%');
			$condition['accountnumber'] = array('like','%'.$search.'%');
            $condition['_logic'] = 'OR';
            $list = $Model->where($condition)->order('paydate desc')->page(I('get.p').',42')->select();
		    $count = $Model->where($condition)->count();// get count of records
        }else
        {
            $list = $Model->where("id>=0")->order('paydate desc')->page(I('get.p').',42')->select();
		    $count = $Model->where("id>=0")->count();// get count of records
        }
        
		
		//print_r($list);
		/**
		* pages
		**/
		
		$Page = new \Think\Page($count,42);// page object
		$Page->setConfig('prev','prev');
		$Page->setConfig('next','next');
		$Page->setConfig('first','first page');
		$Page->setConfig('last','last page');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
		$show = $Page->show();// page output
		$this->assign('page',$show);// 
		$this->assign('list',$list);
        $this->display(T('mgr/transactions_list'));
		
	}
	public function transdetail()
	{
		$transid = I('get.transid');
		$data['transactionID'] = $transid;
		/*transaction*/
		$Model = M('transaction');
		$trans = $Model->where($data)->find();
		$showpay = 0;
		$showperson = 0;
		if($trans['paymethod'] =="Bank Transfer" || $trans['paymethod'] =="Cheque" || $trans['paymethod']== "E-Banking"){
			$showpay = 1;
		}
		if($trans['paymethod'] =="PayPal" || $trans['paymethod'] =="Credit Card"){
			$showperson = 1;
		}
		$this->assign('showpay',$showpay);// 
		$this->assign('showperson',$showperson);//
		$this->assign('trans',$trans);// 
		$this->assign('transid',$transid);// 
		/*get payment method*/
		/*$Model = M('paymethod');
		$condition['useable'] = 'Y';
		$ct = $Model->field('method')->where($condition)->select();
		$this->assign('payments',$ct);*/
		
		
		$this->display(T('mgr/transactions_detail'));
		//print_r($trans);
	}
	public function transupdate()
	{
		$transid = I('post.transactionID');
		$where['transactionID'] = $transid;
		if(!empty(I('post.clientname'))){
			$data['clientname'] = I('post.clientname');
		}
		if(!empty(I('post.accountnumber'))){
			$data['accountnumber'] = I('post.accountnumber');
		}
		$data['invoiceID'] = I('post.invoiceID');
		//$data['paymethod'] = I('post.paymethod');
		$data['settleamount'] = I('post.settleamount');
		$data['paydate'] = date('Y-m-d H:i:s', strtotime(I('post.paydate')));
		$data['description'] = I('post.description');
		$M3 = M('transaction');
		$M3->where($where)->save($data);
		$this->success('Update the transaction successfully!',U('Transaction/transdetail?transid='.$transid.''),1);
		
		
	}
	public function transdel()
	{
		$transid = I('get.transid');
		$data['transactionID'] = $transid;
		/*transaction*/
		$Model = M('transaction');
		$trans = $Model->where($transid)->find();
		/*del order*/
		
		
		
		$this->assign('trans',$trans);// 
		$this->assign('transid',$transid);// 
		$this->display(T('mgr/transactions_detail'));
		//print_r($trans);
	}
	
}