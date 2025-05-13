<?php

namespace Tests\Unit;

use App\Models\Amusement;
use App\Models\Group;
use App\Models\Stamp;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $group;
    protected $amusement;
    protected $stamp;

    public function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->group = Group::factory()->create();
        $this->amusement = Amusement::factory()->create();
        $this->user = User::factory()->create([
            'group_id' => $this->group->id,
            'balance' => 1000,
            'name' => "Rune",
            'email' => 'rune@yrgobanken.vip'
        ]);
        $this->stamp = Stamp::factory()->create(['animal' => 'orca',
        'premium_attribute' => 'silver']);
        
        // Create additional users in the same group
        User::factory()->count(3)->create([
            'group_id' => $this->group->id,
            'balance' => 500,
            'email' => fake()->email(),
            'name' => fake()->name()
        ]);
        
        // Authenticate user if needed
        // $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_create_transaction_with_stake_amount()
    {
        $data = [
            'amusement_id' => $this->amusement->id,
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
            'stake_amount' => 200,
            'payout_amount' => null,
        ];

        $response = $this->postJson('/api/transactions', $data);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Transaction created successfully')
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id', 'amusement_id', 'user_id', 'group_id', 
                    'stake_amount', 'created_at', 'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('transactions', [
            'amusement_id' => $this->amusement->id,
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
            'stake_amount' => 200,
        ]);
        
        // Verify user balances were updated
        // Each of the 4 users should receive 200/4 = 50 added to their balance
        $this->user->refresh();
        $otherUsers = User::where('id', '!=', $this->user->id)
            ->where('group_id', $this->group->id)
            ->get();
            
        // The original user's balance should be 1000 + 50 = 1050
        $this->assertEquals(1050, $this->user->balance);
        
        // Other users should have 500 + 50 = 550
        foreach ($otherUsers as $otherUser) {
            $this->assertEquals(550, $otherUser->balance);
        }
    }

    /** @test */
    public function it_can_create_transaction_with_payout_amount()
    {
        $data = [
            'amusement_id' => $this->amusement->id,
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
            'stake_amount' => null,
            'payout_amount' => 400, // 100 per user
        ];

        $response = $this->postJson('/api/transactions', $data);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Transaction created successfully')
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id', 'amusement_id', 'user_id', 'group_id', 
                    'payout_amount', 'created_at', 'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('transactions', [
            'amusement_id' => $this->amusement->id,
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
            'payout_amount' => 400,
        ]);
        
        // Verify user balances were updated
        // Each of the 4 users should have 400/4 = 100 deducted from their balance
        $this->user->refresh();
        $otherUsers = User::where('id', '!=', $this->user->id)
            ->where('group_id', $this->group->id)
            ->get();
            
        // The original user's balance should be 1000 - 100 = 900
        $this->assertEquals(900, $this->user->balance);
        
        // Other users should have 500 - 100 = 400
        foreach ($otherUsers as $otherUser) {
            $this->assertEquals(400, $otherUser->balance);
        }
    }

    /** @test */
    public function it_cannot_create_transaction_with_both_stake_and_payout_amounts()
    {
        $data = [
            'amusement_id' => $this->amusement->id,
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
            'stake_amount' => 100,
            'payout_amount' => 200,
        ];

        $response = $this->postJson('/api/transactions', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
            
        $this->assertDatabaseCount('transactions', 0);
    }

    /** @test */
    public function it_cannot_create_transaction_without_stake_or_payout_amounts()
    {
        $data = [
            'amusement_id' => $this->amusement->id,
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
            'stake_amount' => null,
            'payout_amount' => null,
        ];

        $response = $this->postJson('/api/transactions', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
            
        $this->assertDatabaseCount('transactions', 0);
    }

    /** @test */
    public function it_cannot_create_transaction_with_stake_amount_exceeding_user_balance()
    {
        // Set user balance lower than stake amount
        $this->user->update(['balance' => 50]);
        
        $data = [
            'amusement_id' => $this->amusement->id,
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
            'stake_amount' => 100,
            'payout_amount' => null,
        ];

        $response = $this->postJson('/api/transactions', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['stake_amount']);
            
        $this->assertDatabaseCount('transactions', 0);
    }

    /** @test */
    public function it_cannot_create_transaction_with_payout_amount_exceeding_group_balance()
    {
        // Set all users' balances to a low amount
        User::where('group_id', $this->group->id)->update(['balance' => 10]);
        
        $data = [
            'amusement_id' => $this->amusement->id,
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
            'stake_amount' => null,
            'payout_amount' => 1000, // Exceeds total group balance of 40 (4 users × 10)
        ];

        $response = $this->postJson('/api/transactions', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['payout_amount']);
            
        $this->assertDatabaseCount('transactions', 0);
    }
    
    /** @test */
    public function it_cannot_create_transaction_when_individual_user_has_insufficient_balance_for_payout()
    {
        // Set one user to have insufficient balance
        $lowBalanceUser = User::where('group_id', $this->group->id)
            ->where('id', '!=', $this->user->id)
            ->first();
        $lowBalanceUser->update(['balance' => 5]);
        
        $data = [
            'amusement_id' => $this->amusement->id,
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
            'stake_amount' => null,
            'payout_amount' => 400, // 100 per user, exceeding the low balance user's 5
        ];

        $response = $this->postJson('/api/transactions', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['payout_amount']);
            
        $this->assertDatabaseCount('transactions', 0);
    }
    
    /** @test */
    public function it_validates_stamp_id_when_stake_amount_is_provided()
    {
        $data = [
            'amusement_id' => $this->amusement->id,
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
            'stake_amount' => 100,
            'payout_amount' => null,
            'stamp_id' => $this->stamp->id,
        ];

        $response = $this->postJson('/api/transactions', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['stamp_id']);
            
        $this->assertDatabaseCount('transactions', 0);
    }
    
    /** @test */
    public function it_allows_stamp_id_when_only_payout_amount_is_provided()
    {
        $data = [
            'amusement_id' => $this->amusement->id,
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
            'stake_amount' => null,
            'payout_amount' => 100,
            'stamp_id' => $this->stamp->id,
        ];

        $response = $this->postJson('/api/transactions', $data);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Transaction created successfully');
            
        $this->assertDatabaseHas('transactions', [
            'amusement_id' => $this->amusement->id,
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
            'payout_amount' => 100,
            'stamp_id' => $this->stamp->id,
        ]);
    }
    
    /** @test */
    public function it_returns_error_on_invalid_resource_ids()
    {
        $data = [
            'amusement_id' => 999, // Non-existent ID
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
            'stake_amount' => 100,
            'payout_amount' => null,
        ];

        $response = $this->postJson('/api/transactions', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['amusement_id']);
    }
}