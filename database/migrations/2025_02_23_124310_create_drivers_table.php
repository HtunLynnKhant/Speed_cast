<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key reference to users table
            $table->string('driver_ic_number', 155); // Driver IC Number
            $table->string('driver_car_plate', 155); // Driver car plate
            $table->text('drivers_license'); // Driver's license
            $table->text('drivers_car_license'); // Driver's car license
            $table->tinyInteger('status')->default(0)->comment('Record status. 1-active, 0-inactive'); // Status
            $table->softDeletes(); // Soft delete timestamp
            $table->timestamps(); // Created at & Updated at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers');
    }
}
