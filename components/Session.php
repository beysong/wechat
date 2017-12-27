<?php namespace Beysong\Wechat\Components;

use Lang;
use Auth;
use Event;
use Flash;
use Request;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use ValidationException;
use Config;

use EasyWeChat\Foundation\Application;

class Session extends \RainLab\User\Components\Session
{
    const ALLOW_ALL = 'all';
    const ALLOW_GUEST = 'guest';
    const ALLOW_USER = 'user';

    public function componentDetails()
    {
        return [
            'name'        => 'wechat session',
            'description' => 'rainlab.user::lang.session.session_desc'
        ];
    }

    public function defineProperties()
    {
        return [
            'security' => [
                'title'       => 'rainlab.user::lang.session.security_title',
                'description' => 'rainlab.user::lang.session.security_desc',
                'type'        => 'dropdown',
                'default'     => 'all',
                'options'     => [
                    'all'   => 'rainlab.user::lang.session.all',
                    'user'  => 'rainlab.user::lang.session.users',
                    'guest' => 'rainlab.user::lang.session.guests'
                ]
            ],
            'redirect' => [
                'title'       => 'rainlab.user::lang.session.redirect_title',
                'description' => 'rainlab.user::lang.session.redirect_desc',
                'type'        => 'dropdown',
                'default'     => ''
            ]
        ];
    }

    public function getRedirectOptions()
    {
        return [''=>'- none -'] + Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
    * Executed when this component is bound to a page or layout.
    */
    public function onRun()
    {
        $redirectUrl = $this->controller->pageUrl($this->property('redirect'));
        $allowedGroup = $this->property('security', self::ALLOW_ALL);
        $isAuthenticated = Auth::check();

        $options = [
            'debug'  => true,
            'app_id' => Config::get('beysong.wechat::app_id', 50),
            'token' => Config::get('beysong.wechat::token', 500),
            'secret'  => Config::get('beysong.wechat::secret', 500),
            // 'oauth' => [
            //     'scopes'   => ['snsapi_userinfo'],
            //     'callback' => 'http://www.themeshow.cn/error',
            // ],
            // 'aes_key' => null, // 可选
            'log' => [
                'level' => 'debug',
                'file'  => '/tmp/easywechat.log', // XXX: 绝对路径！！！！
            ],
            //...
        ];

        // $wechat = new Application($options);
        $wechat = app('wechat.official_account');
        $oauth = $wechat->oauth;
        // dd($oauth);
        Event::listen('Overtrue\LaravelWeChat\Events\WeChatUserAuthorized', function($event)
        {
            // dd($event);
            dd($oauth->user()->toArray());
        });
        // return;
        // if($oauth->user()){
        //     $_SESSION['wechat_user'] = $user->toArray();
        //
        //
        // }

        // try{
        //     $_SESSION['wechat_user'] = $oauth->user()->toArray();
        // }catch(Exception $e){
        //     //  return $oauth->redirect();
        // }

        if (!$isAuthenticated && $allowedGroup == self::ALLOW_USER) {
            if($this->checkWechat()){
                if (empty($_SESSION['wechat_user'])) {
                    $_SESSION['target_url'] = 'http://www.themeshow.cn/ajax';
                    return $oauth->scopes(['snsapi_userinfo'])->redirect();
                    // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
                    // $oauth->redirect()->send();
                }
            }else{
                return Redirect::guest($redirectUrl);
            }

        }
        elseif ($isAuthenticated && $allowedGroup == self::ALLOW_GUEST) {
            if($this->checkWechat()){
                $wechat = app('wechat');
            }else{
                return Redirect::guest($redirectUrl);
            }
        }

        $this->page['user'] = $this->user();
    }

    public function registerWechatUser(){

    }
    /**
    * Log out the user
    *
    * Usage:
    *   <a data-request="onLogout">Sign out</a>
    *
    * With the optional redirect parameter:
    *   <a data-request="onLogout" data-request-data="redirect: '/good-bye'">Sign out</a>
    *
    */
    public function onLogout()
    {
        $user = Auth::getUser();

        Auth::logout();

        if ($user) {
            Event::fire('rainlab.user.logout', [$user]);
        }

        $url = post('redirect', Request::fullUrl());
        Flash::success(Lang::get('rainlab.user::lang.session.logout'));

        return Redirect::to($url);
    }

    /**
    * Returns the logged in user, if available, and touches
    * the last seen timestamp.
    * @return RainLab\User\Models\User
    */
    public function user()
    {
        if (!$user = Auth::getUser()) {
            return null;
        }

        $user->touchLastSeen();

        return $user;
    }
    public function checkWechat()
    {
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')){
            //判断微信浏览器为真
            return true;
        }
        //此处为假
        return false;
    }
}
