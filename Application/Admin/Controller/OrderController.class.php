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
		if(!empty(I('get.search'))){
			$search = I('get.search');
			//print_r($search);
			foreach($search as $vv){ 
				if($se_condition == ""){
					$se_condition = 'AND (db_guests.wxid like "%'.$vv.'%" OR db_guests.wxname Like "%'.$vv.'%" OR db_workers.wxid like "%'.$vv.'%" OR db_workers.wxname Like "%'.$vv.'%"';
				}else
				{
					$se_condition = $se_condition.' OR db_guests.wxid like "%'.$vv.'%" OR db_guests.wxname Like "%'.$vv.'%" OR db_workers.wxid like "%'.$vv.'%" OR db_workers.wxname Like "%'.$vv.'%"' ; 
				}
				$se_condition =  $se_condition.")";
			}
			$this->assign('search_ct',I('get.search'));// 赋值分页输出
			//print($se_condition);
			
		}
		//$search =I('post.search');
		/*$search =I('post.search');
        $Model = M('order');
        if(!empty($search))
        {
            $condition['orderID'] = array('like','%'.$search.'%');
            $condition['username'] = array('like','%'.$search.'%');
            $condition['transactionID'] = array('like','%'.$search.'%');
            $condition['status'] = array('like','%'.$search.'%');
            $condition['_logic'] = 'OR';
            $list = $Model->where($condition)->order('issuedate desc')->page(I('get.p').',42')->select();
		    $count = $Model->where($condition)->count();// get count of records
        }else
        {
            $list = $Model->where("id>=0")->order('issuedate desc')->page(I('get.p').',42')->select();
		    $count = $Model->where("id>=0")->count();// get count of records
        }
		$condition['orderID'] = array('like','%'.$search.'%');

		//print_r($list);

		$Page = new \Think\Page($count,42);// page object
		$Page->setConfig('prev','prev');
		$Page->setConfig('next','next');
		$Page->setConfig('first','first page');
		$Page->setConfig('last','last page');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER% ');
		$show = $Page->show();// page output
		$this->assign('page',$show);//
		$this->assign('list',$list);
        $this->display(T('mgr/orders_list'));
		*/
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
				$orderinfolist = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_guest_order.g_state = 2 AND db_worker_order.w_state = 3 '.$se_condition)->order('db_orders.createtime asc')->page($pp.',20')->select();
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
				$orderinfolist = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where($se_condition)->order('db_orders.createtime desc')->page($pp.',20')->select();
				$count = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where($se_condition)->count();

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
		$this->assign('fflag',$flag);// 赋值分页输出
		$this->assign('newfrom',$newfrom);// 赋值分页输出
		$this->assign('newto',$newto);// 赋值分页输出
		$this->display(T('admin/orders_all'));
	}
	public function orderaddpage(){
		$Model = M('workers');
		$workers = $Model->select();
		$this->assign('workers',$workers);
		$flag =3;
		if(isset($_GET["flag"])){
			$flag =I('get.flag');
		}
		$this->assign('fflag',$flag);// 赋值分页输出
		$this->display(T('admin/orders_add'));
	}
	public function ordernew(){

		$orderid = uniqid('cs_');
		$data['orderid'] = $orderid;
		$data['createtime'] = date('Y-m-d H:i:s',time());//
		$data['projectname'] = I('post.projectname','','htmlspecialchars');//
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
			echo "nonull";
			$Model->where($cond)->save($cell);
		}else
		{
			echo "null";
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
		$map['wxid'] = I('post.wxid','','htmlspecialchars');//
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
		//$this->success('Add a new order successfully!',U('Order/orderlist'),1);
		/*
		$Model = M('workers');
        $workers = $Model->select();
		$this->assign('workers',$workers);
        $this->display(T('mgr/orders_add'));
		*/
	}
	public function ordereditpage(){
		$orderid = I('get.orderid','','htmlspecialchars');//
		//dump($condition);
		$Model = M('orders');
		$orderinfo = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_orders.orderid =  "'.$orderid.'"' )->find();
		//dump($orderinfo);
		$this->assign("orderinfo",$orderinfo);
		$Model = M('workers');
		$workers = $Model->select();
		$this->assign('workers',$workers);
		$flag =3;
		if(isset($_GET["flag"])){
			$flag =I('get.flag');
		}
		$this->assign('fflag',$flag);// 赋值分页输出
		$this->display(T('admin/orders_edit'));
		/*
		$Model = M('workers');
        $workers = $Model->select();
		$this->assign('workers',$workers);
        $this->display(T('admin/orders_add'));
				*/
	}
	public function orderupdate(){
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
			$data['projectname'] = I('post.projectname','','htmlspecialchars');//
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
					$map['wxid'] = I('post.wxid','','htmlspecialchars');//
					$map['orderid'] = $orderid;//
					$mapadd['w_deadline'] = I('post.w_deadline','','htmlspecialchars');//
					$mapadd['w_payment'] = I('post.w_payment','','htmlspecialchars');//
					$mapadd['w_state'] = I('post.w_state','','htmlspecialchars');//


					$WORKEROORDER = M('worker_order');
					if($map['wxid'] != ""){
						$mapadd['wxid'] = $map['wxid'];
						$connd['orderid'] = $map['orderid'];
						$WORKEROORDER->where($connd)->save($mapadd);
						//dump($ii);
					}else
					{
						$map['wxid'] = I('post.oriid','','htmlspecialchars');//
						$WORKEROORDER->where($map)->delete();
					}
					$this->success('Update order #'.$orderid.' successfully!',U('Order/orderlist?flag='.$flag),1);
					/*
					if(isset($_GET["go"]) && $_GET["go"] == 1){
						//$this->error('Update order #'.$orderid.' failure!',U('Order/orderlist_ongoing'),1);
						$this->success('Update order #'.$orderid.' successfully!',U('Order/orderlist?fflag=2'),1);
					}else{
						$this->success('Update order #'.$orderid.' successfully!',U('Order/orderlist'),1);
					}
					*/
					//$this->success('Update order #'.$orderid.' successfully!',U('Order/orderlist'),1);

				}else{
					/*
					if(isset($_GET["go"]) && $_GET["go"] == 1){
						$this->error('Update order #'.$orderid.' failure!',U('Order/orderlist?fflag=2'),1);
					}else{
						$this->error('Update order #'.$orderid.' failure!',U('Order/orderlist'),1);
					}*/
					$this->success('Update order #'.$orderid.' successfully!',U('Order/orderlist?flag='.$flag),1);
					//$this->error('Update order #'.$orderid.' failure!',U('Order/orderlist'),1);
				}
				//$Model->data($go)->add();

			}else
			{
				/*if(isset($_GET["go"]) && $_GET["go"] == 1){
					$this->error('Update order #'.$orderid.' failure!',U('Order/orderlist?fflag=2'),1);
				}else{
					$this->error('Update order #'.$orderid.' failure!',U('Order/orderlist'),1);
				}*/
				$this->success('Update order #'.$orderid.' successfully!',U('Order/orderlist?flag='.$flag),1);

			}
		}else{
			/*if(isset($_GET["go"]) && $_GET["go"] == 1){
				$this->error('Update order #'.$orderid.' failure!',U('Order/orderlist?fflag=2'),1);
			}else{
				$this->error('Update order #'.$orderid.' failure!',U('Order/orderlist'),1);
			}*/
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
		$data['orderid'] = I('get.orderid');
		$Model = M('orders');
		$Model->where($data)->delete();
		/*if(isset($_GET["go"]) && $_GET["go"] == 1){
			//$this->error('Update order #'.$orderid.' failure!',U('Order/orderlist_ongoing'),1);
			$this->success('Delete order #'.$data['orderid'].' successfully!',U('Order/orderlist?fflag=2'),1);
		}else{
			$this->success('Delete order #'.$data['orderid'].' successfully!',U('Order/orderlist'),1);
		}*/
		$this->success('Delete order #'.$data['orderid'].' successfully!',U('Order/orderlist?flag=2'),1);
		//$this->success('Delete order #'.$data['orderid'].' successfully!',U('Order/orderlist'),1);
	}
	public function orderdetailpage()
	{
		$orderid = I('get.orderid');
		$Model = M('orders');
		$orderinfo = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_orders.orderid =  "'.$orderid.'"' )->find();
		//dump($orderinfo);
		$this->assign("orderinfo",$orderinfo);
		if(isset($_GET["go"])){
			$this->assign('go',1);// 赋值分页输出
		}else{
        	$this->assign('go',0);// 赋值分页输出
		}
		$this->display(T('admin/orders_detail'));

	}

	/*in complete*/
	/*
	public function orderlist_ongoing()
	{
		$pp = 0;
		if(isset($_GET["p"])){
			$pp = $_GET["p"];
		}
		$flag =2;
		//$fromdate = date('Y-m-d',strtotime('1016-01-30'));//
		//$todate = date('Y-m-d',strtotime('3016-01-30'));//
		$fromdate = "1000-10-10 00:00:00";
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
		}
		$Model = M('orders');
		$orderinfolist = [];
		$count = 0;
		$orderinfolist = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_orders.createtime >=  "'.$fromdate.' 00:00:00" AND db_orders.createtime <= "'.$todate.' 23:59:59" AND (db_guest_order.g_state != 2 OR db_worker_order.w_state != 3)')->order('db_orders.createtime asc')->page($pp.',20')->select();
		$count = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_orders.createtime >=  "'.$fromdate.' 00:00:00" AND db_orders.createtime <= "'.$todate.' 23:59:59" AND (db_guest_order.g_state != 2 OR db_worker_order.w_state != 3)')->count();
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
		//search default
		$this->assign('fflag',$flag);// 赋值分页输出
		$this->assign('newfrom',$newfrom);// 赋值分页输出
		$this->assign('newto',$newto);// 赋值分页输出
		$this->display(T('admin/orders_ongoing'));
	}
	*/
	public function orderremark(){
		$orderid = I('get.orderid');
		$Model = M('guest_order');
		$cond['orderid'] = $orderid;
		$cell['remark'] = I('post.remarkoption');
		$Model->where($cond)->save($cell);
		if(isset($_GET["go"]) && $_GET["go"] == 1){
			//$this->error('Update order #'.$orderid.' failure!',U('Order/orderlist_ongoing'),1);
			$this->success('Delete order #'.$cond['orderid'].' successfully!',U('Order/orderlist_ongoing'),1);
		}else{
			$this->success('Remark order #'.$cond['orderid'].' successfully!',U('Order/orderlist'),1);
		}
	}


}
