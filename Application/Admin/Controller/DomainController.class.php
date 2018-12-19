<?php
namespace Admin\Controller;
use Think\Controller;
class DomainController extends CommonController {
	public function domainlist()
	{
		$search =I('post.search');
        $Model = M('domainmgr');
        if(!empty($search))
        {
            $condition['domainname'] = array('like','%'.$search.'%');
            $condition['username'] = array('like','%'.$search.'%');
            $condition['registrar'] = array('like','%'.$search.'%');
            $condition['status'] = array('like','%'.$search.'%');
			$condition['orderID'] = array('like','%'.$search.'%');
            $condition['_logic'] = 'OR';
            $list = $Model->where($condition)->order('id desc')->page(I('get.p').',42')->select();
		    $count = $Model->where($condition)->count();// get count of records
        }else
        {
            $list = $Model->where("id>=0")->order('id desc')->page(I('get.p').',42')->select();
		    $count = $Model->where("id>=0")->count();// get count of records
        }
        $User = M('users');
		$ct["status"] = 'active';
		$userlists = $User->where($ct)->select();	
		//print_r($list);
		/**
		* pages
		**/
		/*get payment method*/
		$Model = M('paymethod');
		$conditions['useable'] = 'Y';
		$cts = $Model->field('method')->where($conditions)->select();
		$this->assign('payments',$cts);
		
		
		$Page = new \Think\Page($count,42);// page object
		$Page->setConfig('prev','prev');
		$Page->setConfig('next','next');
		$Page->setConfig('first','first page');
		$Page->setConfig('last','last page');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
		$show = $Page->show();// page output
		$this->assign('page',$show);// 
		$this->assign('list',$list);
		$this->assign('users',$userlists);
        $this->display(T('mgr/domains_list'));
		
	}
	
	
	
