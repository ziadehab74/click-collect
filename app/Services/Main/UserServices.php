<?php

namespace App\Services\Main;

use App\Models\Main\User;
use Acme\AcmeCrudGenerator\Traits\ServiceHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Acme\AcmeCrudGenerator\Traits\CanNotDeleteHelper;

class UserServices
{
    use ServiceHelper,CanNotDeleteHelper;

    private $user;

    private const CACHED_LIST = 'User-list';

    public function __construct()
    {
        $this->user = new User();
    }
    public function getModel()
    {
        return $this->user;
    }

    public function setModel(User $user)
    {
        $this->user = $user;
    }

    public function getModelClass()
    {
        return User::class;
    }

    public function getModelQuery()
    {
        return User::query();
    }

    public function findOrFailIfNotExists($id, $trashed = false)
    {
        if (!$this->user->exists)
            if ($trashed)
                $this->user = User::onlyTrashed()->findOrFail($id);
            else
                $this->user = User::findOrFail($id);

        return $this->user;
    }

    public function getSearchableFields()
    {
        return array_merge((new User())->getFillable(), ['created_at', 'updated_at']);
    }

    public function getViewData($view)
    {
        return [];
    }

    public function store($data)
    {
        $validator = Validator::make($data, $this->user->rules('create'), $this->user->messages());

        if ($validator->fails())
            return $this->validationFailedResponse($validator->errors());

        $this->user = User::create($data);

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($data, __('User._created_success_msg_'));
    }

    public function edit($data)
    {
        $this->user = $this->findOrFailIfNotExists($data['id']);

        $validator = Validator::make($data, $this->user->rules('update'), $this->user->messages());

        if ($validator->fails())
            return $this->validationFailedResponse($validator->errors());

        $this->user = $this->user->update($data);

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($data, __('User._updated_success_msg_'));
    }

    public function delete($id)
    {
        $this->user = $this->findOrFailIfNotExists($id);

        $validation = $this->checkDeleteAbility($this->user);
        if ($validation['code'] == $this::CAN_NOT_BE_DELETED_CODE)
            return $validation;

        $this->user->deleted_at = date('Y-m-d H:i:s');
        $this->user->deleted_by = ((Auth::check()) ? Auth::id() : null);
        $this->user->save();

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($id, __('User._deleted_success_msg_'));

    }

    public function restore($id)
    {
        $this->user = $this->findOrFailIfNotExists($id, true);

        $this->user->deleted_at = null;
        $this->user->restored_at = date('Y-m-d H:i:s');
        $this->user->restored_by = ((Auth::check()) ? Auth::id() : null);
        $this->user->save();

        Cache::forget(self::CACHED_LIST);

        return $this->successResponse($id, __('User._restored_success_msg_'));
    }

    public function search($filters)
    {
        $query = User::query();
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
        $query = User::onlyTrashed();
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
        $activities = $this->user->activities()->orderBy('created_at', 'desc')->paginate();
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
        $list = User::all();
        Cache::put(self::CACHED_LIST, $list);
        return $list;
    }
}
