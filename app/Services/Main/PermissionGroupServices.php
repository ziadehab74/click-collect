<?php

namespace App\Services\Main;

use App\Models\Main\PermissionGroup;
use Acme\AcmeCrudGenerator\Traits\ServiceHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Acme\AcmeCrudGenerator\Traits\CanNotDeleteHelper;

class PermissionGroupServices
{
    use ServiceHelper,CanNotDeleteHelper;

    private $permission_group;

    private const CACHED_LIST = 'PermissionGroup-list';

    public function __construct()
    {
        $this->permission_group = new PermissionGroup();
    }
    public function getModel()
    {
        return $this->permission_group;
    }

    public function setModel(PermissionGroup $permission_group)
    {
        $this->permission_group = $permission_group;
    }

    public function getModelClass()
    {
        return PermissionGroup::class;
    }

    public function getModelQuery()
    {
        return PermissionGroup::query();
    }

    public function findOrFailIfNotExists($id, $trashed = false)
    {
        if (!$this->permission_group->exists)
            if ($trashed)
                $this->permission_group = PermissionGroup::onlyTrashed()->findOrFail($id);
            else
                $this->permission_group = PermissionGroup::findOrFail($id);

        return $this->permission_group;
    }

    public function getSearchableFields()
    {
        return array_merge((new PermissionGroup())->getFillable(), ['created_at', 'updated_at']);
    }

    public function getViewData($view)
    {
        return [];
    }

    public function store($data)
    {
        $validator = Validator::make($data, $this->permission_group->rules('create'), $this->permission_group->messages());

        if ($validator->fails())
            return $this->validationFailedResponse($validator->errors());

        $this->permission_group = PermissionGroup::create($data);

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($data, __('PermissionGroup._created_success_msg_'));
    }

    public function edit($data)
    {
        $this->permission_group = $this->findOrFailIfNotExists($data['id']);

        $validator = Validator::make($data, $this->permission_group->rules('update'), $this->permission_group->messages());

        if ($validator->fails())
            return $this->validationFailedResponse($validator->errors());

        $this->permission_group = $this->permission_group->update($data);

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($data, __('PermissionGroup._updated_success_msg_'));
    }

    public function delete($id)
    {
        $this->permission_group = $this->findOrFailIfNotExists($id);

        $validation = $this->checkDeleteAbility($this->permission_group);
        if ($validation['code'] == $this::CAN_NOT_BE_DELETED_CODE)
            return $validation;

        $this->permission_group->deleted_at = date('Y-m-d H:i:s');
        $this->permission_group->deleted_by = ((Auth::check()) ? Auth::id() : null);
        $this->permission_group->save();

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($id, __('PermissionGroup._deleted_success_msg_'));

    }

    public function restore($id)
    {
        $this->permission_group = $this->findOrFailIfNotExists($id, true);

        $this->permission_group->deleted_at = null;
        $this->permission_group->restored_at = date('Y-m-d H:i:s');
        $this->permission_group->restored_by = ((Auth::check()) ? Auth::id() : null);
        $this->permission_group->save();

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($id, __('PermissionGroup._restored_success_msg_'));
    }

    public function search($filters)
    {
        $query = PermissionGroup::query();
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
        $query = PermissionGroup::onlyTrashed();
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
        $activities = $this->permission_group->activities()->orderBy('created_at', 'desc')->paginate();
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
        $list = PermissionGroup::all();
        Cache::put(self::CACHED_LIST, $list);
        return $list;
    }
}
