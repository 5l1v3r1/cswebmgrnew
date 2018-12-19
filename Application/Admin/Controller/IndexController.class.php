<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
  public function index(){
    cookie('admin_uid',null);
    $this->display(T('admin/login'));
  }
}
