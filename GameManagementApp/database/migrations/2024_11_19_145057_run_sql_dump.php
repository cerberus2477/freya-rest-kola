<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        shell_exec('C:\xampp\mysql\bin\mysql -u root < database\GameManagementApp_dump.sql');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
