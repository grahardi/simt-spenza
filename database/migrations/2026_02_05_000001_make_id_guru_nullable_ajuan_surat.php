<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE ajuan_surat DROP FOREIGN KEY ajuan_surat_id_guru_foreign');
        DB::statement('ALTER TABLE ajuan_surat MODIFY id_guru INT NULL');
        DB::statement('ALTER TABLE ajuan_surat ADD CONSTRAINT ajuan_surat_id_guru_foreign FOREIGN KEY (id_guru) REFERENCES guru(id_guru) ON DELETE CASCADE');
    }

    public function down(): void
    {
        // Tidak perlu revert
    }
};
