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
        Schema::create('banners', function (Blueprint $table) {
            $table->id()->comment('Unique table ID');
            $table->string('title', 50)->default('')->comment('Banner title');
            $table->string('description', 255)->default('')->comment('Banner description');
            $table->unsignedBigInteger('category_id')->default(0)->comment('Category ID. Ref - `categories` table');
            $table->tinyInteger('status')->default(1)->comment('Record status. 1-active, 0-inactive');
            $table->softDeletes()->comment('Record deleted timestamp');
            $table->timestamps();
            $table->index('title', 'idx_title');
            $table->index('category_id', 'idx_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