	public function domaindetail()
	{
		$domainid = I('get.domainid');
		$data['id'] = $domainid;
		$Model = M('domainmgr');
		$domain = $Model->where($data)->find();
		$this->assign('domain',$domain);// 
		$this->assign('domainid',$domainid);// 
		/* Get orderId */
		$ct['domainname'] = $domain['domainname'];
		$Mitem = M('item');
		$orders = $Mitem->where($ct)->find();
		$orderID = $orders['orderID'];
		$this->assign('orderid',$orderID);//
		/*get domain whois*/
		$msg = getWhois($domain['domainname']);
        $flag =  $msg[1];
        $whoisinfo =  $msg[0];//whoisinfo
		$this->assign('whois',$whoisinfo);// 
		/*get registrar*/
		$Model = M('registrar');
		$condition['status'] = 'Y';
		$cts = $Model->field('registrar')->where($condition)->select();
		$this->assign('registrar',$cts);
		$this->display(T('mgr/domains_detail'));
	}
	public function domainupdate()
	{
		$where['id'] = I('get.domainid');
		$data['domainname'] = I('post.domainname');
		$data['expirydate'] = date('Y-m-d',strtotime(I('post.expirydate')));
		$data['nextduedate'] = date('Y-m-d',strtotime(I('post.nextduedate')));
		$data['status'] = I('post.status');
		$data['registrar'] = I('post.registrar');
		$data['autorenew'] = I('post.autorenew');
		$Model = M('domainmgr');
		$Model->where($where)->save($data);
		$this->success('Update the domain successfully!',U('Domain/domaindetail?domainid='.$where['id'].''),1);
		
	}
	public function domainprofileupdate()
	{
		$where['id'] = I('get.domainid');
		$data['email'] = I('post.email','','htmlspecialchars');//get email
		$data['firstname'] = I('post.firstname','','htmlspecialchars');//get firstname
		$data['lastname'] = I('post.lastname','','htmlspecialchars');//get firstname
		$data['company'] = I('post.company','','htmlspecialchars');//get firstname
		$data['jobtitle'] = I('post.jobtitle','','htmlspecialchars');//get firstname
		$data['address1'] = I('post.address1','','htmlspecialchars');//get firstname
		$data['address2'] = I('post.address2','','htmlspecialchars');//get firstname
		$data['city'] = I('post.city','','htmlspecialchars');//get firstname
		$data['state'] = I('post.state','','htmlspecialchars');//get firstname
		$data['postcode'] = I('post.postcode','','htmlspecialchars');//get firstname
		$data['country'] = I('post.country','','htmlspecialchars');//get firstname
		$data['phone'] = I('post.phone','','htmlspecialchars');//get firstname
		$data['fax'] = I('post.fax','','htmlspecialchars');//get firstname
		$data['ns1'] = I('post.ns1','','htmlspecialchars');//get firstname
		$data['ns2'] = I('post.ns2','','htmlspecialchars');//get firstname
		$data['ns3'] = I('post.ns3','','htmlspecialchars');//get firstname
		$data['ns4'] = I('post.ns4','','htmlspecialchars');//get firstname
		$Model = M('domainmgr');
		$Model->where($where)->save($data);
		$this->success('Update the Domain Registration Profile successfully!',U('Domain/domaindetail?domainid='.$where['id'].''),1);
	}
	public function domaintools()
	{
		$where['id'] = I('get.domainid');
		$data['mainforward'] = I('post.mainforward');
		$data['IDprotect'] = I('post.IDprotect');
		$data['DNSmgr'] = I('post.DNSmgr');
		$Model = M('domainmgr');
		$Model->where($where)->save($data);
		$this->success('Update the domain tools successfully!',U('Domain/domaindetail?domainid='.$where['id'].''),1);
	}
	public function domainsendemail()
	{
		$domainid = I('get.domainid');
		$this->success('Send Email successfully!',U('Domain/domaindetail?domainid='.$domainid.''),1);
	}
	public function domainremove()
	{
		$where['id'] = I('get.domainid');
		$Model = M('domainmgr');
		$domain = $Model->where($where)->find();
		$Model->where($where)->delete();
		/*del item*/
		$Mitem =  M('item');
		$ct0['domainname'] = $domain['domainname'];
		$iteminfo = $Mitem->where($ct0)->find();//del item
		$Mitem->where($ct0)->delete();//del item
		$itemCount = $Mitem->where($ct0)->count();
		/*del domain*/
		if($itemCount == 0)
		{
			$ctd['orderID'] = $iteminfo['orderID'];
			$Mdomain = M('order');
			$Mdomain->where($ctd)->delete();//del domain
		}
			
		
		$this->success('Remove the domain successfully!',U('Domain/domainlist'),1);
		
	}
	public function domainadd()
	{
		if(I('post.accounttype','','htmlspecialchars') == "PayPal" || 	I('post.accounttype','','htmlspecialchars') == "Credit Card" ){
			if(I('post.clientname','','htmlspecialchars') == "" || I('post.accountnumber','','htmlspecialchars') == "" ){
				$this->error('When the paymethod is paypal or Credit Card, you must input account infomation! ',U('Domain/domainlist'),3);
			}
		}
		$nowtime = date('Y-m-d H:i:s',time());
		$msg = array();
        $dm_name = I('post.domainname');
        $dm_name = str_replace('www.','',$dm_name);
        if(empty($dm_name)){
            $this->error('Domain name must be not null！');
            return 0;
        }
		//get price configure
        /*$Mt = M('fakedomains');
        $dprice["domainname"] = $dm_name;
        $presult = $Mt->where($dprice)->find();
        if(!empty($presult))
        {
            $pieces = explode(".", $dm_name);
            $Model = M('premium');
            $dcp["domainname"] = array('like','%.'.$pieces[count($pieces)-1]);
            $content2 = $Model->where($dcp)->find();
            if(!empty($content2))
            {
                $price = $content2['price']*($content2['rate']+1);//increase 20%
            }else
            {
                $Model = M('configure');
                $re = $Model->field('domainprice')->where('id=1')->find();
                $price = $re['domainprice'];
            }
        }else
        {
            $Model = M('configure');
            $re = $Model->field('domainprice')->where('id=1')->find();
            $price = $re['domainprice'];
        }*/
		$price = 0;
		$pieces = explode(".", $dm_name);
        $Model = M('premium');
        $dcp["domainname"] = array('like','%.'.$pieces[count($pieces)-1]);
        $content2 = $Model->where($dcp)->find();
        if(!empty($content2))
        {
            $Mt = M('fakedomains');
            $dprice["domainname"] = $dm_name;
            $presult = $Mt->where($dprice)->find();
            if(!empty($presult))
            {
                $price = $content2['price']*($content2['rate']+1);//increase rateing
            }else
            {
                $price = $content2['price'];//increase 20%
            }   
        }else
        {
            $Model = M('configure');
            $re = $Model->field('domainprice')->where('id=1')->find();
            $price = $re['domainprice'];
        }
		/*check domain useful*/
		$showflag = 0;//
        $msg = getWhois($dm_name);
        $flag =  $msg[1];
        $whoisinfo =  $msg[0];//whoisinfo
        //print($flag);
        if($flag == "Y"){////we can use it!
            $data["domainname"] = $dm_name;
            $Model = M('domainmgr');
		    $content = $Model->field('domainname,nextduedate,status')->where($data)->find();
            if(!empty($content))
            {
                $duedate = strtotime($content['nextduedate']);
                $now = time();
                if($content['status'] != 'suspend')
                {
                    if($content['status'] != 'pending')
                    {
                        if($now > $duedate)// domain : pending(renew/wait to check) active suspend
                        {
                            $showflag = 2;
                        }
                    }
                    
                }
                
            }else
            {
                $showflag = 1;
            }
            
        }
		if($showflag == 0)
		{
			$this->error('Domain has been used!Please change new domain!');
			return 0;
		}
		/*build order item transaction*/
		$transactionID = time()+101;
		$orderID = time();
		$years = I('post.years');
		$username = I('post.username');
		$paymethod =  I('post.paymethod');
		$total = $price * $years;
		/*get registar*/
		$Model = M('registrar');
		$condition['status'] = 'Y';
		$ct = $Model->field('registrar')->where($condition)->select();
		shuffle($ct);
		//print_r($ct);
		$registrar = $ct[0]['registrar'];
		
		$Model = M('item');
		$item["domainname"] = $dm_name;
		$item["orderID"] = $orderID;
		$item["registrar"] = $registrar;
		$item["price"] = $price;
		$item["years"] = $years;
		$Model->data($item)->add();
		
		
		$paystatus = 'active';
		$expiry_db = date('Y-m-d', strtotime('+'.$years.' year', strtotime($nowtime)));
		$nextdue_db = date('Y-m-d', strtotime('+'.$years.' year', strtotime($nowtime)));
		$registrationdate = $nowtime;
		$dudate_order = $nowtime;
		
		
		/*get domain infomation*/
		$data['domainname'] = $dm_name;
		$data['username'] = $username;
		$data['registrar'] = $registrar;
		$data['registrationdate'] = $registrationdate;
		$data['expirydate'] = $expiry_db;
		$data['nextduedate'] = $nextdue_db;
		$data['status'] = $paystatus;
		$data['mainforward'] = '';
		$data['DNSmgr'] = '';
		$data['orderID'] = $orderID;
		
		$cuser['username'] = $username;//get username
		$User = M('users');
		$userinfo = $User->where($cuser)->find();

		/*get domain registation profile*/
		$data['email'] = $userinfo['email'];//get email
		$data['firstname'] = $userinfo['firstname'];//get firstname
		$data['lastname'] = $userinfo['lastname'];//get firstname
		$data['company'] = $userinfo['company'];//get firstname
		$data['jobtitle'] = $userinfo['jobtitle'];//get firstname
		$data['address1'] = $userinfo['address1'];//get firstname
		$data['address2'] = $userinfo['address2'];//get firstname
		$data['city'] = $userinfo['city'];//get firstname
		$data['state'] = $userinfo['state'];//get firstname
		$data['postcode'] = $userinfo['postcode'];//get firstname
		$data['country'] = $userinfo['country'];//get firstname
		$data['phone'] = $userinfo['phone'];//get firstname
		$data['fax'] = $userinfo['fax'];//get firstname
		$data['ns1'] = "ns1.namserver.com";//get firstname
		$data['ns2'] = "ns2.namserver.com";//get firstname
		$data['ns3'] = "ns3.namserver.com";//get firstname
		$data['ns4'] = "ns4.namserver.com";//get firstname
		//print_r($data);
		//print $showflag;
		$DoM = M('domainmgr');
		if($showflag == 2)
		{
			$cwdomain['domainname'] = $dm_name;
			$DoM->where($cwdomain)->save($data);
		}else
		{
			$DoM->data($data)->add();
		}
		
		/*get order infomation*/
		$orderM = M('order');
		$order['orderID'] = $orderID;//get order id
		$order['transactionID'] = $transactionID;//get id
		$order['username'] = $username;
		$order['issuedate'] = date('Y-m-d H:i:s',time());
		$order['status'] = "pending";
		$order['refundamount'] = 0.0;
		$order['invoicedate'] = date('Y-m-d H:i:s',time());
		$order['duedate'] = $dudate_order;
		$order['description'] = 'Administrator add new order';
		$orderM->data($order)->add();
		//transaction
		
		
		$transM = M('transaction');
		$trans['transactionID'] = $transactionID;
		$trans['clientname'] = I('post.clientname','','htmlspecialchars');
		$trans['orderID'] = $orderID;
		$trans['invoiceID'] = time();
		$trans['description'] = 'I use the '.$paymethod.' to pay for the order';
		$trans['paydate'] = date('Y-m-d H:i:s',time());
		$trans['paymethod'] = $paymethod;
		$trans['accountnumber'] = I('post.accountnumber','','htmlspecialchars');
		$trans['settleamount'] = $total;
		$transM->data($trans)->add();
		
		
		//$this->assign('items',$item);
		//$this->assign('data',$data);
		//$this->assign('order',$order);
		//$this->assign('trans',$trans);
		//print_r($ct);
		$this->success('ADD the domain(order & transaction) successfully!',U('Domain/domainlist'),1);
		//print_r($data);
		//print_r($item[$index]);
		
	}
	
	
	
	
}