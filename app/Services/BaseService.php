<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Closure;

abstract class BaseService
{
    /**
     * Execute a Closure within a database transaction.
     *
     * @param Closure $callback
     * @return mixed
     * @throws \Throwable
     */
    protected function executeInTransaction(Closure $callback): mixed
    {
        return DB::transaction($callback);
    }

    /**
     * Start a new database transaction.
     */
    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    /**
     * Commit the active database transaction.
     */
    public function commit(): void
    {
        DB::commit();
    }

    /**
     * Rollback the active database transaction.
     */
    public function rollBack(): void
    {
        DB::rollBack();
    }
}
