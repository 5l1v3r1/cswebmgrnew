<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
  public function index(){
      $this->display(T('admin/login'));
  }
	public function checkLog()
	{
    $flag = checkSession();
		//dump($content);
		if($flag == 1)//exist
		{
            $this->success(C('LOGIN_SUCCESS'), U("Dashboard/index"),3);


		}else
		{
			$this->error(C('LOGIN_ERROR'), U('Login/index'),3);
		}
	}
	public function logout()
	{
		session('admin_uid',null);
		//$this->show('login');
		//$this->display(T('homepage/index'));
		R('Login/index');
	}
}
