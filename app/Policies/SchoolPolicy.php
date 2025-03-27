<?php

namespace App\Policies;

use App\Models\School;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchoolPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tout utilisateur authentifié peut voir la liste des écoles auxquelles il a accès
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, School $school): bool
    {
        // L'utilisateur peut voir l'école s'il est super-admin ou s'il est admin de cette école
        return $user->is_superadmin || $this->isSchoolAdmin($user, $school);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Seuls les super-admins peuvent créer de nouvelles écoles
        return $user->is_superadmin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, School $school): bool
    {
        // L'utilisateur peut modifier l'école s'il est super-admin ou s'il est admin de cette école
        return $user->is_superadmin || $this->isAdminWithRole($user, $school, ['admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, School $school): bool
    {
        // Seuls les super-admins peuvent supprimer une école
        return $user->is_superadmin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, School $school): bool
    {
        // Seuls les super-admins peuvent restaurer une école
        return $user->is_superadmin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, School $school): bool
    {
        // Seuls les super-admins peuvent supprimer définitivement une école
        return $user->is_superadmin;
    }

    /**
     * Determine whether the user can view school admins.
     */
    public function viewAdmins(User $user, School $school): bool
    {
        // L'utilisateur peut voir les admins s'il est super-admin ou s'il est admin de cette école
        return $user->is_superadmin || $this->isAdminWithRole($user, $school, ['admin']);
    }

    /**
     * Determine whether the user can manage school admins.
     */
    public function manageAdmins(User $user, School $school): bool
    {
        // L'utilisateur peut gérer les admins s'il est super-admin ou s'il est admin de cette école
        return $user->is_superadmin || $this->isAdminWithRole($user, $school, ['admin']);
    }

    /**
     * Check if the user is an admin of the school with any role.
     */
    private function isSchoolAdmin(User $user, School $school): bool
    {
        return $user->schools()->where('school_id', $school->id)->exists();
    }

    /**
     * Check if the user is an admin of the school with specific roles.
     */
    private function isAdminWithRole(User $user, School $school, array $roles): bool
    {
        return $user->schools()
            ->where('school_id', $school->id)
            ->wherePivotIn('role', $roles)
            ->exists();
    }
}
