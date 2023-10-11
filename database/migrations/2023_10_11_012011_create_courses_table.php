<?php

use App\Models\Course;
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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("subtitle");
            $table->string("slug");
            $table->decimal("price")->nullable();
            $table->enum("status", [Course::DRAFT, Course::REVIEW, Course::APPROVED, Course::REFUSED])->default(Course::DRAFT);
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("category_id")->nullable();
            $table->unsignedBigInteger("level_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("category_id")->references("id")->on("categories")->onDelete("set null");
            $table->foreign("level_id")->references("id")->on("levels")->onDelete("set null");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
