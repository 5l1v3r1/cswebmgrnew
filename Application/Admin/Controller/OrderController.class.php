<?php
namespace Admin\Controller;
use Think\Controller;
class OrderController extends CommonController {
	public function orderlist()
	{
		$pp = 0;
		if(isset($_GET["p"])){
			$pp = $_GET["p"];
		}
		$flag =3;
		if(isset($_GET["flag"])){
			$flag =I('get.flag');;
		}
		//$fromdate = date('Y-m-d',strtotime('1016-01-30'));//
		//$todate = date('Y-m-d',strtotime('3016-01-30'));//
		/*$fromdate = "1000-10-10 00:00:00";
		//echo $fromdate;
		$todate = "3000-10-10 00:00:00";
		$newfrom = "";
		$newto = "";
		if(isset($_GET["fromdate"]) && $_GET["fromdate"] != ""){
			$fromdate = $_GET["fromdate"];
			$newfrom = $fromdate;

		}
		if(isset($_GET["todate"]) && $_GET["todate"] != ""){
			$todate = $_GET["todate"];
			$newto = $todate;
		}*/
		//echo $todate;
		$se_condition = "";
		$se_conditionall = "";
		$search = "";
		if(!empty(I('get.search'))){
			$search = I('get.search');
			//print_r($search);
			$se_condition = 'AND (db_guests.wxid like "%'.$search.'%" OR db_guests.wxname Like "%'.$search.'%" OR db_workers.wxid like "%'.$search.'%" OR db_workers.wxname Like "%'.$search.'%")';
			$se_conditionall = '(db_guests.wxid like "%'.$search.'%" OR db_guests.wxname Like "%'.$search.'%" OR db_workers.wxid like "%'.$search.'%" OR db_workers.wxname Like "%'.$search.'%")';
			// 赋值分页输出
			//print($se_condition);

		}
		$Model = M('orders');
		$orderinfolist = [];
		$count = 0;
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
		switch($flag){
			case 1:
				//echo "completed orders";
				$orderinfolist = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_guest_order.g_state = 2 AND db_worker_order.w_state = 3 '.$se_condition)->order('db_orders.createtime desc')->page($pp.',20')->select();
				$count = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_guest_order.g_state = 2 AND db_worker_order.w_state = 3 '.$se_condition)->count();
				break;
			case 2:
				//echo "incomplte orders";
				//echo "completed orders";
				//$todate = "2017-12-25 00:00:00";
				$orderinfolist = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('(db_guest_order.g_state != 2 OR db_worker_order.w_state != 3) '.$se_condition)->order('db_orders.createtime asc')->page($pp.',20')->select();
				$count = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('(db_guest_order.g_state != 2 OR db_worker_order.w_state != 3) '.$se_condition)->count();
				break;
			case 4:
				//echo "warning incomplte orders";
				//echo "completed orders";
				//$todate = "2017-12-25 00:00:00";
				$orderinfolist = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('(db_worker_order.w_state = 1) AND db_worker_order.w_deadline <= date_sub(curdate(),interval 1 day) '.$se_condition)->order('db_orders.createtime asc')->page($pp.',20')->select();
				$count = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('(db_worker_order.w_state = 1) AND db_worker_order.w_deadline <= date_sub(curdate(),interval 1 day) '.$se_condition)->count();
				break;
			default:
				//$orderinfolist = $Model->select();db_workers.wxname
				//$orderinfolist = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->field('db_orders.orderid')->select();
				//echo $fromdate;
				//$fromdate = "2017-12-25 00:00:00";
				//$todate = "2017-12-28 00:00:00";
				$orderinfolist = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where($se_conditionall)->order('db_orders.createtime desc')->page($pp.',20')->select();
				$count = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where($se_conditionall)->count();

				//echo "hahah";
				//echo "all";
		}
		/* ongoing orders*/

		//dump($orderinfolist);
		//echo $count;
		$Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
		$Page->setConfig('prev','last');
		$Page->setConfig('next','next');
		$Page->setConfig('first','first');
		$Page->setConfig('last','last');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
		$show = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('orders',$orderinfolist);//
		/*search default*/
		$this->assign('search[]',I('get.search'));
		$this->assign('fflag',$flag);// 赋值分页输出
		$this->assign('newfrom',$newfrom);// 赋值分页输出
		$this->assign('newto',$newto);// 赋值分页输出
		$this->display(T('admin/orders_all'));
	}
	public function orderaddpage(){
		$Model = M('workers');
		$workers = $Model->field("wxid as email,wxname as name")->where("status = 0")->order('wxname asc')->select();
		$this->assign('workers',$workers);
		/* get technology list*/
		$Model = M('technologies');
		$teches = $Model->field("techid as email,content as name")->select();
		$this->assign('teches',$teches);

		$flag =3;
		if(isset($_GET["flag"])){
			$flag =I('get.flag');
		}

		$this->assign('fflag',$flag);// 赋值分页输出
		$this->display(T('admin/orders_add'));
	}
	public function ordernew(){
		$projectname = "";
		if(!empty(I('post.projectname'))){
			// projectname is [1,2,3,4,5]
			$digitstr = "";
			$projectnum = I('post.projectname');
			foreach($projectnum as $v){
				if (ctype_digit($v)) {
					//echo "The string $testcase consists of all digits.\n";

					if ($digitstr == "") {
						$digitstr = $v;
					}else{
						$digitstr = $digitstr.",".$v;
					}

				} else {
					if($projectname == ""){
						$projectname = $v;
					}else{
						$projectname = $projectname."^".$v;
					}

				}
			}

			//print($digitstr);
			if(strlen($digitstr) >0){
				//print_r($digitstr);
				$Model = M('technologies');
				$m['techid'] = array('in',$digitstr);
				//print_r($m);
				$techinfos = $Model->field("techid,content")->where($m)->select();
				//print_r($techinfos);
				$keywordsid = "";
				foreach($techinfos as $k=>$v){
						//echo $v[content];
						$keywordsid = $keywordsid."#".$v["techid"];
						if($projectname == ""){
							$projectname = $v[content];
						}else{
							$projectname = $projectname."^".$v[content];
						}
				}
				$projectname = $projectname."$".$keywordsid;
			}

		}
		//print($projectname);
		$orderid = uniqid('cs_');
		$data['orderid'] = $orderid;
		$data['createtime'] = date('Y-m-d H:i:s',time());//
		$data['projectname'] = $projectname;//
		$data['moneytype'] = I('post.moneytype','','htmlspecialchars');//
		$data['totalprice'] = I('post.totalprice','','htmlspecialchars');//
		$data['guarantee'] = I('post.guarantee','','htmlspecialchars');//
		$data['description'] = I('post.description','','htmlspecialchars');//
		//dump($data);
		$Model = M('orders');
		$Model->data($data)->add();
		/*guest*/
		$cond['wxid'] = I('post.guest_wxid','','htmlspecialchars');//
		$guest_wxid = $cond['wxid'];
		$cell['wxname'] = I('post.guest_wxname','','htmlspecialchars');//
		$Model = M('guests');
		//dump($cond);
		$guestinfo = $Model->where($cond)->find();
		//dump($guestinfo);
		if(!empty($guestinfo)){
			//echo "nonull";
			$Model->where($cond)->save($cell);
		}else
		{
			//echo "null";
			$cell['wxid'] = $guest_wxid;
			$Model->data($cell)->add();
		}
		/*guest_order*/
		$Model = M('guest_order');
		$go['wxid'] = $guest_wxid;
		$go['orderid'] = $orderid;
		$go['g_deadline'] = I('post.g_deadtime','','htmlspecialchars');//
		$go['g_state'] = I('post.g_state','','htmlspecialchars');//
		$Model->data($go)->add();
		/* worker_order */
		$workers = I('post.wxid','','htmlspecialchars');
		$map['wxid'] = "";
		if(count($workers)>0){
			$map['wxid'] = $workers[0];
		}
		$map['orderid'] = $orderid;//
		$map['w_deadline'] = I('post.w_deadline','','htmlspecialchars');//
		$map['w_payment'] = I('post.w_payment','','htmlspecialchars');//
		$map['w_state'] = I('post.w_state','','htmlspecialchars');//

		//dump($map);
		if($map['wxid'] != ""){
			$Order = M('worker_order');
			$Order->data($map)->add();
		}
		$flag =3;
		if(isset($_GET["flag"])){
			$flag =I('get.flag');
		}
		$this->assign('fflag',$flag);// 赋值分页输出
		$this->success('Add a new order successfully!',U('Order/orderlist?flag='.$flag),1);
	}
	public function ordereditpage(){
		$orderid = I('get.orderid','','htmlspecialchars');//
		//dump($condition);
		$Model = M('orders');
		$orderinfo = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_orders.orderid =  "'.$orderid.'"' )->find();
		//dump($orderinfo);

		$Model = M('workers');
		//echo $orderinfo[wxid];

		$mmap['status'] = 0;
		$workers = $Model->field("wxid as email,wxname as name")->where($mmap)->order('wxname asc')->select();
		$this->assign('workers',$workers);
		$mmap['wxid'] = $orderinfo[wxid];
		$worker = $Model->field("wxid as email,wxname as name")->where($mmap)->select();
		$this->assign('worker',$worker);
		//print($worker);

		/* get technology list*/
		$Model = M('technologies');
		$teches = $Model->field("techid as email,content as name")->select();
		$this->assign('teches',$teches);
		/* projectname*/
		//print_r($orderinfo);
		$resstr = array();
		$resstr = explode('$', $orderinfo["projectname"]);
		//echo $resstr[1];
		$digitstr = "";
		$title ="";
		if(count($resstr)>=2){
			$techids = explode('#', $resstr[count($resstr)-1]);
			$title = $resstr[0];
			//print($techids);
			foreach($techids as $v){
				if (ctype_digit($v)) {
					//echo "The string $testcase consists of all digits.\n";

					if ($digitstr == "") {
						$digitstr = $v;
					}else{
						$digitstr = $digitstr.",".$v;
					}

				}
			}
		}else if(count($resstr) == 1){
			$title = $resstr[0];
		}

		//echo $title;
		$techinit = array();
		if(strlen($digitstr)>0){
			//echo $digitstr;
			$m['techid'] = array('in',$digitstr);
			//print_r($m);
			$techinit = $Model->field("techid as email,content as name")->where($m)->select();

		}

		if($title != ""){
			$titlearrs = explode('^', $title);
			$techtmp = array();
			if(count($techinit) == 0){
				$techtmp = array();
			}else{
				$techtmp = $techinit;
			}

			//print_r(count($techtmp));
			//echo $title;
			//print_r($titlearrs);
			//print_r($titlearrs);
			foreach($titlearrs as $v){

				if(count($techinit)>0){
					$cff = 0;
					foreach($techinit as $k){
						//echo $v;

						if($k["name"] == $v && $v !=""){
							$cff = 1;
							break;
						}
					}
					if($cff == 0){
						$item = array("email" => $v,"name" =>$v);
						array_push($techtmp,$item);
					}else{
						$cff = 0;
					}


				}else{
					//echo $v;
					if($v !=""){
						$item = array("email" => $v,"name" =>$v);
						//print($techtmp);
						array_push($techtmp,$item);
						//print_r($techtmp);

					}
				}

			}
			$techinit = $techtmp;
		}
		//print_r($techtmp);
		$this->assign('techinit',$techinit);
		$flag =3;
		if(isset($_GET["flag"])){
			$flag =I('get.flag');
		}
		$this->assign('fflag',$flag);// 赋值分页输出
		$this->assign("orderinfo",$orderinfo);
		$this->display(T('admin/orders_edit'));
		/*
		$Model = M('workers');
        $workers = $Model->select();
		$this->assign('workers',$workers);
        $this->display(T('admin/orders_add'));
				*/
	}
	public function orderupdate(){
		$projectname = "";
		if(!empty(I('post.projectname'))){
			// projectname is [1,2,3,4,5]
			$digitstr = "";
			$projectnum = I('post.projectname');
			foreach($projectnum as $v){
				if (ctype_digit($v)) {
					//echo "The string $testcase consists of all digits.\n";

					if ($digitstr == "") {
						$digitstr = $v;
					}else{
						$digitstr = $digitstr.",".$v;
					}

				} else {
					if($projectname == ""){
						$projectname = $v;
					}else{
						$projectname = $projectname."^".$v;
					}

				}
			}

			//print($digitstr);
			if(strlen($digitstr) >0){
				//print_r($digitstr);
				$Model = M('technologies');
				$m['techid'] = array('in',$digitstr);
				//print_r($m);
				$techinfos = $Model->field("techid,content")->where($m)->select();
				//print_r($techinfos);
				$keywordsid = "";
				foreach($techinfos as $k=>$v){
						//echo $v[content];
						$keywordsid = $keywordsid."#".$v["techid"];
						if($projectname == ""){
							$projectname = $v[content];
						}else{
							$projectname = $projectname."^".$v[content];
						}
				}
				$projectname = $projectname."$".$keywordsid;
			}

		}
		$orderid = I('post.orderid','','htmlspecialchars');
		$ORDER = M('orders');
		$condition['orderid'] = $orderid;
		$orderinfo = $ORDER->where($condition)->find();
		$flag =3;
		if(isset($_GET["flag"])){
			$flag =I('get.flag');
		}

		//dump($orderinfo);
		if(!empty($orderinfo)){
			$data['projectname'] = $projectname;//
			$data['moneytype'] = I('post.moneytype','','htmlspecialchars');//
			$data['totalprice'] = I('post.totalprice','','htmlspecialchars');//
			$data['guarantee'] = I('post.guarantee','','htmlspecialchars');//
			$data['description'] = I('post.description','','htmlspecialchars');//
			$ORDER->where($condition)->save($data);
			$GUEST = M('guests');
			$cond['wxid'] = I('post.guest_wxid','','htmlspecialchars');//
			$guestinfo = $GUEST->where($cond)->find();

			if(!empty($guestinfo)){
				//echo "nonull";
				$cell['wxname'] = I('post.guest_wxname','','htmlspecialchars');//
				$GUEST->where($cond)->save($cell);

				/*guest_order*/
				$GUESTORDER = M('guest_order');
				$go['wxid'] = $cond['wxid'];
				$go['orderid'] = $orderid;
				$goadd['g_deadline'] = I('post.g_deadtime','','htmlspecialchars');//
				$goadd['g_state'] = I('post.g_state','','htmlspecialchars');//
				$guestorders = $GUESTORDER->where($go)->find();
				if(!empty($guestorders)){
					$GUESTORDER->where($go)->save($goadd);
					//dump($guestinfo);
					/*worker*/
					/* worker_order */
					$workers = I('post.wxid','','htmlspecialchars');//
					$map['wxid'] = "";
					if(count($workers)>0){
						$map['wxid'] = $workers[0];
					}
					$map['orderid'] = $orderid;//
					$mapadd['w_deadline'] = I('post.w_deadline','','htmlspecialchars');//
					$mapadd['w_payment'] = I('post.w_payment','','htmlspecialchars');//
					$mapadd['w_state'] = I('post.w_state','','htmlspecialchars');//


					$WORKEROORDER = M('worker_order');

					if($map['wxid'] != ""){
						//echo $map['wxid'];
						//echo $map['orderid'];
						$mapadd['wxid'] = $map['wxid'];
						$connd['orderid'] = $map['orderid'];
						$workerorders = $WORKEROORDER->where($connd)->select();
						if(count($workerorders)>0){
							$WORKEROORDER->where($connd)->save($mapadd);
						}else{
							$mapadd['orderid'] = $map['orderid'];
							$WORKEROORDER->add($mapadd);
						}
						//dump($ii);
					}else
					{
						$map['wxid'] = I('post.oriid','','htmlspecialchars');//
						$WORKEROORDER->where($map)->delete();
					}
					$this->success('Update order #'.$orderid.' successfully!',U('Order/orderlist?flag='.$flag),1);


				}else{
					$this->success('Update order #'.$orderid.' successfully!',U('Order/orderlist?flag='.$flag),1);
				}
				//$Model->data($go)->add();

			}else
			{
				$this->success('Update order #'.$orderid.' successfully!',U('Order/orderlist?flag='.$flag),1);

			}
		}else{
			$this->success('Update order #'.$orderid.' successfully!',U('Order/orderlist?flag='.$flag),1);
		}
		/*
		$data['projectname'] = I('post.projectname','','htmlspecialchars');//
		$data['moneytype'] = I('post.moneytype','','htmlspecialchars');//
		$data['totalprice'] = I('post.totalprice','','htmlspecialchars');//
		$data['guarantee'] = I('post.guarantee','','htmlspecialchars');//
		$data['description'] = I('post.description','','htmlspecialchars');//
		*/
		//dump($data);
	}
	public function orderdelete(){
		$flag =3;
		if(isset($_GET["flag"])){
			$flag =I('get.flag');
		}
		$data['orderid'] = I('get.orderid');
		$Model = M('orders');
		$Model->where($data)->delete();
		/*if(isset($_GET["go"]) && $_GET["go"] == 1){
			//$this->error('Update order #'.$orderid.' failure!',U('Order/orderlist_ongoing'),1);
			$this->success('Delete order #'.$data['orderid'].' successfully!',U('Order/orderlist?fflag=2'),1);
		}else{
			$this->success('Delete order #'.$data['orderid'].' successfully!',U('Order/orderlist'),1);
		}*/
		$this->success('Delete order #'.$data['orderid'].' successfully!',U('Order/orderlist?flag='.$flag),1);
		//$this->success('Delete order #'.$data['orderid'].' successfully!',U('Order/orderlist'),1);
	}
	public function orderdetailpage()
	{
		$flag =3;
		if(isset($_GET["flag"])){
			$flag =I('get.flag');
		}
		$orderid = I('get.orderid');
		$Model = M('orders');
		$orderinfo = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_orders.orderid =  "'.$orderid.'"' )->find();
		//dump($orderinfo);
		$this->assign("orderinfo",$orderinfo);
		$this->assign('fflag',$flag);
		$this->display(T('admin/orders_detail'));

	}
	public function orderremark(){
		$flag =3;
		if(isset($_GET["flag"])){
			$flag =I('get.flag');
		}
		$orderid = I('get.orderid');
		$Model = M('guest_order');
		$cond['orderid'] = $orderid;
		$cell['remark'] = I('post.remarkoption');
		$Model->where($cond)->save($cell);
		$this->success('Remark order #'.$cond['orderid'].' successfully!',U('Order/orderlist?flag='.$flag),1);
	}
	public function ajaxRecommand(){
		//$data = 'ok';
		$data = I('post.data');
		//$data = array(array("email"=>"1","name"=>"php"),array("email"=>"9","name"=>"C"));


		$res = Recommand($data);

		$this->ajaxReturn($res);
	}
	public function showDataAnalysisPage(){
		
		$this->display(T('admin/orders_analysis'));
	}
	public function getWeekData(){
		//$fd = I('post.fromdate','','htmlspecialchars');//
		//$td = I('post.todate','','htmlspecialchars');//
		$fd = "2018-01-01";//
		$td = "2019-12-31";//
		if(date('w') == 0){
			$td = date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600));;
		}else{
			$td = date('Y-m-d', strtotime('-1 sunday', time()));
		}
		//echo $td;
		//date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600)); //同样使用w,以现在与周日相关天数算
		$res = [];
		$res = getWeekDataOrder($fd,$td);
		$this->ajaxReturn($res);
	}

}
