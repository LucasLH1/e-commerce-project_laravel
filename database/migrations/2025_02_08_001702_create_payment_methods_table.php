<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom de la méthode (ex: "Carte Bancaire", "PayPal")
            $table->string('slug')->unique(); // Identifiant unique (ex: "card", "paypal")
            $table->boolean('is_active')->default(true); // Permet d'activer/désactiver la méthode
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }

};
