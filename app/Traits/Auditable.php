<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    /**
     * Boot the auditable trait for a model.
     */
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->auditCreated();
        });

        static::updated(function ($model) {
            $model->auditUpdated();
        });

        static::deleted(function ($model) {
            $model->auditDeleted();
        });

        if (method_exists(static::class, 'restored')) {
            static::restored(function ($model) {
                $model->auditRestored();
            });
        }
    }

    /**
     * Get the attributes that should be excluded from auditing.
     */
    protected function getAuditExclude(): array
    {
        return $this->auditExclude ?? ['updated_at', 'created_at'];
    }

    /**
     * Get filtered attributes for auditing.
     */
    protected function getAuditableAttributes(array $attributes): array
    {
        $exclude = $this->getAuditExclude();
        return array_diff_key($attributes, array_flip($exclude));
    }

    /**
     * Get current request information.
     */
    protected function getRequestInfo(): array
    {
        $request = request();
        return [
            'ip_address' => $request ? $request->ip() : null,
            'user_agent' => $request ? $request->userAgent() : null,
        ];
    }

    /**
     * Audit model creation.
     */
    protected function auditCreated()
    {
        AuditLog::create(array_merge([
            'user_id' => Auth::id(),
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'action' => 'created',
            'old_values' => null,
            'new_values' => $this->getAuditableAttributes($this->getAttributes()),
        ], $this->getRequestInfo()));
    }

    /**
     * Audit model update.
     */
    protected function auditUpdated()
    {
        $changes = $this->getChanges();
        $original = $this->getOriginal();

        if (empty($changes)) {
            return;
        }

        $oldValues = [];
        $newValues = [];

        foreach ($changes as $key => $value) {
            if (!in_array($key, $this->getAuditExclude())) {
                $oldValues[$key] = $original[$key] ?? null;
                $newValues[$key] = $value;
            }
        }

        if (empty($newValues)) {
            return;
        }

        AuditLog::create(array_merge([
            'user_id' => Auth::id(),
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'action' => 'updated',
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ], $this->getRequestInfo()));
    }

    /**
     * Audit model deletion.
     */
    protected function auditDeleted()
    {
        AuditLog::create(array_merge([
            'user_id' => Auth::id(),
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'action' => 'deleted',
            'old_values' => $this->getAuditableAttributes($this->getAttributes()),
            'new_values' => null,
        ], $this->getRequestInfo()));
    }

    /**
     * Audit model restoration.
     */
    protected function auditRestored()
    {
        AuditLog::create(array_merge([
            'user_id' => Auth::id(),
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'action' => 'restored',
            'old_values' => null,
            'new_values' => $this->getAuditableAttributes($this->getAttributes()),
        ], $this->getRequestInfo()));
    }

    /**
     * Get audit logs for this model.
     */
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'model');
    }
}
