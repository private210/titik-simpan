<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $idxName = 'expenses_user_id_spent_at_index';
            if (! Schema::hasIndex('expenses', $idxName)) {
                $table->index(['user_id', 'spent_at'], $idxName);
            }
            $idxName2 = 'expenses_user_id_is_recurring_index';
            if (! Schema::hasIndex('expenses', $idxName2)) {
                $table->index(['user_id', 'is_recurring'], $idxName2);
            }
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex('expenses_user_id_spent_at_index');
            $table->dropIndex('expenses_user_id_is_recurring_index');
        });
    }
};