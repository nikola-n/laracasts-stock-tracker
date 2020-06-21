<?php

namespace App;

class Product extends Model
{
    public function track()
    {
        $this->stock->each->track();
//       $this->stock->each(function (Stock $stock){
//           $stock->track();
//           $this->recordHistory($stock);
//       }
//       );
    }

    public function inStock()
    {
//        tip: you can you magic methods whereInStock(true), but its not recommended
        return $this->stock()->where('in_stock', true)->exists();
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    //we dont need this anymore
//    public function recordHistory(Stock $stock)
//    {
//        $this->history()->create([
//            'price' => $stock->price,
//            'in_stock' => $stock->in_stock,
//            'stock_id' => $stock->id,
//        ]);
//    }

    public function history()
    {
        return $this->hasMany(History::class);
    }

}
