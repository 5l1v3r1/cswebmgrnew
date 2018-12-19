<?php
namespace Admin\Controller;
use Think\Controller;
class ProfileController extends CommonController {
    public function index()
    {
		$uid = cookie('admin_uid');
		$username = cookie('admin_username');

		$data['uid'] = $uid;
		$Model = M('admin');
		$content = $Model->where($data)->find();
		if(!empty($content) )//exist
		{
			$nowtime = date('Y-m-d H:i:s',time());
			//echo $nowtime;
			$this->assign('username',$username);
			$this->assign('role',"administrator");
			$this->assign('description',"This is administrator");
			$this->assign('nowtime',$nowtime);

      
			//$this->display(T('admin/index'));
		}else
		{
			$this->error(C('LOGIN_ERROR'), U('Login/index'),3);
		}


    }
}
