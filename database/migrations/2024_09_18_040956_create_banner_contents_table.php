<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banner_contents', function (Blueprint $table) {
            $table->id()->comment('Unique table ID');
            $table->unsignedBigInteger('banner_id')->default(0)->comment('Banner ID. Ref `banners` table');
            $table->tinyInteger('type')->default(0)->comment('Define content type(s). 1-image, 2-video, etc...');
            $table->text('path')->comment('Content path without actual domain');
            $table->tinyInteger('status')->default(1)->comment('Record status. 1-active, 0-inactive');
            $table->softDeletes()->comment('Record deleted timestamp');
            $table->timestamps();
            $table->index('type', 'idx_type');
            $table->index('status', 'idx_status');
            $table->index('banner_id', 'idx_banner_id');

            // Foreign key constraint to reference banners table
            $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_contents');
    }
};
