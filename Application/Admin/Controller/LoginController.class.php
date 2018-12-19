<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
  public function index(){
      $this->display(T('admin/login'));
  }
	public function checkLog()
	{
		cookie('admin_uid',null);
		$data['username']= I('post.username','','htmlspecialchars');//get name
		$data['password'] = md5($data['username'].I('post.password','','htmlspecialchars'));//get name
		$data['code'] = I('post.code','','htmlspecialchars');//get code
		$Model = M('admin');
		//$data['uid'] = uniqid('cs_');
		//print_r($data);
		$content = $Model->field('uid,username')->where($data)->find();
		//dump($content);
		if(!empty($content))//exist
		{
			//echo "hahah";
			cookie('admin_uid',$content['uid'],36000);
            cookie('admin_username',$content['username'],36000);
            $this->success(C('LOGIN_SUCCESS'), U("Dashboard/index"),3);


		}else
		{
			cookie('admin_uid',null);
			$this->error(C('LOGIN_ERROR'), U('Login/index'),3);
		}
	}
	public function logout()
  {
		cookie('admin_uid',null);
		//$this->show('login');
    	//$this->display(T('homepage/index'));
		R('Login/index');
  }
}
