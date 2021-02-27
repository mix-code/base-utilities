<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MixCode\BaseUtilities\BaseUtility;

class CreateBaseUtilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_utilities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->enum('status', [BaseUtility::ACTIVE_STATUS, BaseUtility::INACTIVE_STATUS])->default(BaseUtility::ACTIVE_STATUS);

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
        Schema::dropIfExists('base_utilities');
    }
}
