<?php
namespace Admin\Controller;
use Think\Controller;
class DashboardController extends CommonController {
    public function index()
    {
      $uid = cookie('admin_uid');
      $username = cookie('admin_username');

      $data['uid'] = $uid;
      $Model = M('admin');
      $content = $Model->where($data)->find();
      if(!empty($content) )//exist
      {
        /* cal total complete income */

        /**/
        $year = date("Y");
        $res = [];
        $res = getAllData();
        $cydata = [];
        $cydata = getYearData($year);
        $this->assign('revenues',$res[0]);
        $this->assign('salary',$res[1]);
        $this->assign('profit',$res[2]);
        $this->assign('revenuesarr',$res[3]);
        $this->assign('ordernum',$res[4]);


        $this->assign('ongoingrevenues',$res[5]);
        $this->assign('ongoingsalary',$res[6]);
        $this->assign('ongoingprofit',$res[7]);
        $this->assign('ongoingrevenuesarr',$res[8]);
        $this->assign('profitavg',$res[9]);
        $this->assign('ongoingunpaid',$res[10]);
        $this->assign('ongoingdoing',$res[11]);
        $this->assign('ongoingunset',$res[12]);

        $this->assign('cydata',$cydata);//current day data info

        //print_r($cydata);

        $fromdate = date("Y-m-d");
        $this->assign('today',$fromdate);
        $this->assign('todaymonth',date("Y-m"));
        $this->assign('todayyear',$year);
        /* display */

        /*
        month show
        */



        $this->display(T('admin/index'));
      }else
      {
      	$this->error(C('LOGIN_ERROR'), U('Login/index'),3);
      }
    }

    public function getDayData(){
      $daydata = I('post.daytime','','htmlspecialchars');//
      $datas = getDayData($daydata);
      $this->ajaxReturn($datas);
    }


    public function getMonthData(){
        $daydata = I('post.daytime','','htmlspecialchars');//
        $res = getMonthData($daydata);
        $this->ajaxReturn($res);
    }



    public function getYearData(){
      /*year show*/
      $year =  I('post.daytime','','htmlspecialchars');//
      $res = [];
      $res = getYearData($year);
      $this->ajaxReturn($res);
    }
    public function showDataAnalysis(){
        $year = date("Y");
        $res = [];
        $res = getAllData();
        $cydata = [];
        $cydata = getYearData($year);
        $this->assign('revenues',$res[0]);
        $this->assign('salary',$res[1]);
        $this->assign('profit',$res[2]);
        $this->assign('revenuesarr',$res[3]);
        $this->assign('ordernum',$res[4]);


        $this->assign('ongoingrevenues',$res[5]);
        $this->assign('ongoingsalary',$res[6]);
        $this->assign('ongoingprofit',$res[7]);
        $this->assign('ongoingrevenuesarr',$res[8]);
        $this->assign('profitavg',$res[9]);
        $this->assign('ongoingunpaid',$res[10]);
        $this->assign('ongoingdoing',$res[11]);
        $this->assign('ongoingunset',$res[12]);

        $this->assign('cydata',$cydata);//current day data info

        //print_r($cydata);

        $fromdate = date("Y-m-d");
        $this->assign('today',$fromdate);
        $this->assign('todaymonth',date("Y-m"));
        $this->assign('todayyear',$year);
       $this->display(T('admin/dashbord_data_analysis'));

    }
    public function getDayToDay(){
      $fromdate = I('post.fromdate','','htmlspecialchars');//
      $todate = I('post.todate','','htmlspecialchars');//
      $fromdate = "2018-01-01";
      $todate = "2019-01-20";
      $res = getDayToDay($fromdate,$todate);
      
      $this->ajaxReturn($res);
    }
    public function getEachMonth(){
      $fromdate = I('post.fromdate','','htmlspecialchars');//
      $todate = I('post.todate','','htmlspecialchars');//
      $fromdate = "2018-01-01";
      $todate = "2018-01-30";
      $res = getDayToDay($fromdate,$todate);
      $fromdate = "2019-01-01";
      $todate = "2019-01-30";
      $res1 = getDayToDay($fromdate,$todate);
      
      $this->ajaxReturn(array_merge($res,$res1));
    }
    public function getMonths(){
        $fy = "2018";
        $ty = "2019";
        $res = getMonthsData($fy,$ty);
        $this->ajaxReturn($res);
    }
}
