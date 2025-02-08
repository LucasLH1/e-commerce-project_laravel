<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::table('products')->truncate();
    }

    public function down()
    {
        // On ne peut pas restaurer les anciens produits, donc cette méthode reste vide.
    }
};
