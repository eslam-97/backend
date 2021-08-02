<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\productDetail;
use App\Models\rating;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class filtersController extends Controller
{
public function allProducts(Request $request){
    $products = Product::where('type',$request->type)->with('rating')->get();
    return response()->json($products , 200);
}
public function product(Request $request){
    $product = Product::where('id',$request->id)->with('rating')->first();
    return response()->json($product , 200);
}

  public function productBrand(Request $request){ 
    $brands = Brand::select('ar_brand','en_brand')->where('type',$request->type)->withCount('product')->get();

    return response()->json($brands , 200);
    
}
public function productColor(Request $request){ 
    $colors = productDetail::whereHas('product',function($q) use($request){
        $q->where('type',$request->type);
    })->select('ar_color','color', DB::raw('count(color)quantity'))->groupBy('color','ar_color')->get();

    return response()->json($colors , 200);
}

public function productOperatingSystem(Request $request){ 
    $OS = productDetail::whereHas('product',function($q) use($request){
        $q->where('type',$request->type);
    })->select('ar_OperatingSystem','OperatingSystem', DB::raw('count(OperatingSystem)quantity'))->groupBy('OperatingSystem','ar_OperatingSystem')->get();
  
    return response()->json($OS , 200);
}

public function productByBrand(Request $request){
    $products = Product::where('type',$request->type)->whereHas('brand',function($q) use($request){
        $q->where('en_brand',$request->brand);
    })->with('rating')->get();
    return response()->json($products , 200);
}
public function productByColor(Request $request){
    $products = Product::where('type',$request->type)->whereHas('productDetail',function($q) use($request){
        $q->where('color',$request->color);
    })->with('rating')->get();
    return response()->json($products , 200);
}
public function productByOperatingSystem(Request $request){
    $products = Product::where('type',$request->type)->whereHas('productDetail',function($q) use($request){
        $q->where('OperatingSystem',$request->operatingSystem);
    })->with('rating')->get();
    return response()->json($products , 200);
}
}
