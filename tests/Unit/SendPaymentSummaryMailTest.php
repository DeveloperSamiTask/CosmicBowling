<?php

namespace Tests\Unit;

use App\Jobs\SendPaymentSummaryMail;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class SendPaymentSummaryMailTest extends TestCase
{
    public function test_configuracion_de_reintentos(): void
    {
        $job = new SendPaymentSummaryMail([
            'email' => 'sistemas.ti@samitask.com',
            'purchaseNumber' => 999999,
            'code' => 'PRUEBA1',
        ]);

        $this->assertSame(5, $job->tries);
        $this->assertSame(60, $job->timeout);
        $this->assertSame([60, 300, 900, 1800], $job->backoff());
    }

    public function test_el_error_regresa_a_la_cola(): void
    {
        Mail::shouldReceive('to')
            ->once()
            ->with('sistemas.ti@samitask.com')
            ->andThrow(
                new RuntimeException('451 Temporary local problem - simulated')
            );

        $job = new SendPaymentSummaryMail([
            'email' => 'sistemas.ti@samitask.com',
            'purchaseNumber' => 999999,
            'code' => 'PRUEBA1',
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            '451 Temporary local problem - simulated'
        );

        $job->handle();
    }
}
