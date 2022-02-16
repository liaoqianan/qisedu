<?php


namespace app\admin\controller;


use think\Db;

class Payment extends Base
{
    public function index() {
        return view("Payment/index");
    }

    public function ajaxGetIndex() {
        $start = input('start', '0', 'trim');
        $limit = input('length', '20', 'trim');
        $name = input('get.name/s');

        $where = ['a.status'=>1];
        if($name != '')
        {
            $where['b.nickname|b.id|b.mobile'] = ['like', '%'.$name.'%'];
        }

        $total = model('Payment')
            ->alias('a')
            ->join('User b','a.user_id = b.id','left')
            ->where($where)
            ->count();
        $list = model('Payment')
            ->alias('a')
            ->join('User b','a.user_id = b.id','left')
            ->join('course c','a.type_id = c.id','left')
            ->field('a.*,b.nickname,b.mobile,b.headimg,c.title,c.type')
            ->where($where)
            ->order('id desc')
            ->limit($start, $limit)
            ->select();

        foreach ($list as &$item){
            $item['add_time'] = date('Y-m-d H:i:s',$item['pay_time']);
        }

        $data = array(
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $list
        );
        return json($data);
    }

    public function add()
    {
        $column = model('course')->where(['is_del'=>0])->field('id,title')->select();
        if (request()->isAjax()){
            $data = input('post.');
            if (!$data['mobile'] || !$data['column_id']){
                return $this->ajaxError('手机号码或课程不能为空');
            }
            $uid = db('user')->where('mobile',$data['mobile'])->where('status',0)->find();
            if (!$uid){
                $this->ajaxError('该用户不存在或被拉黑');
            }
            $res = db('user_course')->where('course_id',$data['column_id'])->where('user_id',$uid['id'])->where('is_del',0)->find();
            if ($res){
                return $this->ajaxError('该用户已购买此课程');
            }

            Db::startTrans();
            $Payment = db('Payment')->insert([
                'user_id' => $uid['id'],
                'money' => 0.00,
                'type' => 1,
                'pay_type' => 5,
                'type_id' => $data['column_id'],
                'payment_id' => $this->getPaymentId(),
                'status' => 1,
                'add_time' => time(),
                'pay_time' => time(),
            ]);
            if ($Payment){
                $user_course = db('user_course')->insert([
                    'user_id' => $uid['id'],
                    'type' => 1,
                    'course_id' => $data['column_id'],
                    'pay_time' => time(),
                ]);
                if ($user_course){
                    Db::commit();
                    return $this->ajaxSuccess('添加成功');
                }else{
                    Db::rollback();
                }
            }
        }
        return view("Payment/add",['column'=>$column]);
    }

    /**
     *
     * 获取订单编号
     *
     */
    private function getPaymentId(){
        $payment_id = date('YmdHis').rand(1000,9999);

        $order = model('Payment')->where('payment_id',$payment_id)->find();
        if (!empty($order)){
            return $this -> getPaymentId();
        }

        return $payment_id;
    }
}