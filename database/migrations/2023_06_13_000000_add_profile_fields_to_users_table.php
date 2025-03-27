<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
            $table->string('phone')->nullable()->after('avatar');
            $table->string('job_title')->nullable()->after('phone');
            $table->text('address')->nullable()->after('job_title');
            $table->text('bio')->nullable()->after('address');
            $table->string('language')->default('fr')->after('bio');
            $table->string('theme')->default('light')->after('language');
            $table->boolean('email_notifications')->default(true)->after('theme');
            $table->boolean('browser_notifications')->default(false)->after('email_notifications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'phone',
                'job_title',
                'address',
                'bio',
                'language',
                'theme',
                'email_notifications',
                'browser_notifications',
            ]);
        });
    }
} 