<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuthApiColumnsToPersonalAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->string('browser');
            $table->string('os');
            $table->ipAddress('ip');
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->boolean('is_mobile');
            $table->boolean('confirmed')->default(false);
        });
    }

    public function down()
    {
        Schema::dropColumns('personal_access_tokens', ['browser', 'os', 'ip', 'country', 'city', 'is_mobile',
            'confirmed']);
    }
}