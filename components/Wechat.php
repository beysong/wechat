<?php namespace Beysong\Wechat\Components;

// use Beysong\Proevent\Models\Event as BeysongEvent;
// use Beysong\Proevent\Models\Order as Order;
// use Beysong\Proevent\Models\OrderDetail as OrderDetail;
// use Beysong\Proevent\Models\Ticket as Ticket;
use Config;
use EasyWeChat\Foundation\Application;

require_once('vendor/autoload.php');

class Wechat extends \Cms\Classes\ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Wechat',
            'description' => 'Wechat Config'
        ];
    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun()
    {
        // print_r(session('wechat.oauth_user'));
        if(\Request::wantsJson()){
            $options = [
                'debug'  => true,
                'app_id' => Config::get('beysong.wechat::app_id', 50),
                'token' => Config::get('beysong.wechat::token', 500),
                'secret'  => Config::get('beysong.wechat::secret', 500),
                // 'aes_key' => null, // 可选
                'log' => [
                    'level' => 'debug',
                    'file'  => '/tmp/easywechat.log', // XXX: 绝对路径！！！！
                ],
                //...
            ];
            $wechat = app('wechat');
            $wechat->server->setMessageHandler(function($message){
                switch ($message->MsgType) {
                    case 'event':
                        if($message->Event == 'subscribe'){

                        }
                        return '收到事件消息';
                    break;
                    case 'text':
                    return '收到文字消息';
                    break;
                    case 'image':
                    return '收到图片消息';
                    break;
                    case 'voice':
                    return '收到语音消息';
                    break;
                    case 'video':
                    return '收到视频消息';
                    break;
                    case 'location':
                    return '收到坐标消息';
                    break;
                    case 'link':
                    return '收到链接消息';
                    break;
                    // ... 其它消息
                    default:
                    return '收到其它消息';
                    break;
                }
            });

            //\Log::info('return response.');

            return $wechat->server->serve();
        }

        // $this->addJs('/plugins/beysong/proevent/assets/pingpp/src/pingpp-pc.js');
        // $this->addJs('/plugins/beysong/proevent/assets/js/orderview.js');
        //	$event_id = $this->property('events');
        //	$tickets = BeysongEvent::find($event_id)->tickets;
        //	$this->page['tickets'] = $tickets;

    }


}
