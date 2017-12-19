<?php

namespace App\Http\Controllers;

use App\Config;
use App\Events\NewTransaction;
use App\Events\StockTransaction;
use App\Stock;
use Illuminate\Http\Request;
use App\User;

class StockController extends Controller
{
    //

    public function buyStock(Request $request)
    {
        $limit = Config::KeyValue('stock_transactions_limit')->value;
        if($request->user()->stockTransactionTimes() >= $limit)
        {
            return view('errors.custom')->with('message', '本财年股票交易次数已上限');
        }
        $buyer = $request->user();
        $seller = User::type(0)->first();
        $amount = $request->amount;
        $buyerItem = $buyer->resources()->resid(1)->first();//money
        if(empty($stock = Stock::find($request->stock_id)))
        {
            return view('errors.custom')->with('message', '该股票不存在');
        }
        $sellerItem = $seller->resources()->resid($stock->resource->id)->first();
        $sellerAmount = $amount;
        $buyerAmount = $amount * $stock->sellPrice();
        if($buyer->type != 2)
        {
            return view('errors.custom')->with('message', '您不能进行股票交易');
        }
        if($buyerItem->amount < $buyerAmount)
        {
            return view('errors.custom')->with('message', '您的余额不足');
        }
        if($sellerAmount > $stock->sell_reamin)
        {
            $sellerAmount = $stock->sell_remain;
        }

        event(new NewTransaction($request->user(), $seller, $buyer, $sellerItem, $buyerItem, $sellerAmount, $buyerAmount, 'stock_buy'));
        event(new StockTransaction($buyer, $stock, 'buy', $sellerAmount));
        //Price Updates are written in StockTransaction Event

    }

    public function sellStock(Request $request)
    {
        $limit = Config::KeyValue('stock_transactions_limit')->value;
        if($request->user()->stockTransactionTimes() >= $limit)
        {
            return view('errors.custom')->with('message', '本财年股票交易次数已上限');
        }
        $buyer = User::type(0)->first();
        $seller = $request->user();
        $amount = $request->amount;
        $buyerItem = $buyer->resources()->resid(1)->first();//money
        if(empty($stock = Stock::find($request->stock_id)))
        {
            return view('errors.custom')->with('message', '该股票不存在');
        }
        $sellerItem = $seller->resources()->resid($stock->resource()->id)->first();
        $sellerAmount = $amount;
        $buyerAmount = $amount * $stock->buyPrice();
        if($buyer->type != 2)
        {
            return view('errors.custom')->with('message', '您不能进行股票交易');
        }
        if($sellerItem->amount < $sellerAmount)
        {
            return view('errors.custom')->with('message', '您持有的该股票不足进行交易');
        }
        if($buyerAmount > $stock->buy_remain)
        {
            $buyerAmount = $stock->buy_remain;
        }

        event(new NewTransaction($request->user(), $seller, $buyer, $sellerItem, $buyerItem, $sellerAmount, $buyerAmount, 'stock_sell'));
        event(new StockTransaction($seller, $stock, 'sell', $buyerAmount));
        //Prices Updates are written in StockTransaction Event
    }

    public function sendData(Request $request)
    {
        $response = [];
        foreach (Stock::all() as $stock)
        {
            $stockData = [];
            $stockData['id'] = $stock->id;
            $stockData['current_price'] = $stock->current_price;
            $stockData['all_prices'] = array_push($stock->history_prices, $stock->current_price);
            $stockData['company_name'] = $stock->company->name;
            $stockData['total'] = $stock->total;
            $stockData['dividend'] = $stock->dividend;
            $stockData['sell_remain'] = $stock->sell_remain;
            $stockData['buy_remain'] = $stock->buy_remain;
            array_push($response, $stockData);
        }
        return response()->json($response);
    }

    public function viewStocks(Request $request)
    {
        return view('stocks.list');
    }
}
