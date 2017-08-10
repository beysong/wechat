<?php namespace Beysong\Wechat\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class AddWechatId extends Migration
{
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->string('wechatid', 100)->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn(['wechatid']);
        });
    }
}
