<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $t) {
            $t->string('role', 20)->default('customer')->index();
            $t->string('phone', 20)->nullable();
        });
        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->string('name_ar'); $t->string('name_en');
            $t->string('icon', 8)->default('⚡');
            $t->enum('section', ['tech', 'perfume'])->default('tech')->index();
            $t->timestamps();
        });
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained()->cascadeOnDelete();
            $t->string('name_ar'); $t->string('name_en');
            $t->text('desc_ar')->nullable(); $t->text('desc_en')->nullable();
            $t->string('brand')->nullable()->index();
            $t->decimal('price', 10, 2); $t->decimal('old_price', 10, 2)->nullable();
            $t->unsignedInteger('stock')->default(0);
            $t->string('image')->nullable();
            $t->boolean('is_featured')->default(false);
            $t->boolean('is_active')->default(true)->index();
            $t->timestamps();
        });
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->string('number')->unique();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name'); $t->string('phone', 20); $t->string('city');
            $t->text('address'); $t->text('notes')->nullable();
            $t->decimal('subtotal', 10, 2); $t->decimal('shipping', 10, 2); $t->decimal('total', 10, 2);
            $t->enum('payment_method', ['cod', 'card']);
            $t->enum('payment_status', ['unpaid', 'paid', 'failed', 'refunded'])->default('unpaid');
            $t->string('transaction_id')->nullable();
            $t->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending')->index();
            $t->timestamps();
        });
        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name'); $t->decimal('price', 10, 2); $t->unsignedInteger('qty');
        });
    }
    public function down(): void {
        Schema::dropIfExists('order_items'); Schema::dropIfExists('orders');
        Schema::dropIfExists('products'); Schema::dropIfExists('categories');
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn(['role', 'phone']));
    }
};
