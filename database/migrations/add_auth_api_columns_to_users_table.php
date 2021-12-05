<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuthApiColumnsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->unique()->nullable();
            $table->timestamp('phone_confirmed_at')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('postal_index')->nullable();
            $table->enum('two_factor_authentication_type', ['email', 'phone', 'google_authentication'])->nullable();
            $table->string('google_authentication_secret')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'phone_confirmed_at', 'country', 'city', 'street', 'postal_index',
                'two_factor_authentication_type', 'google_authentication_secret']);
        });
    }
}