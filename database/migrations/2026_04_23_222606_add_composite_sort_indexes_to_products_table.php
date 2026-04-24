<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['category_id', 'in_stock', 'price', 'id'], 'idx_category_stock_price_id');
            $table->index(['category_id', 'in_stock', 'rating', 'id'], 'idx_category_stock_rating_id');
            $table->index(['category_id', 'in_stock', 'created_at', 'id'], 'idx_category_stock_created_id');

            $table->index(['price', 'id'], 'idx_price_id');
            $table->index(['rating', 'id'], 'idx_rating_id');
            $table->index(['created_at', 'id'], 'idx_created_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_category_stock_price_id');
            $table->dropIndex('idx_category_stock_rating_id');
            $table->dropIndex('idx_category_stock_created_id');
            $table->dropIndex('idx_price_id');
            $table->dropIndex('idx_rating_id');
            $table->dropIndex('idx_created_id');
        });
    }
};
