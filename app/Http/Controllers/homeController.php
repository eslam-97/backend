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
use Illuminate\Support\Facades\DB;

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
        // select('color', DB::raw('count(color)quantity'))->groupBy('color')->get();
        // $categories = Brand::select('type', DB::raw('groupBy(type)quantity'))->groupBy('type')->get();
        $categories = Brand::select('type','en_brand','ar_brand')->get()->groupBy('type');
        // $categories =  Brand::select('type',DB::raw('select(en_brand)'))->get();

        return response()->json($categories , 200);

    }

    // public function tablet(){
    //     $products = Product::where('type','TABLET')->with('rating')->paginate(12);
    //     return response()->json($products , 200);

    //     // return view('welcome',compact('products'));
    // }
    // public function acessories(){
    //     $products = Product::where('type','ACCESSORIES')->with('rating')->paginate(12);
    //     return response()->json($products , 200);

    //     // return view('welcome',compact('products'));
    // }
    // public function brandProducts(Request $req){
    //     $products = $req->min;
    //     return response()->json($products , 200);
    // }
    
}
