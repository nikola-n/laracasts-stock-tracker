<?php

namespace Tests\Unit;

use App\Clients\Client;
use App\Clients\ClientException;
use App\Clients\StockStatus;
use Facades\App\Clients\ClientFactory;
use App\Retailer;
use App\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RetailerWithProductSeeder;
use Tests\TestCase;
use Tests\Unit\Clientt as ClienttAlias;

class StockTest extends TestCase {
    use RefreshDatabase;

    /** @test */
    public function it_throws_an_exception_if_a_client_is_not_found_when_tracking()
    {
        //given i have a retailer with stock
        $this->seed(RetailerWithProductSeeder::class);
        Retailer::first()->update(['name' => 'Foo Retailer']);
        //and if the retailer doesn't have a client class
        //then an exception should be thrown
        $this->expectException(ClientException::class);
        //if i track that stock
        Stock::first()->track();
    }

    /** @test */
    public function it_updates_local_stock_status_after_being_tracked()
    {
        $this->seed(RetailerWithProductSeeder::class);
        //Client factory to determine the appropriate Client
        //checkAvailability()
//        ['available' => true, 'price' => 9900]
//        with mockery
//        ClientFactory::shouldReceive('make')->andReturn($mockClient);
//        ClientFactory::shouldReceive('make')->andReturn(new class implements Client {
//                public function checkAvailability(Stock $stock) : StockStatus
//                {
//                    return new StockStatus($available = true, $price = 99000);
//                }
//            }
//        );
        $this->mockClientRequest($available = true, $price = 99000);
//        ClientFactory::shouldReceive('make->checkAvailability')->andReturn(new StockStatus($available = true, $price = 99000));
        $stock = tap(Stock::first())->track();
        //tap returns the model itself
        $this->assertTrue($stock->in_stock);
        $this->assertEquals(99000, $stock->price);
    }
}

//class FakeClient implements Client {
//
//    public function checkAvailability(Stock $stock) : StockStatus
//    {
//        return new StockStatus($available = true, $price = 99000);
//    }
//}

//you can also reach for anonymous class
//we can also use mockery
//$clientMock = Mockery::mock(Client::class);
//$clientMock->shouldReceive('checkAvailability')->andReturn(new StockStatus($available = true, $price = 99000));
//
