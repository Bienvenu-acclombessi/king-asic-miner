<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            // Basic discount fields
            $table->decimal('discount_value', 10, 2)->after('type')->default(0);
            $table->boolean('is_active')->after('discount_value')->default(true)->index();

            // Order conditions
            $table->decimal('min_order_amount', 10, 2)->nullable()->after('is_active');
            $table->decimal('max_order_amount', 10, 2)->nullable()->after('min_order_amount');
            $table->integer('min_qty')->unsigned()->nullable()->after('max_order_amount');
            $table->decimal('max_discount_amount', 10, 2)->nullable()->after('min_qty');

            // Advanced options
            $table->boolean('apply_to_shipping')->default(false)->after('max_discount_amount');
            $table->boolean('exclude_sale_items')->default(false)->after('apply_to_shipping');
            $table->boolean('individual_use')->default(false)->after('exclude_sale_items');
            $table->boolean('free_shipping')->default(false)->after('individual_use');

            // Email restriction (comma-separated emails)
            $table->text('allowed_emails')->nullable()->after('free_shipping');

            // Description for admin
            $table->text('description')->nullable()->after('allowed_emails');
        });
    }

    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn([
                'discount_value',
                'is_active',
                'min_order_amount',
                'max_order_amount',
                'min_qty',
                'max_discount_amount',
                'apply_to_shipping',
                'exclude_sale_items',
                'individual_use',
                'free_shipping',
                'allowed_emails',
                'description',
            ]);
        });
    }
};
