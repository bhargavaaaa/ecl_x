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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('work_order_number')->nullable()->unique();
            $table->string('customer')->nullable()->index();
            $table->string('part_name')->nullable()->index();
            $table->string('metal')->nullable()->index();
            $table->string('size')->nullable()->index();
            $table->bigInteger('quantity')->default(0);
            $table->decimal('weight_per_pcs', 15, 3)->default(0);
            $table->decimal('required_weight', 15, 3)->default(0);
            $table->string('po_no')->nullable()->index();
            $table->date('po_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('remark')->nullable();
            $table->date('cutting_end_date')->nullable();
            $table->bigInteger('cutting_recorded_quantity')->default(0);
            $table->bigInteger('cutting_rejected_quantity')->default(0);
            $table->bigInteger('cutting_net_quantity')->default(0);
            $table->date('turning_end_date')->nullable();
            $table->decimal('turning_recorded_quantity', 15)->default(0);
            $table->decimal('turning_rejected_quantity', 15)->default(0);
            $table->decimal('turning_net_quantity', 15)->default(0);
            $table->date('milling_end_date')->nullable();
            $table->decimal('milling_recorded_quantity', 15)->default(0);
            $table->decimal('milling_rejected_quantity', 15)->default(0);
            $table->decimal('milling_net_quantity', 15)->default(0);
            $table->date('other_end_date')->nullable();
            $table->decimal('other_recorded_quantity', 15)->default(0);
            $table->decimal('other_rejected_quantity', 15)->default(0);
            $table->decimal('other_net_quantity', 15)->default(0);
            $table->date('dispatch_end_date')->nullable();
            $table->decimal('dispatch_recorded_quantity', 15)->default(0);
            $table->decimal('dispatch_rejected_quantity', 15)->default(0);
            $table->decimal('dispatch_net_quantity', 15)->default(0);
            $table->foreignId("created_by")->nullable()->constrained();
            $table->foreignId("updated_by")->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
