<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FactoryAndSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {

    }

    protected function test_user_factory(): void
    {
        $user = User::factory()->make();

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
        $this->assertNotNull($user->email_verified_at);
        $this->assertNotNull($user->password);
        $this->assertNotNull($user->remember_token);

        $user = User::factory()->make();
        $this->assertDatabaseMissing('users', ['email' => $user->email]);

        $user = User::factory()->make();
        $this->assertDatabaseHas('users', ['email' => $user->email]);

    }

    protected function test_user_seeder(): void
    {
        $this->seed(UserSeeder::class);
        $this->assertDatabaseCount('users', 10);
    }

    protected function test_supplier_factory(): void
    {
        $supplier = Supplier::facotry()->make();

        $this->assertInstanceOf(Supplier::class, $supplier);
        $this->assertNotNull($supplier->name);
        $this->assertNotNull($supplier->address);
        $this->assertNotNull($supplier->phone);
        $this->assertNotNull($supplier->email);

        $supplier = Supplier::factory()->make();
        $this->assertDatabaseHas('suppliers', ['name' => $supplier->name]);

        $supplier = Supplier::factory()->make();
        $this->assertDatabaseHas('suppliers', ['name' => $supplier->name]);
    }

        protected function test_supplier_seeder(): void
    {
        $this->seed(SupplierSeeder::class);
        $this->assertDatabaseCount('suppliers', 10);
    }
}
