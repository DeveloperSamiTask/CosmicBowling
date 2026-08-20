<?php

namespace Tests\Feature;

use App\Models\Frontend\Client;
use App\Models\LoyaltyCard;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoyaltyPortalTest extends TestCase
{
    use DatabaseTransactions;

    public function test_client_can_open_and_close_loyalty_portal_with_document(): void
    {
        $document = 'TEST' . strtoupper(bin2hex(random_bytes(4)));
        $client = Client::create([
            'document_id' => '07',
            'number_doc' => $document,
            'lastname_pat' => 'PORTAL',
            'lastname_mat' => 'PRUEBA',
            'names_client' => 'CLIENTE TEMPORAL',
            'birthday_client' => '2000-01-01',
            'registration_source' => 'atc',
        ]);
        LoyaltyCard::create([
            'client_id' => $client->id_client,
            'card_number' => 'TEST-' . $client->id_client,
            'current_checks' => 4,
            'total_checks' => 14,
            'current_cycle' => 2,
            'status' => LoyaltyCard::STATUS_ACTIVE,
        ]);

        $this->post(route('loyalty.portal.authenticate'), ['document' => strtolower($document)])
            ->assertRedirect(route('loyalty.portal.card'))
            ->assertSessionHas('loyalty_portal_client_id', $client->id_client);

        $this->get(route('loyalty.portal.card'))
            ->assertOk()
            ->assertSee('CLIENTE TEMPORAL')
            ->assertSee('4 / 10');

        $this->post(route('loyalty.portal.logout'))
            ->assertRedirect(route('loyalty.portal.login'))
            ->assertSessionMissing('loyalty_portal_client_id');

        $this->get(route('loyalty.portal.card'))
            ->assertRedirect(route('loyalty.portal.login'));
    }

    public function test_unknown_document_is_rejected(): void
    {
        $this->from(route('loyalty.portal.login'))
            ->post(route('loyalty.portal.authenticate'), ['document' => 'UNKNOWN999'])
            ->assertRedirect(route('loyalty.portal.login'))
            ->assertSessionHasErrors('document');
    }
}
