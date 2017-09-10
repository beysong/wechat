<?php namespace Beysong\Wechat\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateWechatUsersTable extends Migration
{
    public function up()
    {
        Schema::table('beysong_wechat_users', function($table)
        {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->integer('user_id')->unsigned();
          $table->string('nickname')->nullable();
          $table->string('openid')->unique();
          $table->string('sex')->nullable();
          $table->string('province')->nullable();
          $table->string('city')->nullable();
          $table->string('country')->nullable();
          $table->string('headimgurl')->nullable();
          $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('beysong_wechat_users');
    }
}
