<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUsersTable.
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('username', 50)->unique()->default('')->comment('用户名');
            $table->string('email')->default('')->comment('登录邮箱');
            $table->dateTime('email_verified_at')->nullable()->comment('邮箱验证时间');
            $table->string('password', 60)->default('')->comment('登录密码');
            $table->dateTime('password_changed_at')->nullable()->comment('密码最后修改时间');
            $table->string('mobile')->default('')->comment('绑定手机号');
            $table->string('mobile_verified_at')->default('')->comment('手机号验证时间');
            $table->string('avatar')->default('')->comment('头像地址');
            $table->unsignedTinyInteger('active')->default(0)->comment('是否启用');
            $table->dateTime('last_login_at')->nullable()->comment('最后登录时间');
            $table->string('last_login_ip')->nullable();
            $table->rememberToken()->comment('remember me token');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
