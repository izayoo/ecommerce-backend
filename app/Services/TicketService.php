<?php

namespace App\Services;

use App\Enum\Constants;
use App\Models\OrderProduct;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class TicketService extends BaseService
{
    private Ticket $model;

    public function __construct(Ticket $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function findByTicketNo(string $ticketNo)
    {
        return $this->model->where('ticket_no', $ticketNo)->first();
    }

    public function fetchActiveByCampaign(int $id)
    {
        return $this->model->whereHas('orderProduct', function($query) use ($id) {
            $query->where('campaign_id', $id);
        })->get();
    }

    public function fetchActivePaginatedByCampaign(int $id, array $query)
    {
        $pageConfig = $this->setPageConfig($query);
        $data = $this->model->with('orderProduct')->whereHas('orderProduct', function($query) use ($id) {
            $query->where('campaign_id', $id);
        });

        if (array_key_exists('search', $query)) {
            $data->where(function($db) use ($query){
                $db->where('ticket_no', 'LIKE', $query['search']."%")->orWhere('ticket_no', 'LIKE', "% ".$query['search']."%");
            });
        }

        return $data->paginate($pageConfig['perPage'], ['*'], 'page', $pageConfig['page']);
    }

    public function findActiveByCampaign(int $id, int $ticketId)
    {
        return $this->model->where('status', '!=', -1)->where('campaign_id', $id)->where('ticket_no', $ticketId)->find();
    }

    public function setAsWinner(int $id)
    {
        DB::beginTransaction();
        $ticket = $this->find($id);
        $ticket->status = Constants::TICKET_STATUS_WON;
        $campaign = $ticket->orderProduct->campaign_id;
        $orderProducts = OrderProduct::where('campaign_id', $campaign)
            ->where('id', '!=', $ticket->id)->get()->pluck('id');
        $endTickets = $this->model->whereIn('order_product_id', $orderProducts)
            ->update(['status' => Constants::TICKET_STATUS_EXPIRED]);
        if ($endTickets) {
            $ticket->save();
            DB::commit();
        } else {
            DB::rollBack();
        }
        return $ticket;
    }

    public function getActiveTicketsByUser(int $userId)
    {
        return $this->model->with('orderProduct')
            ->whereHas('order', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();
    }

    public function getActiveTicketsByCampaignAndUser(int $campaignId)
    {
        $userId = auth()->user()->getAuthIdentifier();

        return $this->model->with('orderProduct', 'order')
            ->whereHas('orderProduct', function($query) use ($campaignId) {
                $query->where('campaign_id', $campaignId);
            })->whereHas('order', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();
    }

    public function findWinners()
    {
        return $this->model->where('status', Constants::TICKET_STATUS_WON)->orderBy('updated_at', 'DESC')->get();
    }
}
