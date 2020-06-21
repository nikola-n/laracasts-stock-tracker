<?php

namespace Tests\Clients;

use App\Clients\BestBuy;
use App\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use RetailerWithProductSeeder;
use Tests\TestCase;

//not to hit the api everytime you run this test
/**
 * vendor/bin/phpunit -exclude-group api
 * @group api
 */
class BestBuyTest extends TestCase {

 use RefreshDatabase;
    /** @test @doesNotPerformAssertions */

    public function it_tracks_a_product()
    {
        //given i have a product
        $this->seed(RetailerWithProductSeeder::class);

        //with stock at BestBuy
        $stock = tap(Stock::first())->update([
            'sku' => '6364253',            //nintento switch sku
            'url' => 'https://www.bestbuy.com/site/nintendo-switch-32gb-console-gray-joy-con/6364253.p?skuId=6364253',
        ]);
        //if i use the BestBuy client to track stock/sku
       try {
           $stockStatus = (new BestBuy())->checkAvailability($stock);
       } catch(\Exception $e) {
           $this->fail("Failed to track the BestBuy Api properly. " . $e->getMessage());
       }
        //it should return the appropriate StockStatus
        $this->assertTrue(true);
    }

    /** @test */
    public function it_creates_the_proper_stock_status_response()
    {
        Http::fake(fn() => ['salePrice' => 299.99, 'onlineAvailability' => true]);

       $stockStatus = (new BestBuy())->checkAvailability(new Stock);
        $this->assertEquals(29999, $stockStatus->price);
        $this->assertTrue($stockStatus->available);
    }
}
