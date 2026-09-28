<?php

namespace App\Console\Commands;

use App\Models\LoyaltyCard;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireLoyaltyCards extends Command
{
    protected $signature = 'loyalty:expire-cards';

    protected $description = 'Resets current loyalty checks for cards whose validity date has expired.';

    public function handle(): int
    {
        $today = now()->toDateString();
        $expiredCards = LoyaltyCard::whereNotNull('expires_at')
            ->whereDate('expires_at', '<=', $today)
            ->get();

        foreach ($expiredCards as $card) {
            DB::transaction(function () use ($card) {
                $card = LoyaltyCard::whereKey($card->getKey())->lockForUpdate()->first();

                if (!$card || !$card->expires_at || $card->expires_at->isFuture()) {
                    return;
                }

                $nextExpiration = $card->expires_at->copy();

                do {
                    $nextExpiration->addYear();
                } while ($nextExpiration->isPast());

                $card->update([
                    'current_checks' => 0,
                    'last_expired_at' => $card->expires_at->toDateString(),
                    'expires_at' => $nextExpiration->toDateString(),
                ]);
            });
        }

        $this->info("Tarjetas vencidas procesadas: {$expiredCards->count()}");

        return self::SUCCESS;
    }
}
