<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Drop legacy BĐS / unused tables.
     * This migration cleans the database from the previous real-estate codebase.
     */
    public function up(): void
    {
        // Foreign-key safe drop order: children first
        $tables = [
            'product_files',
            'product_images',
            'wishlists',
            'reviews',
            'points',
            'coupons',
            'order_details',
            'employees',
            'customers',
            'carts',
            'productsvp',
            'products',
            'wards',
            'ward',
            'districts',
            'district',
            'provinces',
            'city',
            'cities',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }

    public function down(): void
    {
        // No rollback — these legacy tables are intentionally removed.
    }
};
