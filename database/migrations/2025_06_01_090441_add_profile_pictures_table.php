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
    Schema::create('profile_pictures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('file_name');
            $table->string('drive_file_id');
            $table->string('mime_type')->nullable();
            $table->timestamps();
        });

    // Fix invalid data first
    DB::table('companies')->whereRaw("CAST(profile_picture AS CHAR) = ''")->update(['profile_picture' => null]);
    DB::table('applicants')->whereRaw("CAST(profile_picture AS CHAR) = ''")->update(['profile_picture' => null]);

    Schema::table('companies', function (Blueprint $table) {
        $table->unsignedBigInteger('profile_picture')->nullable()->change();
        $table->foreign('profile_picture')
            ->references('id')
            ->on('profile_pictures')
            ->onDelete('set null');
    });

    Schema::table('applicants', function (Blueprint $table) {
        $table->unsignedBigInteger('profile_picture')->nullable()->change();
        $table->foreign('profile_picture')
            ->references('id')
            ->on('profile_pictures')
            ->onDelete('set null');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_pictures');
    }
    
};