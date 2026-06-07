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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('tussenvoegsel')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('tussenvoegsel');
            $table->string('mobile', 20)->nullable()->after('last_name');
            $table->date('datum_in_dienst')->nullable()->after('mobile');
            $table->integer('aantal_sterren')->default(0)->after('datum_in_dienst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'tussenvoegsel',
                'last_name',
                'mobile',
                'datum_in_dienst',
                'aantal_sterren',
            ]);
        });
    }
};
