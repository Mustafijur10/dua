<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up():void {
 Schema::create('users',function(Blueprint $t){$t->id();$t->string('name');$t->string('email')->unique();$t->timestamp('email_verified_at')->nullable();$t->string('password');$t->rememberToken();$t->timestamps();});
 Schema::create('categories',function(Blueprint $t){$t->id();$t->string('name',80)->unique();$t->string('icon',40)->default('✦');$t->string('color',30)->default('sage');$t->timestamps();});
 Schema::create('duas',function(Blueprint $t){$t->id();$t->string('title',180);$t->foreignId('category_id')->constrained()->cascadeOnDelete();$t->string('subcategory',100)->nullable();$t->longText('arabic')->nullable();$t->longText('transliteration')->nullable();$t->longText('translation')->nullable();$t->string('reference',180)->nullable();$t->text('notes')->nullable();$t->boolean('is_favorite')->default(false);$t->timestamps();$t->index(['category_id','subcategory']);});
 Schema::create('notes',function(Blueprint $t){$t->id();$t->string('title',160);$t->longText('body')->nullable();$t->boolean('pinned')->default(false);$t->timestamps();});
 Schema::create('sessions',function(Blueprint $t){$t->string('id')->primary();$t->foreignId('user_id')->nullable()->index();$t->string('ip_address',45)->nullable();$t->text('user_agent')->nullable();$t->longText('payload');$t->integer('last_activity')->index();});
 } public function down():void {Schema::dropIfExists('sessions');Schema::dropIfExists('notes');Schema::dropIfExists('duas');Schema::dropIfExists('categories');Schema::dropIfExists('users');} };
