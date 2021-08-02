<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class homeController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function hotOffers(){
        $products = Product::orderBy('discount','desc')->with('rating')->paginate(15);
                return response()->json($products , 200);
    }
    public function bestSeller(){
        $products = Product::orderBy('sold','desc')->with('rating')->paginate(15);
        return response()->json($products , 200);

    }
    public function newArrival(){
        $products = Product::orderBy('updated_at','desc')->with('rating')->paginate(15);
        return response()->json($products , 200);

    }
    public function search(Request $request){
        $searchInput = '';
        $searchInput = $request->searchInput;
        $products = Product::where('name','LIKE',$searchInput.'%')->paginate(6);
        return response()->json($products , 200);

    }

    public function productCategories(){
        $categories = Brand::select('type','en_brand','ar_brand')->get()->groupBy('type');
        return response()->json($categories , 200);

    }
    
}
