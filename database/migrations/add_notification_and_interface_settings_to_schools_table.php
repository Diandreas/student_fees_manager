<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Notifications
            $table->boolean('has_email_notifications')->default(true)->after('has_sms_notifications');
            $table->json('notification_settings')->nullable()->after('report_settings');
            $table->json('notification_templates')->nullable()->after('notification_settings');
            
            // Interface personnalisée
            $table->string('font_family')->nullable()->after('text_color');
            $table->string('card_style')->nullable()->after('font_family');
            $table->string('button_style')->nullable()->after('card_style');
            $table->string('layout')->nullable()->after('button_style');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'has_email_notifications',
                'notification_settings',
                'notification_templates',
                'font_family',
                'card_style',
                'button_style',
                'layout',
            ]);
        });
    }
}; 