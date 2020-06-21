<?php

namespace Tests\Integration;

use App\History;
use App\Notifications\ImportantStockUpdate;
use App\Stock;
use App\Usecases\TrackStock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackStockTest extends TestCase {

    use RefreshDatabase;

    protected function setUp() : void
    {
        parent::setUp();

        \Notification::fake();

        $this->mockClientRequest(true, 249000);

        $this->seed(\RetailerWithProductSeeder::class);

        (new TrackStock(Stock::first()))->handle();

    }

    /** @test */
    public function it_notifies_the_user()
    {
        \Notification::assertTimesSent(1, ImportantStockUpdate::class);
    }

    /** @test */
    public function it_refreshes_the_local_stock()
    {
        tap(Stock::first(), function ($stock) {
        $this->assertEquals(249000, $stock->price);
        $this->assertTrue($stock->in_stock);
        });
    }

    /** @test */
    public function it_records_to_history()
    {
        $this->assertEquals(1, History::count());
    }
}
