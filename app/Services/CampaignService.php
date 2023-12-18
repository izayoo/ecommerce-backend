<?php

namespace App\Services;

use App\Enum\Constants;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CampaignService extends BaseService
{
    private MediaService $mediaService;

    public function __construct(
        Campaign $model,
        MediaService $mediaService
    ) {
        parent::__construct($model);
        $this->model = $model;
        $this->mediaService = $mediaService;
    }

    // Override
    public function create(array $data)
    {
        $data = $this->setDataToSave($data);
        $result = $this->model->create($data);
        $this->mediaService->activateImage($result->media);
        $this->mediaService->activateImage($result->banner);

        return $result;
    }

    // Override
    public function update(int $id, array $data)
    {
        $data = $this->setDataToSave($data);
        $this->model->find($id)->update($data);
        $result = $this->find($id);
        $this->mediaService->activateImage($result->media);
        $this->mediaService->activateImage($result->banner);

        return $result;
    }

    private function setDataToSave(array $data)
    {
        $additionalData = [
            'slug' => Str::slug($data['name']),
            'status' => Constants::STATUS_ACTIVE
        ];
        return array_merge($additionalData, $data);
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

    public function fetchFeatured()
    {
        return $this->model->where('is_featured', 1)->where('status', 1)->get();
    }

    public function fetchCurrentCampaigns(int $category = null)
    {
        $data = $this->model->where('start_date', '<=', Carbon::now()->toDateTime())->where('end_date', '>=', Carbon::now()->toDateTime());
        if ($category) $data = $data->where('campaign_category_id', $category);

        return $data->where('status', 1)->get();
    }

    public function findSuggested(int $id)
    {
        return $this->model->where('id', '!=', $id)->inRandomOrder()->limit(3)->get();
    }

    public function fetchCampaignsByProduct(int $id)
    {
        return $this->model->where('status', 1)->where('product_id', $id)->get();
    }
}
