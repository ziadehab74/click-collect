<?php

namespace App\Services\Main;

use App\Models\Main\Role;
use Acme\AcmeCrudGenerator\Traits\ServiceHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Acme\AcmeCrudGenerator\Traits\CanNotDeleteHelper;

class RoleServices
{
    use ServiceHelper,CanNotDeleteHelper;

    private $role;

    private const CACHED_LIST = 'Role-list';

    public function __construct()
    {
        $this->role = new Role();
    }
    public function getModel()
    {
        return $this->role;
    }

    public function setModel(Role $role)
    {
        $this->role = $role;
    }

    public function getModelClass()
    {
        return Role::class;
    }

    public function getModelQuery()
    {
        return Role::query();
    }

    public function findOrFailIfNotExists($id, $trashed = false)
    {
        if (!$this->role->exists)
            if ($trashed)
                $this->role = Role::onlyTrashed()->findOrFail($id);
            else
                $this->role = Role::findOrFail($id);

        return $this->role;
    }

    public function getSearchableFields()
    {
        return array_merge((new Role())->getFillable(), ['created_at', 'updated_at']);
    }

    public function getViewData($view)
    {
        return [];
    }

    public function store($data)
    {
        $validator = Validator::make($data, $this->role->rules('create'), $this->role->messages());

        if ($validator->fails())
            return $this->validationFailedResponse($validator->errors());

        $this->role = Role::create($data);

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($data, __('Role._created_success_msg_'));
    }

    public function edit($data)
    {
        $this->role = $this->findOrFailIfNotExists($data['id']);

        $validator = Validator::make($data, $this->role->rules('update'), $this->role->messages());

        if ($validator->fails())
            return $this->validationFailedResponse($validator->errors());

        $this->role = $this->role->update($data);

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($data, __('Role._updated_success_msg_'));
    }

    public function delete($id)
    {
        $this->role = $this->findOrFailIfNotExists($id);

        $validation = $this->checkDeleteAbility($this->role);
        if ($validation['code'] == $this::CAN_NOT_BE_DELETED_CODE)
            return $validation;

        $this->role->deleted_at = date('Y-m-d H:i:s');
        $this->role->deleted_by = ((Auth::check()) ? Auth::id() : null);
        $this->role->save();

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($id, __('Role._deleted_success_msg_'));

    }

    public function restore($id)
    {
        $this->role = $this->findOrFailIfNotExists($id, true);

        $this->role->deleted_at = null;
        $this->role->restored_at = date('Y-m-d H:i:s');
        $this->role->restored_by = ((Auth::check()) ? Auth::id() : null);
        $this->role->save();

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($id, __('Role._restored_success_msg_'));
    }

    public function search($filters)
    {
        $query = Role::query();
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
        $query = Role::onlyTrashed();
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
        $activities = $this->role->activities()->orderBy('created_at', 'desc')->paginate();
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
        $list = Role::all();
        Cache::put(self::CACHED_LIST, $list);
        return $list;
    }
}
