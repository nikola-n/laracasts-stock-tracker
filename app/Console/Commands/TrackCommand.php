<?php

namespace App\Console\Commands;

use App\Product;
use Illuminate\Console\Command;

class TrackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Track all product stock';



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $products = Product::all();
        //start a progress bar
        $this->output->progressStart($products->count());
        //track each product while ticking the progressbar
        Product::all()->each(function ($product) {
            $product->track();

            $this->output->progressAdvance();
        });


        //select name,price, url, in_stock from products
        //left join stock on stock.product_id = products.id
        $this->showResults();
    }

    protected function showResults() : void
    {
        $this->output->progressFinish();
        $data = Product::query()
                    ->leftJoin('stock', 'stock.product.id', '=', 'products.id')
                    ->get($this->keys());
        //finish the progress bar to 100%
        //output the results as a table
        $this->table(
            array_map('ucwords',$this->keys()),
            $data

        );
    }

    protected function keys() : array
    {
        return ['name', 'price', 'url', 'in_stock'];
    }
}
