<?php


namespace Tests\Feature\Http\Controllers\API;


use App\Models\Account;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class AccountAPIControllerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->be(factory(User::class)->create(), 'api');
    }

    /** @test */
    public function it_gets_all_accounts(){
        $accounts = factory(Account::class, 10)->create();
        $response = $this->json('get', '/api/accounts');

        $response->assertJson($accounts->toArray());
    }

    /** @test */
    public function it_gets_an_account_by_id() {
        $account = factory(Account::class)->create();
        $response = $this->json('get', '/api/accounts/'.$account->id);

        $response->assertJson($account->toArray());
    }

    /** @test */
    public function it_creates_an_account(){
        $attributes = [
            'description' => 'Some Description',
            'is_department_code' => true,
            'code' => 'AAA'
        ];

        $response = $this->json('post', '/api/accounts', $attributes);

        $this->assertDatabaseHas('accounts', $attributes);
    }

    /** @test */
    public function the_description_must_be_longer_than_3_characters(){
        $attributes = [
            'description' => 'SS',
            'is_department_code' => true,
            'code' => 'AAA'
        ];

        $response = $this->json('post', '/api/accounts', $attributes);

        $response->assertJsonValidationErrors('description');
    }
    
}