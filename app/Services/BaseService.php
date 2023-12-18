<?php

namespace App\Services;

use App\Enum\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class BaseService
{
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function find(int $id)
    {
        $data = $this->model->find($id);

        if ($data) return $data;

        throw new HttpResponseException(response()->json([
            'message' => 'Resource not found'
        ], 404));
    }

    public function findActive(int $id)
    {
        $data = $this->model->where('status', 1)->find($id);

        if ($data) return $data;

        throw new HttpResponseException(response()->json([
            'message' => 'Resource not found'
        ], 404));
    }

    public function findBySlug(string $slug)
    {
        $data = $this->model->where('slug', $slug)->first();

        if ($data) return $data;

        throw new HttpResponseException(response()->json([
            'message' => 'Resource not found'
        ], 404));
    }

    public function findActiveBySlug(string $slug)
    {
        $data = $this->model->where('status', 1)->where('slug', $slug)->first();

        if ($data) return $data;

        throw new HttpResponseException(response()->json([
            'message' => 'Resource not found'
        ], 404));
    }

    public function fetchAll(array $query)
    {
        return $this->model->all();
    }

    public function fetchAllPaginated(array $query)
    {
        $pageConfig = $this->setPageConfig($query);
        return $this->model->paginate($pageConfig['perPage'], ['*'], 'page', $pageConfig['page']);
    }

    public function fetchActive(array $query)
    {
        return $this->model->where('status', '!=', -1)->get();
    }

    public function fetchActivePaginated(array $query)
    {
        $pageConfig = $this->setPageConfig($query);
        return $this->model->where('status', '!=', -1)->paginate($pageConfig['perPage'], ['*'], 'page', $pageConfig['page']);
    }

    protected function setPageConfig(array $query)
    {
        $perPage = array_key_exists('perPage', $query) ? $query['perPage'] : 10;
        $page = array_key_exists('page', $query) ? $query['page'] : 0;

        return [
            "perPage" => $perPage,
            "page" => $page
        ];
    }

    public function fetchDeleted(array $query = [])
    {
        return $this->model->where('status', -1)->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = $this->find($id);

        if ($model) {
            $model->update($data);
            return $model;
        }

        return null;
    }

    public function softDelete(int $id)
    {
        $model = $this->find($id);

        if ($model) {
            $model->update(['status' => Constants::STATUS_DELETED]);
            $model->save();
        }
    }

    public function delete(int $id)
    {
        $model = $this->find($id);

        if ($model) {
            $model->delete();
        }
    }
}
