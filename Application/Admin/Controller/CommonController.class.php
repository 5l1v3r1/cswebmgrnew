<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;
use Org\Util\Auth;
class CommonController extends Controller
{
	public function _initialize()
	{

		if(empty(cookie('admin_uid')))
		{
			$this->error(C('LOGIN_TIPS'),U('Login/index'),3);
			return 0;
		}else if(!in_array(cookie('admin_uid'),explode(',',C('AUTH_SUPERADMIN'))))//whether is superadmin or not
		{
			$uid = cookie('admin_uid');
			$data['uid'] = $uid;
			$Model = M('admin');
			$content = $Model->where($data)->find();
			//dump($data);
			if(empty($content) )// no exist
			{
				$this->error(C('PERMISSION_DENIED_WARNING'));
			}else{
				// incomplte orders num
				$Model = M('orders');
				$cin = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('db_guest_order.g_state != 2 OR db_worker_order.w_state != 3')->count();
				$cin1 = $Model->join('left join db_worker_order on db_worker_order.orderid = db_orders.orderid')->join('left join db_workers on db_worker_order.wxid = db_workers.wxid')->join('left join db_guest_order on db_guest_order.orderid = db_orders.orderid')->join('left join db_guests on db_guest_order.wxid = db_guests.wxid')->field('db_orders.orderid,db_orders.createtime,db_guests.wxid as gwxid,db_guests.wxname as gwxname,db_orders.projectname,db_guest_order.g_deadline,db_orders.moneytype,db_orders.totalprice,db_orders.guarantee,db_guest_order.g_state,db_workers.wxid,db_workers.wxname,db_worker_order.w_deadline,db_worker_order.w_payment,db_worker_order.w_state,db_guest_order.remark as gremark,db_orders.description')->where('(db_guest_order.g_state != 2 OR db_worker_order.w_state != 3) AND curdate() >= date_sub(db_worker_order.w_deadlin,interval 1 day)')->count();
				$this->assign('cin',$cin);// incomplte orders num
				$this->assign('cin1',$cin1);// incomplte orders warning num
				/* currency types*/
				$Model = M('configure_exchange');
				$items = $Model->select();
				$this->assign('currencies',$items);
				/* get default date */
				$dateform = date("Y-m-d",time());
				$this->assign('dateform',$dateform);
			}

		}
	}
}
