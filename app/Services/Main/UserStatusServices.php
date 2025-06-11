<?php

namespace App\Services\Main;

use App\Models\Main\UserStatus;
use Acme\AcmeCrudGenerator\Traits\ServiceHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Acme\AcmeCrudGenerator\Traits\CanNotDeleteHelper;

class UserStatusServices
{
    use ServiceHelper,CanNotDeleteHelper;

    private $user_status;

    private const CACHED_LIST = 'UserStatus-list';

    public function __construct()
    {
        $this->user_status = new UserStatus();
    }
    public function getModel()
    {
        return $this->user_status;
    }

    public function setModel(UserStatus $user_status)
    {
        $this->user_status = $user_status;
    }

    public function getModelClass()
    {
        return UserStatus::class;
    }

    public function getModelQuery()
    {
        return UserStatus::query();
    }

    public function findOrFailIfNotExists($id, $trashed = false)
    {
        if (!$this->user_status->exists)
            if ($trashed)
                $this->user_status = UserStatus::onlyTrashed()->findOrFail($id);
            else
                $this->user_status = UserStatus::findOrFail($id);

        return $this->user_status;
    }

    public function getSearchableFields()
    {
        return array_merge((new UserStatus())->getFillable(), ['created_at', 'updated_at']);
    }

    public function getViewData($view)
    {
        return [];
    }

    public function store($data)
    {
        $validator = Validator::make($data, $this->user_status->rules('create'), $this->user_status->messages());

        if ($validator->fails())
            return $this->validationFailedResponse($validator->errors());

        $this->user_status = UserStatus::create($data);

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($data, __('UserStatus._created_success_msg_'));
    }

    public function edit($data)
    {
        $this->user_status = $this->findOrFailIfNotExists($data['id']);

        $validator = Validator::make($data, $this->user_status->rules('update'), $this->user_status->messages());

        if ($validator->fails())
            return $this->validationFailedResponse($validator->errors());

        $this->user_status = $this->user_status->update($data);

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($data, __('UserStatus._updated_success_msg_'));
    }

    public function delete($id)
    {
        $this->user_status = $this->findOrFailIfNotExists($id);

        $validation = $this->checkDeleteAbility($this->user_status);
        if ($validation['code'] == $this::CAN_NOT_BE_DELETED_CODE)
            return $validation;

        $this->user_status->deleted_at = date('Y-m-d H:i:s');
        $this->user_status->deleted_by = ((Auth::check()) ? Auth::id() : null);
        $this->user_status->save();

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($id, __('UserStatus._deleted_success_msg_'));

    }

    public function restore($id)
    {
        $this->user_status = $this->findOrFailIfNotExists($id, true);

        $this->user_status->deleted_at = null;
        $this->user_status->restored_at = date('Y-m-d H:i:s');
        $this->user_status->restored_by = ((Auth::check()) ? Auth::id() : null);
        $this->user_status->save();

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($id, __('UserStatus._restored_success_msg_'));
    }

    public function search($filters)
    {
        $query = UserStatus::query();
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
        $query = UserStatus::onlyTrashed();
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
        $activities = $this->user_status->activities()->orderBy('created_at', 'desc')->paginate();
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
        $list = UserStatus::all();
        Cache::put(self::CACHED_LIST, $list);
        return $list;
    }
}
