<?php

namespace App\Models\Concerns;

trait UsesSubcontractorConnection
{
    /**
     * Set the database connection that should be used by the model.
     */
    public function getConnectionName()
    {
        return config('subcontractor.connection');
    }
}
