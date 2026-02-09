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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Web Store, Mobile App, etc.
            $table->string('handle')->unique(); // web, mobile, etc.
            $table->boolean('default')->default(false);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        // Insert default channel
        DB::table('channels')->insert([
            'name' => 'Web Store',
            'handle' => 'web',
            'default' => true,
            'enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
