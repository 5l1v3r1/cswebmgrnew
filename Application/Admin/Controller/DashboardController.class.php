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
      	$nowtime = date('Y-m-d H:i:s',time());
      	//echo $nowtime;
      	$this->assign('username',$username);
      	$this->assign('role',"administrator");
      	$this->assign('description',"This is administrator");
      	$this->assign('nowtime',$nowtime);
        /* cal total complete income */

        /**/
        $res = [];
        $res = getAllData($year);
        $this->assign('revenues',$res[0]);
        $this->assign('salary',$res[1]);
        $this->assign('profit',$res[2]);
        $this->assign('revenuesarr',$res[3]);
        $this->assign('ordernum',$res[4]);

        $this->assign('ongoingrevenues',$res[5]);
        $this->assign('ongoingsalary',$res[6]);
        $this->assign('ongoingprofit',$res[7]);
        $this->assign('ongoingrevenuesarr',$res[8]);
        $fromdate = date("Y-m-d");
        $this->assign('today',$fromdate);
        $this->assign('todaymonth',date("Y-m"));
        $this->assign('todayyear',date("Y"));
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
      $ORDER = M('orders');
      $daydata = I('post.daytime','','htmlspecialchars');//
      //echo $daydata;
      /*
          today show
      */
      $fromdate = $daydata ;
      $todate = $daydata;
      $today_salary = 0;
      $today_salary = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->where('db_orders.createtime >=  "'.$fromdate.' 00:00:00" AND db_orders.createtime <= "'.$todate.' 23:59:59"')->sum('db_worker_order.w_payment');
      //print($today_salary);
      $today_revenuesarr  = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.moneytype,SUM(db_orders.totalprice) as revenues')->where('db_orders.createtime >=  "'.$fromdate.' 00:00:00" AND db_orders.createtime <= "'.$todate.' 23:59:59"')->group('db_orders.moneytype')->select();
      $today_array = [];
      $today_revenues = 0;
      foreach($today_revenuesarr as $k=>$v){
          //print_r($v);
          $Model = M('configure_exchange');
          $cc['currency'] = $v['moneytype'];
          $item = $Model->where($cc)->find();
          $v['rating'] = $item['rating'];
          $today_item[$k] = $v;
          array_push($today_array ,$today_item[$k]);
          $today_revenues = $today_revenues + $v['revenues']*$item['rating'];

      }
      //print($today_revenues);
      $datas['today_day'] = $daydata;
      $datas['today_salary'] = $today_salary;
      $datas['today_revenues'] = $today_revenues;
      $datas['today_alldata'] = $today_revenuesarr;
      $datas['today_profit'] = $today_revenues - $today_salary;
      $this->ajaxReturn($datas);
    }


    public function getMonthData(){
      $ORDER = M('orders');
      //$month =  date("Y-m");
	    $daydata = I('post.daytime','','htmlspecialchars');//
      $month =  $daydata;
      $fromdate = date('Y-m-d', strtotime($month."-01")); //月初
      $todate = date('Y-m-d', strtotime("$fromdate +1 month -1 day"));//月末
      /*  month day revenues*/
      $month_revenuesarr  = $ORDER->field('DATE_FORMAT(db_orders.createtime,"%Y-%m-%d") as createday,db_orders.moneytype,SUM(db_orders.totalprice) as revenues')->where('db_orders.createtime >=  "'.$fromdate.' 00:00:00" AND db_orders.createtime <= "'.$todate.' 23:59:59"')->group('db_orders.moneytype,DATE_FORMAT(db_orders.createtime,"%Y-%m-%d")')->select();
      //print($fromdate);
      //print($todate);
      //print_r($month_revenuesarr);
      $day_revenuearray = [];
	    $day_all = [];

      foreach($month_revenuesarr as $k=>$v){
          //print_r($v);
          $Model = M('configure_exchange');
          $cc['currency'] = $v['moneytype'];
          $item = $Model->where($cc)->find();
          $day_revenuearray[$v['createday']] = $day_revenuearray[$v['createday']] + $v['revenues']*$item['rating'];

		      $day_all[$v['createday']][$v['moneytype']] = $v['revenues'];
      }
      //print_r($day_revenuearray);
      /*  month day salary */
      $month_salaryarr  = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('DATE_FORMAT(db_orders.createtime,"%Y-%m-%d") as createday,SUM(db_worker_order.w_payment) as salary')->where('db_orders.createtime >=  "'.$fromdate.' 00:00:00" AND db_orders.createtime <= "'.$todate.' 23:59:59"')->group('DATE_FORMAT(db_orders.createtime,"%Y-%m-%d")')->select();
      //print_r($month_salaryarr);
      $day_profitarray = [];

      foreach($month_salaryarr as $k=>$v){
          //print_r($v);
          $Model = M('configure_exchange');
          $day_profitarray[$v['createday']] = $day_revenuearray[$v['createday']] - $v['salary'];
      }
      //print_r($day_profitarray);
	  $datas = [];
    $salarysum = 0;
    $revenuesum = 0;
	  foreach($month_salaryarr as $k=>$v){
		  $cell['profit'] = $day_profitarray[$v['createday']];
		  $cell['salary'] = $v['salary'];
      $salarysum = $salarysum + $v['salary'];
		  $cell['revenuearray'] = $day_revenuearray[$v['createday']];
		  $cell['createday'] = $v['createday'];
		  $cell['datas'] = $day_all[$v['createday']];
      $revenuesum = $day_revenuearray[$v['createday']] + $revenuesum;
		  array_push($datas ,$cell);
    }
    $res["createdate"] = $daydata;
    $res["salarysum"] = $salarysum;
    $res["revenuesum"]=$revenuesum;
    $res["profitsum"]=($revenuesum - $salarysum);
    $res["datas"] = $datas;
	  $this->ajaxReturn($res);
    }



    public function getYearData(){
      /*year show*/
      $ORDER = M('orders');
      $year =  I('post.daytime','','htmlspecialchars');//
      $fromdate = date('Y-m-d', strtotime($year."-01-01")); //月初
      $todate = date('Y-m-d', strtotime($year."-12-31"));//月末
      $year_revenuesarr  = $ORDER->field('DATE_FORMAT(db_orders.createtime,"%Y-%m") as createday,db_orders.moneytype,SUM(db_orders.totalprice) as revenues')->where('db_orders.createtime >=  "'.$fromdate.' 00:00:00" AND db_orders.createtime <= "'.$todate.' 23:59:59"')->group('DATE_FORMAT(db_orders.createtime,"%Y-%m"),db_orders.moneytype')->select();
      //print_r($year_revenuesarr);


      $year_revenuearray = [];
      $day_all = [];

      foreach($year_revenuesarr as $k=>$v){
          //print_r($v);
          $Model = M('configure_exchange');
          $cc['currency'] = $v['moneytype'];
          $item = $Model->where($cc)->find();
          $year_revenuearray[$v['createday']] = $year_revenuearray[$v['createday']] + $v['revenues']*$item['rating'];

          $day_all[$v['createday']][$v['moneytype']] = $v['revenues'];
      }
      //print_r($year_revenuearray);
      $year_salaryarr  = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('DATE_FORMAT(db_orders.createtime,"%Y-%m") as createday,SUM(db_worker_order.w_payment) as salary')->where('db_orders.createtime >=  "'.$fromdate.' 00:00:00" AND db_orders.createtime <= "'.$todate.' 23:59:59"')->group('DATE_FORMAT(db_orders.createtime,"%Y-%m")')->select();
      $year_profitarray = [];
      //print_r($year_salaryarr);
      foreach($year_salaryarr as $k=>$v){
          //print_r($v);
          $Model = M('configure_exchange');
          $year_profitarray[$v['createday']] = $year_revenuearray[$v['createday']] - $v['salary'];
      }
      $datas = [];
      $salarysum = 0;
      $revenuesum = 0;
  	  foreach($year_salaryarr as $k=>$v){
		$room['profit'] = $year_profitarray[$v['createday']];
		$room['salary'] = $v['salary'];
		$salarysum = $salarysum + $v['salary'];
		$room['revenuearray'] = $year_revenuearray[$v['createday']];
		$room['createday'] = $v['createday'];
		$room['datas'] = $day_all[$v['createday']];
		$revenuesum = $revenuesum  + $year_revenuearray[$v['createday']];
		array_push($datas ,$room);
      }
	  $res["createyear"] = $year;
      $res["salarysum"] = $salarysum;
      $res["revenuesum"]=$revenuesum;
      $res["profitsum"]=($revenuesum - $salarysum);
      $res["datas"] = $datas;
      $this->ajaxReturn($res);
    }
}
