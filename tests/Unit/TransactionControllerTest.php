<?php
namespace Tests\Unit;
use App\Models\Transaction;
use App\Models\User;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;
use App\Http\Controllers\TransactionController;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_transaction_and_returns_correct_response()
    {
        // Create a group first
        $group = Group::factory()->create();
        
        // Then create a user with the correct group_id
        $user = User::factory()->create([
            'group_id' => $group->id
        ]);
        
        // Arrange
        $validatedData = [
            'stake_amount' => 100.00,
            'user_id' => $user->id,
            'group_id' => $group->id
        ];

        // Mock the StoreTransactionRequest
        $request = Mockery::mock(StoreTransactionRequest::class);
        $request->shouldReceive('validated')
                ->once()
                ->andReturn($validatedData);

        // Get the controller instance
        $controller = app()->make(TransactionController::class);

        // Act
        $response = $controller->store($request);

        // Assert
        $this->assertEquals(201, $response->getStatusCode());
        
        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['ok']);
        $this->assertArrayHasKey('transaction', $responseData);
        
        // Verify transaction was created in database
        $this->assertDatabaseHas('transactions', [
            'stake_amount' => 100.00,
            'user_id' => $user->id,
            'group_id' => $group->id
        ]);
        
        // Verify the returned transaction data matches what we expect
        $transaction = $responseData['transaction'];
        $this->assertEquals($validatedData['stake_amount'], $transaction['stake_amount']);
        $this->assertEquals($validatedData['user_id'], $transaction['user_id']);
        $this->assertEquals($validatedData['group_id'], $transaction['group_id']);
    }
}