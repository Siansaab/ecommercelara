<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Termwind\Components\Raw;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code')->unique();
            $table->enum('type',['fixed','percentage']);
            $table->decimal('value');
            $table->decimal('cart_value');
            $table->date('expiry_date')->default(DB::raw("(Date(CURRENT_TIMESTAMP))"));
        
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupans');
    }
};
