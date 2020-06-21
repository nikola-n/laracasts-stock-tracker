<?php

namespace Tests\Unit;

use Facades\App\Clients\ClientFactory;
use App\Clients\StockStatus;
use App\History;
use App\Product;
use App\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use RetailerWithProductSeeder;
use Tests\TestCase;

class ProductHistoryTest extends TestCase {
    use RefreshDatabase;

    /** @test */
    public function it_records_history_each_time_stock_is_tracked()
    {
        //given i have a stock at a retailer
        $this->seed(RetailerWithProductSeeder::class);

        $this->mockClientRequest($available = true, $price = 9900);
//        ClientFactory::shouldReceive('make->checkAvailability')
//            ->andReturn(new StockStatus($available = true, $price = 99));
//        Http::fake(fn() => ['salePrice' => 99, 'onlineAvailability' => true]);

        $product = tap(Product::first(), function ($product) {

            $this->assertCount(0, $product->history);
            //if i track that stock
//        $stock = tap(Stock::first())->track();
            // a new history entry should be created
            $product->track();

            //must refresh database because whenever you load those relationships they are cashed
            $this->assertCount(1, $product->refresh()->history);
        });

        $history = $product->history->first();

        $this->assertEquals($price, $history->price);
        $this->assertEquals($available, $history->in_stock);
        $this->assertEquals($product->id, $history->product_id);
        $this->assertEquals($product->stock[0]->id, $history->stock_id);
    }
}
