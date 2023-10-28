<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use CreatesApplication;

    public User $admin;

    public User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory(['role' => 'admin'])->create();
        $this->customer = User::factory(['role' => 'customer'])->create();

        // remove vite from testing
        $this->withoutVite();
    }
}
