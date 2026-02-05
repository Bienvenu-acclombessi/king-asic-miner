<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $variantsTable = 'product_variants';
        $productsTable = 'products';
        $optionsTable = 'product_options';
        $optionValueTable = 'product_option_values';
        $variantOptionsValueTable = 'product_option_value_product_variant';

        DB::table($variantOptionsValueTable)->join(
            $variantsTable,
            "{$variantOptionsValueTable}.variant_id",
            '=',
            "{$variantsTable}.id"
        )->join(
            $optionValueTable,
            "{$variantOptionsValueTable}.value_id",
            '=',
            "{$optionValueTable}.id"
        )->join(
            $optionsTable,
            "{$optionValueTable}.product_option_id",
            '=',
            "{$optionsTable}.id"
        )->join(
            $productsTable,
            "{$variantsTable}.product_id",
            '=',
            "{$productsTable}.id"
        )->select([
            "{$productsTable}.id as product_id",
            "{$optionsTable}.id as product_option_id",
            "{$optionsTable}.position",
        ])->groupBy([
            "{$productsTable}.id",
            "{$optionsTable}.id",
            "{$optionsTable}.position",
        ])
            ->orderBy("{$productsTable}.id")
            ->chunk(200, function ($rows) {
                DB::table(
                    'product_product_option'
                )->insert(
                    $rows->map(
                        fn ($row) => (array) $row
                    )->toArray()
                );
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_product_option');
    }
};
