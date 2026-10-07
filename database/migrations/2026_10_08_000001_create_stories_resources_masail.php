<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('stories')) Schema::create('stories', function (Blueprint $t) {
            $t->id(); $t->string('title',160); $t->string('slug',180)->unique();
            $t->string('story_type',40)->default('prophet'); $t->string('person_name',160)->nullable(); $t->string('era_label',120)->nullable();
            $t->text('short_story')->nullable(); $t->longText('life_journey')->nullable(); $t->text('reason')->nullable();
            $t->longText('full_story')->nullable(); $t->longText('lessons')->nullable(); $t->json('sources')->nullable();
            $t->string('cover_url',1000)->nullable(); $t->boolean('is_published')->default(false); $t->timestamps();
            $t->index(['story_type','is_published']);
        });
        if (!Schema::hasTable('story_schedules')) Schema::create('story_schedules', function (Blueprint $t) {
            $t->id(); $t->foreignId('story_id')->constrained()->cascadeOnDelete();
            $t->date('starts_on'); $t->date('ends_on')->nullable(); $t->string('repeat_rule',16)->default('once');
            $t->unsignedSmallInteger('priority')->default(10); $t->boolean('is_active')->default(true); $t->timestamps();
            $t->index(['is_active','starts_on','ends_on']);
        });
        if (!Schema::hasTable('resources')) Schema::create('resources', function (Blueprint $t) {
            $t->id(); $t->string('title',180); $t->string('slug',200)->unique();
            $t->string('resource_type',32)->default('islamic_link'); $t->string('creator',180)->nullable();
            $t->text('summary')->nullable(); $t->string('url',1500); $t->string('cover_url',1000)->nullable();
            $t->boolean('is_published')->default(false); $t->boolean('is_featured')->default(false); $t->date('published_at')->nullable(); $t->timestamps();
            $t->index(['is_published','is_featured','published_at']);
        });
        if (!Schema::hasTable('masail')) Schema::create('masail', function (Blueprint $t) {
            $t->id(); $t->string('title',180); $t->string('slug',200)->unique(); $t->string('category',100)->nullable();
            $t->text('question')->nullable(); $t->text('short_answer')->nullable(); $t->longText('answer')->nullable();
            $t->longText('references')->nullable(); $t->boolean('is_published')->default(false); $t->timestamps();
            $t->index(['category','is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('masail'); Schema::dropIfExists('resources'); Schema::dropIfExists('story_schedules'); Schema::dropIfExists('stories');
    }
};
