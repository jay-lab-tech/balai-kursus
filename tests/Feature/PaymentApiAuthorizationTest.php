<?php

namespace Tests\Feature;

use Tests\TestCase;

class PaymentApiAuthorizationTest extends TestCase
{
    public function test_guest_cannot_create_payment_through_legacy_api(): void
    {
        $this->postJson('/api/payment/create', [])->assertUnauthorized();
    }

    public function test_guest_cannot_check_payment_status_through_legacy_api(): void
    {
        $this->getJson('/api/payment/status/ORDER-UNKNOWN')->assertUnauthorized();
    }
}
