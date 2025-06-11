<?php

namespace App\Services\Main;

use App\Models\Main\RolePermission;
use Acme\AcmeCrudGenerator\Traits\ServiceHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Acme\AcmeCrudGenerator\Traits\CanNotDeleteHelper;

class RolePermissionServices
{
    use ServiceHelper,CanNotDeleteHelper;

    private $role_permission;

    private const CACHED_LIST = 'RolePermission-list';

    public function __construct()
    {
        $this->role_permission = new RolePermission();
    }
    public function getModel()
    {
        return $this->role_permission;
    }

    public function setModel(RolePermission $role_permission)
    {
        $this->role_permission = $role_permission;
    }

    public function getModelClass()
    {
        return RolePermission::class;
    }

    public function getModelQuery()
    {
        return RolePermission::query();
    }

    public function findOrFailIfNotExists($id, $trashed = false)
    {
        if (!$this->role_permission->exists)
            if ($trashed)
                $this->role_permission = RolePermission::onlyTrashed()->findOrFail($id);
            else
                $this->role_permission = RolePermission::findOrFail($id);

        return $this->role_permission;
    }

    public function getSearchableFields()
    {
        return array_merge((new RolePermission())->getFillable(), ['created_at', 'updated_at']);
    }

    public function getViewData($view)
    {
        return [];
    }

    public function store($data)
    {
        $validator = Validator::make($data, $this->role_permission->rules('create'), $this->role_permission->messages());

        if ($validator->fails())
            return $this->validationFailedResponse($validator->errors());

        $this->role_permission = RolePermission::create($data);

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($data, __('RolePermission._created_success_msg_'));
    }

    public function edit($data)
    {
        $this->role_permission = $this->findOrFailIfNotExists($data['id']);

        $validator = Validator::make($data, $this->role_permission->rules('update'), $this->role_permission->messages());

        if ($validator->fails())
            return $this->validationFailedResponse($validator->errors());

        $this->role_permission = $this->role_permission->update($data);

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($data, __('RolePermission._updated_success_msg_'));
    }

    public function delete($id)
    {
        $this->role_permission = $this->findOrFailIfNotExists($id);

        $validation = $this->checkDeleteAbility($this->role_permission);
        if ($validation['code'] == $this::CAN_NOT_BE_DELETED_CODE)
            return $validation;

        $this->role_permission->deleted_at = date('Y-m-d H:i:s');
        $this->role_permission->deleted_by = ((Auth::check()) ? Auth::id() : null);
        $this->role_permission->save();

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($id, __('RolePermission._deleted_success_msg_'));

    }

    public function restore($id)
    {
        $this->role_permission = $this->findOrFailIfNotExists($id, true);

        $this->role_permission->deleted_at = null;
        $this->role_permission->restored_at = date('Y-m-d H:i:s');
        $this->role_permission->restored_by = ((Auth::check()) ? Auth::id() : null);
        $this->role_permission->save();

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($id, __('RolePermission._restored_success_msg_'));
    }

    public function search($filters)
    {
        $query = RolePermission::query();
        $columns = $this->getSearchableFields();
        $results = $query->where(function ($q) use ($columns, $filters) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', '%' . $filters . '%');
            }
        })->latest()->paginate();

        return ['data' => $results];
    }

    public function searchTrashed($filters)
    {
        $query = RolePermission::onlyTrashed();
        $columns = $this->getSearchableFields();
        $results = $query->where(function ($q) use ($columns, $filters) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', '%' . $filters . '%');
            }
        })->latest()->paginate();

        return ['data' => $results];
    }
    public function logs()
    {
        $activities = $this->role_permission->activities()->orderBy('created_at', 'desc')->paginate();
        $groupedActivities = $activities->getCollection()->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        });
        $activities->setCollection($groupedActivities);
        return $this->successResponse($activities);

    }
    public static function getCachedList($options = [])
    {
        $list = Cache::get(self::CACHED_LIST);
        if ($list) {
            return $list;
        }
        $list = RolePermission::all();
        Cache::put(self::CACHED_LIST, $list);
        return $list;
    }
}
