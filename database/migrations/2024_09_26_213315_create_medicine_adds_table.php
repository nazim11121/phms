<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\MedicineAdd;

class CreateMedicineAddsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicine_adds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('group_id')->nullable();
            $table->string('brand_id')->nullable();
            $table->string('type_id')->nullable();
            $table->string('available_stock')->nullable();
            $table->string('suplier_id')->nullable();
            $table->string('status', 20)->default(MedicineAdd::STATUS_ACTIVE);
            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicine_adds');
    }
}
