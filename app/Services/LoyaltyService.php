<?php

namespace App\Services;

use App\Models\Frontend\Client;
use App\Models\LoyaltyCard;
use App\Models\LoyaltyManualPurchase;
use App\Models\LoyaltyMovement;
use App\Models\LoyaltyReward;
use App\Models\LoyaltyRewardCatalog;
use Illuminate\Support\Facades\DB;

class LoyaltyService
{
    public function registerManualPurchase(array $data, int $userId): array
    {
        return DB::transaction(function () use ($data, $userId) {
            $client = Client::where('document_id', $data['document_id'])
                ->where('number_doc', $data['number_doc'])
                ->lockForUpdate()
                ->first();

            if (!$client) {
                $client = Client::create([
                    'document_id' => $data['document_id'],
                    'number_doc' => $data['number_doc'],
                    'lastname_pat' => $data['lastname_pat'],
                    'lastname_mat' => $data['lastname_mat'],
                    'names_client' => $data['names_client'],
                    'phone_client' => $data['phone_client'] ?? null,
                    'birthday_client' => $data['birthday_client'],
                    'address_client' => $data['address_client'] ?? null,
                    'email_client' => null,
                    'password_client' => null,
                    'registration_source' => 'atc',
                ]);
            }

            $card = LoyaltyCard::where('client_id', $client->id_client)
                ->lockForUpdate()
                ->first();

            if (!$card) {
                $card = LoyaltyCard::create([
                    'client_id' => $client->id_client,
                    'card_number' => $this->generateCardNumber($client->id_client),
                    'current_checks' => 0,
                    'total_checks' => 0,
                    'current_cycle' => 1,
                    'status' => LoyaltyCard::STATUS_ACTIVE,
                ]);
            }

            // Solo se puede consumir un premio que ya estaba pendiente antes de esta compra.
            $rewardToRedeem = LoyaltyReward::where('card_id', $card->id)
                ->where('status', LoyaltyReward::STATUS_PENDING)
                ->orderBy('earned_at')
                ->orderBy('id')
                ->lockForUpdate()
                ->first();

            $purchase = LoyaltyManualPurchase::create([
                'client_id' => $client->id_client,
                'subcategory_id' => $data['subcategory_id'],
                'receipt_number' => $data['receipt_number'] ?? null,
                'total_hours' => $data['total_hours'],
                'quantity_lane' => $data['quantity_lane'],
                'quantity_hours' => $data['quantity_hours'],
                'amount' => $data['amount'] ?? null,
                'purchased_at' => $data['purchased_at'],
                'registered_by' => $userId,
                'notes' => $data['notes'] ?? null,
            ]);

            $checksBefore = $card->current_checks;
            $cycleBefore = $card->current_cycle;
            $balance = $checksBefore;
            $cycle = $cycleBefore;
            $earnedMilestones = [];

            for ($check = 0; $check < $data['total_hours']; $check++) {
                $balance++;

                if ($balance === 5 || $balance === 10) {
                    $earnedMilestones[] = ['milestone' => $balance, 'cycle' => $cycle];
                }

                if ($balance === 10) {
                    $balance = 0;
                    $cycle++;
                }
            }

            $card->update([
                'current_checks' => $balance,
                'total_checks' => $card->total_checks + $data['total_hours'],
                'current_cycle' => $cycle,
            ]);

            $movement = LoyaltyMovement::create([
                'card_id' => $card->id,
                'manual_purchase_id' => $purchase->id,
                'movement_type' => LoyaltyMovement::TYPE_MANUAL_PURCHASE,
                'checks' => $data['total_hours'],
                'checks_before' => $checksBefore,
                'checks_after' => $balance,
                'cycle_before' => $cycleBefore,
                'cycle_after' => $cycle,
                'description' => 'Compra presencial/WhatsApp'
                    . (!empty($data['receipt_number']) ? ' - Boleta ' . $data['receipt_number'] : '')
                    . ($rewardToRedeem ? ' - Premio utilizado #' . $rewardToRedeem->id : ''),
                'registered_by' => $userId,
            ]);

            if ($rewardToRedeem) {
                $rewardToRedeem->update([
                    'status' => LoyaltyReward::STATUS_USED,
                    'redeemed_at' => now(),
                    'redeemed_by' => $userId,
                ]);
            }

            $catalog = LoyaltyRewardCatalog::where('active', true)
                ->whereIn('milestone', [5, 10])
                ->get()
                ->keyBy('milestone');

            foreach ($earnedMilestones as $earned) {
                $rewardDefinition = $catalog->get($earned['milestone']);

                if (!$rewardDefinition) {
                    continue;
                }

                LoyaltyReward::firstOrCreate(
                    [
                        'card_id' => $card->id,
                        'reward_catalog_id' => $rewardDefinition->id,
                        'cycle_number' => $earned['cycle'],
                    ],
                    [
                        'earned_from_movement_id' => $movement->id,
                        'milestone' => $earned['milestone'],
                        'reward_name' => $rewardDefinition->name,
                        'reward_description' => $rewardDefinition->description,
                        'status' => LoyaltyReward::STATUS_PENDING,
                        'earned_at' => now(),
                    ]
                );
            }

            return [
                'client' => $client->fresh([
                    'loyaltyCard.rewards' => fn ($query) => $query
                        ->where('status', LoyaltyReward::STATUS_PENDING)
                        ->orderBy('earned_at')
                        ->orderBy('id'),
                ]),
                'redeemed_reward' => $rewardToRedeem,
            ];
        });
    }

    private function generateCardNumber(int $clientId): string
    {
        return 'CBF-' . str_pad((string) $clientId, 10, '0', STR_PAD_LEFT);
    }
}
