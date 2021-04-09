<?php
namespace app\admin\controller\job;

use think\Db;
use think\queue\Job;
use redis\Redis;
use app\common\utils\WechatUtils;
use app\common\utils\WeDbUtils;
use EasyWeChat\OfficialAccount\Application;
class Wechat
{

    /**
     * 微信公众号相关队列任务
     * fire方法是消息队列默认调用的方法
     * @param Job            $job      当前的任务对象
     * @param array|mixed    $data     发布任务时自定义的数据
     */
    public function fire(Job $job,$data){
        $redis = new Redis();
        if(!$data['jobname']){$job->delete();}

        if($data['jobname']=="SysncFans"){
            $isJobDone = $this->doSysncFans($data['data']);
        }
        
        if ($isJobDone) {
        
               $job->delete();
            //如果任务执行成功， 记得删除任务
            
            print("<info>Hello Job has been done and deleted"."</info>\n");
        }else{
            if ($job->attempts() > 3) {
                //通过这个方法可以检查这个任务已经重试了几次了
                print("<warn>Hello Job has been retried more than 3 times!"."</warn>\n");
                $job->delete();
            }
        }
    }
    /**
     * 根据消息中的数据进行实际的业务处理
     * @param array|mixed    $data     发布任务时自定义的数据
     * @return boolean                 任务执行的结果
     * 0-等待继续 1-确认继续 2-
     */
    private function doSysncFans($data) {
        $Wechat_tool = WechatUtils::getInstance();
        $WeDb = WeDbUtils::getInstance();
        foreach ($data as $key => $value) {
            $user = $Wechat_tool->userinfo_openid($value);
            $is_user = $WeDb->find('wechat_fans','openid = "'.$user['openid'].'"');
            if($is_user){
                $WeDb->update('wechat_fans','openid = "'.$user['openid'].'"',array(
                    // 'appid'=>
                    'unionid'=>$user['unionid'],
                    'tagid_list'=>$user['tagid_list'],
                    'subscribe'=>$user['subscribe'],
                    'nickname'=>$user['nickname'],
                    'sex'=>$user['sex'],
                    'country'=>$user['country'],
                    'province'=>$user['province'],
                    'city'=>$user['city'],
                    'language'=>$user['language'],
                    'headimgurl'=>$user['headimgurl'],
                    'subscribe_time'=>$user['subscribe_time'],
                    'remark'=>$user['remark'],
                    'subscribe_scene'=>$user['subscribe_scene'],
                    'qr_scene'=>$user['qr_scene'],
                    'qr_scene_str'=>$user['qr_scene_str'],

                ));
            }else{

                $WeDb->insertGetId('wechat_fans',array(
                    // 'appid'=>,
                    'unionid'=>$user['unionid'],
                    'openid'=>$user['openid'],
                    'tagid_list'=>$user['tagid_list'],
                    // 'is_black'=>,
                    'subscribe'=>$user['subscribe'],
                    'nickname'=>$user['nickname'],
                    'sex'=>$user['sex'],
                    'country'=>$user['country'],
                    'province'=>$user['province'],
                    'city'=>$user['city'],
                    'language'=>$user['language'],
                    'headimgurl'=>$user['headimgurl'],
                    'subscribe_time'=>$user['subscribe_time'],
                    'subscribe_at'=>date("Y-m-d H:i:s",time()),
                    'remark'=>$user['remark'],
                    'subscribe_scene'=>$user['subscribe_scene'],
                    'qr_scene'=>$user['qr_scene'],
                    'qr_scene_str'=>$user['qr_scene_str'],
                    'create_at'=>date("Y-m-d H:i:s",time()),
                ));
            }
            //return true;
            # code...
        }
    }
}