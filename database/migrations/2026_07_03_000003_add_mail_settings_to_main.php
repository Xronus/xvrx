<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main', function (Blueprint $table) {
            if (! Schema::hasColumn('main', 'mail_password_reset_enabled')) {
                $table->boolean('mail_password_reset_enabled')->default(true)->after('logo_path');
            }
            if (! Schema::hasColumn('main', 'mail_from_name')) {
                $table->string('mail_from_name', 100)->nullable()->after('mail_password_reset_enabled');
            }
            if (! Schema::hasColumn('main', 'mail_reset_subject')) {
                $table->string('mail_reset_subject', 180)->nullable()->after('mail_from_name');
            }
            if (! Schema::hasColumn('main', 'mail_reset_body')) {
                $table->text('mail_reset_body')->nullable()->after('mail_reset_subject');
            }
        });

        DB::table('main')->updateOrInsert(
            ['id' => 1],
            [
                'mail_password_reset_enabled' => true,
                'mail_from_name' => config('app.name'),
                'mail_reset_subject' => 'Сброс пароля',
                'mail_reset_body' => 'Вы получили это письмо, потому что был запрошен сброс пароля для вашего аккаунта.',
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        Schema::table('main', function (Blueprint $table) {
            $columns = [
                'mail_password_reset_enabled',
                'mail_from_name',
                'mail_reset_subject',
                'mail_reset_body',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('main', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
