<?php

namespace App;

//real time facade
use App\Clients\BestBuy;
use App\Clients\ClientException;
use App\Events\NowInStock;
use App\UseCases\TrackStock;
use Facades\App\Clients\ClientFactory;
use App\Clients\Target;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Stock extends Model {

    protected $table = 'stock';

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track() //$callback = null - no need
    {
        dispatch(new TrackStock($this));
        //TrackStock::dispatch($this);
//        (new TrackStock($this))->handle();
//       $class =  Str::studly($this->retailer->name); //BestBuy
        //it specifies the path of the class
//        $class = "App\\Clients\\" . Str::studly($this->retailer->name);
//
//        if(! class_exists($class)){
//            throw new ClientException('Clients not found for '. $this->retailer->name);
//        }
//        $status = (new $class)->checkAvailability($this);

//       $status = (new ClientFactory())->make($this->retailer)->checkAvailability($this);
//       $status = ClientFactory::make($this->retailer)->checkAvailability($this);

//        $status = $this->retailer
//            ->client()
//            ->checkAvailability($this);
//
//        if(! $this->in_stock && $status->available) {
//            event(new NowInStock($this));
//        }
//
//        //hit an api endpoint for the associated retailer
////        if($this->retailer->name === 'Best Buy')
////        {
////            $status = (new BestBuy())->checkAvailability($this);
////        }
////        if($this->retailer->name === 'Target')
////        {
////            $status = (new Target())->checkAvailability($this);
////        }
////        if($this->retailer->name === 'Walmart')
////        {
////            $status = (new Walmart())->checkAvailability($this);
////        }
//
//        $this->update([
//            'in_stock' => $status->available,
//            'price' => $status->price
//        ]);
//
//        //Fetch the up to date details for the item
//        //and then refresh the current stock record.
//
////        $this->recordHistory();
////        $this->product->recordHistory($this);
//
//        $callback && $callback($this);
    }


    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
