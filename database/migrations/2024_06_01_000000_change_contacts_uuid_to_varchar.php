<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Part of the CardDAV duplicate-contact fix.
 *
 * Widens contacts.uuid from CHAR(36) to VARCHAR(255) so non-standard CardDAV
 * UIDs (e.g. from iOS/macOS, Thunderbird, older Android apps) are stored intact
 * instead of being silently truncated at 36 characters.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE contacts MODIFY uuid VARCHAR(255) NULL DEFAULT NULL');
        } else {
            Schema::table('contacts', function (Blueprint $table) {
                $table->string('uuid', 255)->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE contacts MODIFY uuid CHAR(36) NULL DEFAULT NULL');
        } else {
            Schema::table('contacts', function (Blueprint $table) {
                $table->char('uuid', 36)->nullable()->change();
            });
        }
    }
};
