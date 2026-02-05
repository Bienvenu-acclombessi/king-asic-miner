<?php

namespace App\Database;

use Illuminate\Database\Migrations\Migration as BaseMigration;

abstract class Migration extends BaseMigration
{
    /**
     * Migration table prefix (empty - no prefix used)
     */
    protected string $prefix = '';

    /**
     * Check if we can drop foreign keys safely
     */
    public function canDropForeignKeys(): bool
    {
        $driverName = $this->getConnection()->getDriverName();

        return ! in_array($driverName, ['sqlite']);
    }
}
