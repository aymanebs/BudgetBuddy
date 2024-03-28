<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;




class ExpenseControllerTest extends TestCase
{
    
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a user for testing
        $this->user = User::factory()->create();
         // Authenticate the user
        $this->actingAs($this->user);
    }

  
    public function testIndex()
    {
    Expense::factory()->count(3)->create(['user_id' => $this->user->id]);
    $response = $this->get('/api/expenses'); 
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'expenses',
        'status'
    ]);

    }

    public function testStore()
    {
        // Data for the expense
        $expenseData = [
            'description' => 'Test Expense',
            'amount' => 100,
            'category' => 'Test Category'
        ];

        // Make a POST request to store the expense
        $response = $this->post('/api/expenses', $expenseData);

        // Assert that the response is successful and the expense is created
        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'message' => 'Expense created successfully'
        ]);

        // Assert that the expense is stored in the database
        $this->assertDatabaseHas('expenses', [
            'description' => $expenseData['description'],
            'amount' => $expenseData['amount'],
            'category' => $expenseData['category'],
            'user_id' => $this->user->id
        ]);
    }

   

}
