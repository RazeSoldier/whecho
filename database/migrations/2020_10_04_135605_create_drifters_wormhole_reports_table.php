<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriftersWormholeReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drifters_wormhole_reports', function (Blueprint $table) {
            $table->id();
            $table->string('system');
            $table->string('signature_name');
            $table->string('submitter');
            $table->string('notice')->default('');
            $table->ipAddress('submitter_ip');
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
        Schema::dropIfExists('drifters_wormhole_reports');
    }
}
