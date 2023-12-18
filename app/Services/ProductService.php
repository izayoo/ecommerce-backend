<?php

namespace App\Services;

use App\Enum\Constants;
use App\Models\Product;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService extends BaseService
{
    private Product $model;
    private ProductCategoryService $productCategoryService;
    private MediaService $mediaService;

    public function __construct(
        Product $model,
        ProductCategoryService $productCategoryService,
        MediaService $mediaService
    ) {
        parent::__construct($model);
        $this->model = $model;
        $this->productCategoryService = $productCategoryService;
        $this->mediaService = $mediaService;
    }

    // Override
    public function create(array $data)
    {
        $data = $this->setDataToSave($data);
        $result = $this->model->create($data);
        $this->mediaService->activateImage($result->media);

        return $result;
    }

    // Override
    public function update(int $id, array $data)
    {
        $data = $this->setDataToSave($data);
        $this->model->find($id)->update($data);

        return $this->find($id);
    }

    private function setDataToSave(array $data)
    {
        $additionalData = [
            'slug' => Str::slug($data['name']),
            'status' => Constants::STATUS_ACTIVE
        ];
        return array_merge($additionalData, $data);
    }

    public function checkAvailability(array $data)
    {
        $product = $this->model->where('id', $data['product_id'])->first();

        if ($product->stock >= $data['quantity']) {
            return true;
        }

        DB::rollback();
        throw new HttpResponseException(response()->json([
            'message' => 'Validation Error',
            'errors' => [
                'quantity' => $product->name . ' does not have enough stocks.'
            ]
        ], 400));
    }

    //Override
    public function fetchActivePaginated(array $query)
    {
        $pageConfig = $this->setPageConfig($query);
        $data = $this->model->where('status', '!=', -1);
        if (array_key_exists('search', $query)) {
            $data->where(function($db) use ($query){
                $db->where('name', 'LIKE', $query['search']."%")->orWhere('name', 'LIKE', "% ".$query['search']."%");
            });
        }

        return $data->paginate($pageConfig['perPage'], ['*'], 'page', $pageConfig['page']);
    }
}
