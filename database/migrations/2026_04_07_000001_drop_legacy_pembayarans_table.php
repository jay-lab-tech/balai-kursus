<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('pembayarans');
    }

    public function down(): void
    {
        // Legacy manual-payment table intentionally not recreated.
    }
};
