<?php

namespace Tests\Feature;

use Tests\TestCase;

class BillingSystemTest extends TestCase
{
    public function test_login_page_is_available(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }
}
