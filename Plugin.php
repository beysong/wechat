<?php namespace Beysong\Wechat;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
      return [
	        'Beysong\Wechat\Components\Wechat' => 'Wechat',
    	    'Beysong\Wechat\Components\Session' => 'Session'
	    ];
    }

    public function registerSettings()
    {
    }
    public function boot(){
      \App::register('\Overtrue\LaravelWechat\ServiceProvider');
    }
}
