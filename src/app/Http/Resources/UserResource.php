<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request): array
    {
        /** @var User $this */

        return [
            'id' => (int)$this->id,
            'c_id' => $this->channel_id,
            'l_id' => $this->link_id,
            'hash' => $this->hash,
            'name' => $this->name,
            'activity' => (boolean)$this->activity,
            'has_recharge' => (boolean)$this->hasRecharge(),
            'day_interest' => $this->day_interest,
            'unread_notifications_count' => $this->unreadNotifications()->count(),
            'parent' => FriendResource::make($this->whenLoaded('parent')),
            'invite' => UserInviteResource::make($this->whenLoaded('invite')),
            'invite_award' => (float)data_get($this->whenLoaded('inviteAward'), 'give_balance'),//下线总收益
            'wallet' => UserWalletResource::make($this->whenLoaded('wallet')),
            'money_bao' => UserMoneyBaoResource::make($this->whenLoaded('moneyBao')),
            'product_data' => $this->productData(),
            'all_property' => $this->all_property,
            'created_at' => $this->created_at,
        ];


    }
}
