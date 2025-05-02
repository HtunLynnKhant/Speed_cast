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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->default('')->comment('Category name');
            $table->string('description', 125)->default('')->comment('Describe the category');
            $table->text('icon_path')->comment('Icon path for the category');
            $table->tinyInteger('status')->default(1)->comment('Record status. 0-inactive, 1-active');
            $table->timestamp('deleted_at')->nullable()->comment('Record deleted timestamp');
            $table->timestamps(0); // Using `timestamps(0)` for zero precision on timestamp columns

            $table->primary('id');
            $table->index('status', 'idx_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
