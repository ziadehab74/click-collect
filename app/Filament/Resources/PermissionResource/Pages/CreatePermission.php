<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use App\Models\Permission;
use App\Models\Role;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure permissions are stored as an array
        $permissions = is_array($data['view']) ? $data['view'] : json_decode($data['view'], true);

        if (!is_array($permissions)) {
            return $data; // Return original data if invalid
        }

        $groupId = $data['group_id'];
        $moduleId = $data['module_id'];
        $roleId = $data['role_id']; // Role ID to be stored in `role_permission`

        // Get the role instance
        $role = Role::find($roleId);

        foreach ($permissions as $permissionName) {
            // Create or find the permission
            $permission = Permission::firstOrCreate([
                'group_id'  => $groupId,
                'module_id' => $moduleId,
                'view'      => $permissionName,
            ]);

            // Attach permission to role using the pivot table
            if ($role) {
                $role->permissions()->attach($permission->id);
            }
        }
     
        $data['view'] = json_encode($permissions);
        return $data;
    }
}
