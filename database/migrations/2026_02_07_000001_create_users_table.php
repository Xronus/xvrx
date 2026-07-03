<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'username')) {
                    $table->string('username', 14)->unique()->after('id');
                }
                if (! Schema::hasColumn('users', 'salt')) {
                    $table->binary('salt')->after('email');
                }
                if (! Schema::hasColumn('users', 'verifier')) {
                    $table->binary('verifier')->after('salt');
                }
                if (! Schema::hasColumn('users', 'is_admin')) {
                    $table->boolean('is_admin')->default(false)->after('verifier');
                }
                if (! Schema::hasColumn('users', 'bonuses')) {
                    $table->integer('bonuses')->default(0)->after('is_admin');
                }
                if (! Schema::hasColumn('users', 'votes')) {
                    $table->integer('votes')->default(0)->after('bonuses');
                }
            });
        } else {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('username', 14)->unique();
                $table->string('email')->unique();
                $table->text('salt');
                $table->text('verifier');
                $table->string('password');
                $table->boolean('is_admin')->default(false);
                $table->integer('bonuses')->default(0);
                $table->integer('votes')->default(0);
                $table->timestamp('email_verified_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
