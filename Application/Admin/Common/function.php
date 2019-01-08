<?php
/**
 *
 * @params array $array target array
 * @params string $field field
 * @params string $sort flag SORT_DESC ；SORT_ASC
 */
function arraySequence($array, $field, $sort = 'SORT_DESC')
{
    $arrSort = array();
    foreach ($array as $uniqid => $row) {
        foreach ($row as $key => $value) {
            $arrSort[$key][$uniqid] = $value;
        }
    }
    array_multisort($arrSort[$field], constant($sort), $array);
    return $array;
}
/*

  0. guest have no paid gurrentee
  1. guest have paid gurrentee
  2. guest have pain all money
  -->
  <!--
  0. no
  1. worker is doing
  2. worker has completed and no pay
  3. worker has completed and paid
*/
function getWorkerInfo($wxid){
  $ORDER = M('orders');
  /* complete orders*/
  $ordercomplete = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_workers.wxid = "'.$wxid.'" AND db_worker_order.w_state = 3')->count();
  $orderremark = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_workers.wxid = "'.$wxid.'" AND db_guest_order.g_state = 2 AND db_worker_order.w_state = 3')->avg('db_guest_order.remark');
  $income = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_workers.wxid = "'.$wxid.'" AND db_worker_order.w_state = 3')->sum('db_worker_order.w_payment');
  /* onging  */
  $orderonging = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_workers.wxid = "'.$wxid.'" AND db_worker_order.w_state = 1')->count();
  /* onging  */
  $orderunpaid = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_workers.wxid = "'.$wxid.'" AND db_worker_order.w_state = 2')->count();
  /* order all  */
  $orderall = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_workers.wxid = "'.$wxid.'"')->count();
  return array($ordercomplete, $orderremark, $income,$orderonging,$orderunpaid,$orderall);
}
/*
*
*
$v['ordercomplete'] = $ordercomplete;//$ordercomplete;
$v['orderonging'] = $orderonging;//$orderonging;
$v['orderunpaid'] = $orderunpaid;//$order unpaid;
$v['orderall'] = $orderall;//$orderonging + $ordercomplete;
$v['income'] = $income;
$v['remark'] = round($orderremark,2);
*/
function Recommand($data){

  $datasql = "";
  foreach($data as $v ){
    if($datasql == ""){
      $datasql = '(db_worker_tech.techid = '.$v["email"];//$v["email"];
    }else{
      $datasql = $datasql." OR db_worker_tech.techid = ".$v["email"];
    }
  }
  if($datasql != ""){
    $datasql = $datasql.")";
  }
  //print($datasql);
  /*
  * get dataset from workers in technology
  */
  $workerOld = array();
  $workerNew = array();
  if(!empty($datasql))
  {
    $Model = M('workers');
    $workers = $Model->order('addtime asc')->select();
    //dump($techstr);
    $worklist = [];
    foreach($workers as $k=>$v){
      $MM = M('worker_tech');

      $techeslist = $MM->join('left join db_technologies on db_worker_tech.techid = db_technologies.techid')->field('db_worker_tech.wxid')->where('db_worker_tech.wxid = "'.$v['wxid'].'" and '.$datasql.'')->select();
      //dump($techeslist);
      if($techeslist != NULL){
        array_push($worklist ,$techeslist[0]['wxid']);
      }
    }
    //dump($worklist);
    //$workliststr = rtrim($workliststr,",");
    //dump($workliststr);
    //$max_ordercomplete = 0;
    //$min_ordercomplete = 0;
    $max_ordercomplete = 0;
    $max_orderonging = 0;;//$orderonging;
    $max_orderunpaid = 0;;//$order unpaid;
    $max_orderall = 0;//$orderonging + $ordercomplete;
    $max_income = 0;
    $max_remark = 0;

    $min_ordercomplete= 0;
    $max_orderonging = 0;//$orderonging;
    $min_ordercomplete_orderunpaid = 0;//$order unpaid;
    $min_orderall = 0;//$orderonging + $ordercomplete;
    $min_income = 0;
    $min_remark = 0;
    foreach($workers as $k=>$v){
      $MM = M('worker_tech');
      if(in_array($v['wxid'],$worklist)){
        $teches = $MM->join('left join db_technologies on db_worker_tech.techid = db_technologies.techid')->field('db_worker_tech.techid,db_technologies.content')->where('db_worker_tech.wxid = "'.$v['wxid'].'"')->select();
        $techsss = "";
        foreach($teches as $c){
          $techsss = $techsss.'#'.$c["techid"].'#';
        }
        $v['techarr'] = $teches;
        #print_r($teches);
        $ORDER = M('orders');
        /* complete orders*/
        $ordercomplete = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_workers.wxid = "'.$v['wxid'].'" AND db_worker_order.w_state = 3')->count();
        $orderremark = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_workers.wxid = "'.$v['wxid'].'" and db_worker_order.w_state = 3 AND db_guest_order.g_state = 2')->avg('db_guest_order.remark');
        $income = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_workers.wxid = "'.$v['wxid'].'" AND db_worker_order.w_state = 3')->sum('db_worker_order.w_payment');
        /* onging  */
        $orderonging = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_workers.wxid = "'.$v['wxid'].'" AND db_worker_order.w_state = 1')->count();
        /* onging  */
        $orderunpaid = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_workers.wxid = "'.$v['wxid'].'" AND db_worker_order.w_state = 2')->count();
        /* order all  */
        $orderall = $ORDER->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_workers.wxid = "'.$v['wxid'].'"')->count();

        $v['ordercomplete'] = $ordercomplete;//$ordercomplete;
        /*if($ordercomplete >$max_ordercomplete){
          $max_ordercomplete = $ordercomplete;
        }
        if($min_ordercomplete == 0 || ($ordercomplete < $min_ordercomplete && $ordercomplete > $min_ordercomplete)){
          $min_ordercomplete = $ordercomplete;
        }*/
        $v['orderonging'] = $orderonging;//$orderonging;
        $v['orderunpaid'] = $orderunpaid;//$order unpaid;
        $v['orderall'] = $orderall;//$orderonging + $ordercomplete;
        $v['income'] = $income;


        $v['remark'] = round($orderremark,2);

        $workers[$k] = $v;
        $ffc = 0;
        foreach($data as $v ){
          if(strstr($techsss, "#".$v["email"]."#") === false){
            $ffc = 1;
            break;
          }
        }
        if($ffc == 0){
          //print($techsss);
          if($workers[$k]["status"] == 0 && $workers[$k]["ordercomplete"] > 0 ){
            if($ordercomplete >$max_ordercomplete){
              $max_ordercomplete = $ordercomplete;
            }
            if($min_ordercomplete == 0 || ($ordercomplete < $min_ordercomplete && $ordercomplete > 0)){
              $min_ordercomplete = $ordercomplete;
            }
            if($orderonging >$max_orderonging){
              $max_orderonging = $orderonging;
            }
            if($min_orderonging == 0 || ($orderonging < $min_orderonging && $orderonging > 0)){
              $min_orderonging = $orderonging;
            }
            if($orderunpaid >$max_orderunpaid){
              $max_orderunpaid = $orderunpaid;
            }
            if($min_orderunpaid == 0 || ($orderunpaid < $min_orderunpaid && $orderunpaid > 0)){
              $min_orderunpaid = $orderunpaid;
            }
            if($orderall >$max_orderall){
              $max_orderall = $orderall;
            }
            if($min_orderall == 0 || ($orderall < $min_orderall && $orderall > 0)){
              $min_orderall = $orderall;
            }
            if($income >$max_income){
              $max_income = $income;
            }
            if($min_income == 0 || ($income < $min_income && $income > 0)){
              $min_income = $income;
            }
            if($workers[$k]['remark'] >$max_remark){
              $max_remark = $workers[$k]['remark'];
            }
            if($min_remark == 0 || ($workers[$k]['remark'] < $min_remark && $workers[$k]['remark'] > 0)){
              $min_remark = $workers[$k]['remark'];
            }
            //print("<br>");
            //print($workers[$k]['remark']);
            array_push($workerOld,$workers[$k]);
          }
          if($workers[$k]["status"] == 0 && $workers[$k]["ordercomplete"] == 0 ){
            array_push($workerNew,$workers[$k]);
          }

        }

        //array_push($workeroutput ,$workers[$k]);
      }
    }
  }
  //print_r($workerOld);
  /*print("<br>=== len =<br>");
  print(count($workerOld));
  print("<br>=== len =<br>");
  #print("<br>====<br>");
  #print_r($workerNew);
  print($max_ordercomplete);
  print("<br>=max=<br>");
  print($min_ordercomplete);
  print("<br>=min=<br>");
  print($max_orderonging);
  print("<br>====<br>");
  print($min_orderonging);
  print("<br>====<br>");
  print($max_orderunpaid);
  print("<br>====<br>");
  print($min_orderunpaid);
  print("<br>====<br>");
  print($max_orderall);
  print("<br>====<br>");
  print($min_orderall);
  print("<br>====<br>");
  print($max_income);
  print("<br>====<br>");
  print($min_income);
  print("<br>====<br>");
  print($max_remark);
  print("<br>====<br>");
  print($min_remark);
  print("<br>====<br>");
  */
  /*
  * recommand old worker
  * total score  ?
  * ordercomplete  10
  * orderonging >= 3 -10
                >=2  -6
                >=1  -2
                >=0  +10
  * orderunpaid passed
  * orderall  passed
  * remark    15
  * attr remark 5
  * fresh    60*10 / today - recent orders time
  * work type: stu v2ex qq
  */
  $rate_ordercomplete = 10;
  $rate_remark = 15;
  foreach($workerOld as $k=>$v){
    /* cal ordercomplete */
    $diff_ordercomplete = 1;
    $mark_ordercomplete = 0;
    if($max_ordercomplete == $min_ordercomplete ){
      $diff_ordercomplete = 1;
      $mark_ordercomplete = 1*$rate_ordercomplete;
    }else{
      $diff_ordercomplete = $max_ordercomplete - $min_ordercomplete;
      $mark_ordercomplete = ($v['ordercomplete']-$min_ordercomplete)*$rate_ordercomplete/$diff_ordercomplete;
    }
    $workerOld[$k]["mark_ordercomplete"] = $mark_ordercomplete;
    //print("<br>=== mark complete =<br>");
    /* cal orderonging */
    $mark_orderongoing = 10;
    if($v['orderonging']>=3 ){
      $mark_orderongoing = -10;
    }else if($v['orderonging'] == 2){
      $mark_orderongoing = -6;
    }else if($v['orderonging'] == 1){
      $mark_orderongoing = -2;
    }else if($v['orderonging'] == 0){
      $mark_orderongoing = +5;
    }
    $workerOld[$k]["mark_orderongoing"] = $mark_orderongoing;
    /* cal order remark*/
    $diff_remark = 1;
    $mark_remark = 0;
    if($max_remark == $min_remark ){
      $diff_remark = 1;
      $mark_remark = 1*$rate_remark;
    }else{
      $diff_remark = $max_remark - $min_remark;
      $mark_remark = ($v['remark']-$min_remark)*$rate_remark/$diff_remark;
    }
    $workerOld[$k]["mark_remark"] = $mark_remark;
    /*$attrsche = ["v2ex","student","cs","abroad","other"];*/
    $attrsche = ["v2ex","student","cs","abroad","other"];
    $attr_mark = 0;
    foreach($attrsche as $v){
        if(strstr($workerOld[$k]["attrs"], $v) != false){
            $attr_mark = $attr_mark + 1;
        }
    }
    $workerOld[$k]["mark_attrs"] = $attr_mark;
    /* total */
    $workerOld[$k]["mark_sum"] = $workerOld[$k]["mark_ordercomplete"] + $workerOld[$k]["mark_orderongoing"] +$workerOld[$k]["mark_remark"] + $workerOld[$k]["attrs"];

    $workerOld[$k]["mark_ordercomplete"] = round($workerOld[$k]["mark_ordercomplete"],1);
    $workerOld[$k]["mark_orderongoing"] = round($workerOld[$k]["mark_orderongoing"],1);
    $workerOld[$k]["mark_remark"] = round($workerOld[$k]["mark_remark"],1);
    $workerOld[$k]["mark_sum"] = round($workerOld[$k]["mark_sum"],1);


  }

  $workerOld = arraySequence($workerOld, "mark_sum", $sort = 'SORT_DESC');
  $workerNew = arraySequence($workerNew, "addtime", $sort = 'SORT_DESC');
  //print_r($workerOld);
  /*  */
  return array($workerOld,$workerNew);


}
