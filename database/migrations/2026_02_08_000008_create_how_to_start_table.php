<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('how_to_start')) {
            Schema::create('how_to_start', function (Blueprint $table) {
                $table->id();
                $table->string('client_size', 100)->nullable();

                $table->string('google_drive_url', 500)->nullable();
                $table->boolean('google_drive_active')->default(true);
                $table->string('yandex_disk_url', 500)->nullable();
                $table->boolean('yandex_disk_active')->default(true);
                $table->string('filemail_url', 500)->nullable();
                $table->boolean('filemail_active')->default(true);
                $table->string('mega_url', 500)->nullable();
                $table->boolean('mega_active')->default(true);
                $table->string('torrent_url', 500)->nullable();
                $table->boolean('torrent_active')->default(true);

                $table->string('launcher_text_ru', 255)->nullable();
                $table->string('launcher_text_en', 255)->nullable();
                $table->string('launcher_url', 500)->nullable();
                $table->text('launcher_description_ru')->nullable();
                $table->text('launcher_description_en')->nullable();

                $table->string('req_storage_min', 50)->nullable();
                $table->string('req_storage_rec', 50)->nullable();
                $table->string('req_windows_min', 50)->nullable();
                $table->string('req_windows_rec', 50)->nullable();
                $table->string('req_ram_min', 50)->nullable();
                $table->string('req_ram_rec', 50)->nullable();
                $table->string('req_gpu_min', 50)->nullable();
                $table->string('req_gpu_rec', 50)->nullable();
                $table->string('req_internet_min', 50)->nullable();
                $table->string('req_internet_rec', 50)->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('how_to_start');
    }
};
