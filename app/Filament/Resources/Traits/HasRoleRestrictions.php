<?php

namespace App\Filament\Resources\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasRoleRestrictions
{
    public static function canViewAny(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $allowedRoles = static::getAllowedRoles();
        $userRol = auth()->user()->rol;

        return !empty($userRol) && (
            in_array($userRol, $allowedRoles) ||
            $userRol === 'ADM'
        );
    }

    public static function canView(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canDeleteAny(): bool
    {
        return static::canViewAny();
    }

    protected static function getAllowedRoles(): array
    {
        return ['ADM']; // Por defecto, solo administradores
    }
}
