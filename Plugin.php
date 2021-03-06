<?php namespace Beysong\Wechat;

use System\Classes\PluginBase;
use Illuminate\Foundation\AliasLoader;

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
        // Register aliases
        $alias = AliasLoader::getInstance();
        $alias->alias('LaravelWechat', 'Overtrue\LaravelWechat\Facade');
    }
}
