<?php

namespace Tests\Feature;

use App\Notifications\ImportantStockUpdate;
use App\User;
use Facades\App\Clients\ClientFactory;
use App\Clients\StockStatus;
use App\Product;
use App\Retailer;
use App\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use RetailerWithProductSeeder;
use Tests\TestCase;

class TrackCommandTest extends TestCase {

    use RefreshDatabase;

    protected function setUp() : void
    {
        parent::setUp();

        Notification::fake();

        $this->seed(RetailerWithProductSeeder::class);

    }

    /** @test */
    public function it_tracks_product_stock()
    {
//        $this->seed(RetailerWithProductSeeder::class);

        $this->assertFalse(Product::first()->inStock());

        $this->mockClientRequest();
//        ClientFactory::shouldReceive('make->checkAvailability')
//            ->andReturn(new StockStatus($available = true, $price = 29900));
//        Http::fake(fn() =>['available' => true, 'price' => 29900]);
        //when i trigger the php artisan track command
        //and assuming the stock is available now
        $this->artisan('track')
            ->expectsOutput('All done!');
        //then the stock details should be refreshed
        $this->assertTrue(Product::first()->inStock());
    }

//    /** @test */
//    public function it_notifies_the_user_when_the_stock_is_now_available()
//    {
////        Notification::fake();
//        //given i have a user
////        $user = factory(User::class)->create(['email' => 'nikola@thecodeconnectors.nl']);
//        // and a product
////        $this->seed(RetailerWithProductSeeder::class);
//
//        $this->mockClientRequest();
//
////        ClientFactory::shouldReceive('make->checkAvailability')
////            ->andReturn(new StockStatus($available = true, $price = 29900));
//        //when i track the product
//        $this->artisan('track');
//        //if the stock changes in a notable way after being tracked
//        //then the user should be notified
//        Notification::assertSentTo(User::first(), ImportantStockUpdate::class);
//    }
//
//    /** @test */
//    public function it_does_not_notify_when_the_stock_remains_unavailable()
//    {
////        Notification::fake();
//        //given i have a user
////        $user = factory(User::class)->create(['email' => 'nikola@thecodeconnectors.nl']);
//        // and a product
////        $this->seed(RetailerWithProductSeeder::class);
//
//        $this->mockClientRequest($available = false);
//        //when i track the product
//        $this->artisan('track');
//        //if the stock changes in a notable way after being tracked
//        //then the user should be notified
//        Notification::assertNothingSent();
//    }

}
