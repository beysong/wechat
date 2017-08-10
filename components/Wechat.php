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
        $options = [
            'debug'  => true,
            'app_id' => Config::get('beysong.wechat::appId', 50),
            'secret' => 'testwechat',
            'token'  => Config::get('beysong.wechat::appSecret', 500),
            // 'aes_key' => null, // 可选
            'log' => [
                'level' => 'debug',
                'file'  => '/tmp/easywechat.log', // XXX: 绝对路径！！！！
            ],
            //...
        ];
        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function($message){
            return "欢迎关注 overtrue！";
        }); 

        //\Log::info('return response.');

        return $wechat->server->serve();
        // $this->addJs('/plugins/beysong/proevent/assets/pingpp/src/pingpp-pc.js');
        // $this->addJs('/plugins/beysong/proevent/assets/js/orderview.js');
    //	$event_id = $this->property('events');
    //	$tickets = BeysongEvent::find($event_id)->tickets;
    //	$this->page['tickets'] = $tickets;

    }


}
