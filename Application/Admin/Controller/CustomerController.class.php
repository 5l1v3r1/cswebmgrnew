<?php
namespace Admin\Controller;
use Think\Controller;
class CustomerController extends CommonController {
    public function customerlist()
    {
        $search =I('post.search');
        $Model = M('users');
        if(!empty($search))
        {
            $condition['username'] = array('like','%'.$search.'%');
            $condition['firstname'] = array('like','%'.$search.'%');
            $condition['lastname'] = array('like','%'.$search.'%');
            $condition['email'] = array('like','%'.$search.'%');
            $condition['city'] = array('like','%'.$search.'%');
            $condition['state'] = array('like','%'.$search.'%');
            $condition['country'] = array('like','%'.$search.'%');
            $condition['_logic'] = 'OR';
            $list = $Model->where($condition)->order('regtime desc')->page(I('get.p').',42')->select();
		    $count = $Model->where($condition)->count();// get count of records
        }else
        {
            $list = $Model->where("id>=0")->order('regtime desc')->page(I('get.p').',42')->select();
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
        $this->display(T('mgr/customers_list'));	
		//print("list");
		
		
    }
    public function customerdetail()
    {
        $data['id'] = I('get.id');
        $Model = M('users');
		$content = $Model->where($data)->find();
		if(!empty($content))//exist email
		{
			$this->assign('profiles',$content);
            $this->assign('cusid',$data['id']);
            /*domain*/
            $Model = M('domainmgr');
            $domain['username'] = $content['username'];
            $domain['status'] = 'active';
		    $list = $Model->where($domain)->order('id desc')->select();
            $this->assign('domains',$list);
            /*orders*/
            $Model = M('order');
            $order['username'] = $content['username'];
            $list = $Model->where($order)->order('issuedate desc')->select();
            $this->assign('orders',$list);
			$this->display(T('mgr/customers_detail'));
            
			
		}else
        {
            R('Customer/customerlist');
        }
        
        
    }
    public function updateprofile()
    {
        $data_['username'] = I('post.username');
        $id = I('get.id');
        if(empty($data_['username']))
        {
            R('Customer/customerlist');
        }else
        {
            $Model = M('users');
            $content = $Model->where($data_)->find();
            if(!empty($content))
            {
                $data['username'] = $data_['username'];
                $data['email'] = I('post.email','','htmlspecialchars');//get email
                $data['password'] = I('post.password','','htmlspecialchars');//get email
                $data['firstname'] = I('post.firstname','','htmlspecialchars');//get firstname
                $data['lastname'] = I('post.lastname','','htmlspecialchars');//get firstname
                $data['company'] = I('post.company','','htmlspecialchars');//get firstname
                $data['jobtitle'] = I('post.jobtitle','','htmlspecialchars');//get firstname
                //$data['question'] = I('post.question','','htmlspecialchars');//get firstname
                //$data['answer'] = I('post.answer','','htmlspecialchars');//get firstname
                $data['address1'] = I('post.address1','','htmlspecialchars');//get firstname
                $data['address2'] = I('post.address2','','htmlspecialchars');//get firstname
                $data['city'] = I('post.city','','htmlspecialchars');//get firstname
                $data['state'] = I('post.state','','htmlspecialchars');//get firstname
                $data['postcode'] = I('post.postcode','','htmlspecialchars');//get firstname
                $data['country'] = I('post.country','','htmlspecialchars');//get firstname
                $data['phone'] = I('post.phone','','htmlspecialchars');//get firstname
                $data['fax'] = I('post.fax','','htmlspecialchars');//get firstname
                $data['overdue'] = I('post.overdue','','htmlspecialchars');//get firstname
                $data['taxexemption'] = 'NO';//get firstname
                $data['currency'] = I('post.currency','','htmlspecialchars');//get firstname
                $data['balance'] = I('post.amount','','htmlspecialchars');//get firstname
                $data['status'] = I('post.status','','htmlspecialchars');//get firstname
                $Model->where($data_)->save($data);
                $this->success('Update profile successfully!',U('Customer/customerdetail?id='.$id.''),1);
            }else
            {
                R('Customer/customerlist');
            }
        }
        
    }
    public function customeradd()//show page
    {
        $this->display(T('mgr/customers_add'));
    }
    public function customernew()
    {
        $data_['username'] = I('post.username');
        $Model = M('users');
        $content = $Model->where($data_)->find();
        if(empty($content))
        {
            $data['username'] = $data_['username'];
            $data['email'] = I('post.email','','htmlspecialchars');//get email
            $data['password'] = I('post.password','','htmlspecialchars');//get email
            $data['firstname'] = I('post.firstname','','htmlspecialchars');//get firstname
            $data['lastname'] = I('post.lastname','','htmlspecialchars');//get firstname
            $data['company'] = I('post.company','','htmlspecialchars');//get firstname
            $data['jobtitle'] = I('post.jobtitle','','htmlspecialchars');//get firstname
            //$data['question'] = I('post.question','','htmlspecialchars');//get firstname
            //$data['answer'] = I('post.answer','','htmlspecialchars');//get firstname
            $data['address1'] = I('post.address1','','htmlspecialchars');//get firstname
            $data['address2'] = I('post.address2','','htmlspecialchars');//get firstname
            $data['city'] = I('post.city','','htmlspecialchars');//get firstname
            $data['state'] = I('post.state','','htmlspecialchars');//get firstname
            $data['postcode'] = I('post.postcode','','htmlspecialchars');//get firstname
            $data['country'] = I('post.country','','htmlspecialchars');//get firstname
            $data['phone'] = I('post.phone','','htmlspecialchars');//get firstname
            $data['fax'] = I('post.fax','','htmlspecialchars');//get firstname
            $data['overdue'] = I('post.overdue','','htmlspecialchars');//get firstname
            $data['taxexemption'] = I('post.taxexemption','','htmlspecialchars');;//get firstname
            $data['currency'] = I('post.currency','','htmlspecialchars');//get firstname
            $data['balance'] = I('post.amount','','htmlspecialchars');//get firstname
            $data['status'] = I('post.status','','htmlspecialchars');//get firstname
            $Model->data($data)->add();
            $this->success('Add a new customer successfully!',U('Customer/customeradd'),1);
        }
        else
        {
            $this->error('User has existed already!', U('Customer/customeradd'),2);
            
        }
        
    }
}