<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\productDetail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class filtersController extends Controller
{

public function product($id){
        $product = Product::where('id',$id)->with('rating')->first();
        return response()->json($product , 200);
    }

public function prices(Request $request){

    $products = Product::where('type',$request->type);
    $max_price = $products->max('totalprice');
    $min_price = $products->min('totalprice');

    return response()->json([$min_price,$max_price] , 200);
}


public function products(Request $request){

    $products_type = $request->input('type',['laptop','mobile','tablet','accessories']);
    settype($products_type,'array');

    $products = Product::with('rating')->whereIn('type',$products_type)
    ->whereBetween('totalprice',[$request->input('min_price',0),$request->input('max_price',50000)]);
    
    if($request->has('brand')){
         $products->whereHas('brand',function($q) use($request){
            $q->where('en_brand',$request->brand);
        });
    }
    
    if($request->has('color')){
         $products->whereHas('productDetail',function($q) use($request){
            $q->where('color',$request->color);
        });
    }

    if($request->has('operatingSystem')){
         $products->whereHas('productDetail',function($q) use($request){
            $q->where('OperatingSystem',$request->operatingSystem);
        });
    }
    
    if($request->order_by == 'popular'){
        $products->withCount('rating')->orderBy('rating_count',$request->order_dir);
    } else{
        $products->orderBy($request->input('order_by','name'),$request->input('order_dir','ASC'));
    }

        $products = $products->get();
        return response()->json($products, 200);
}


public function productBrand(Request $request){ 
    $price_range = [$request->input('min_price',0),$request->input('max_price',50000)];

    $brands = Brand::select('ar_brand','en_brand')->where('type',$request->type)
    ->withCount(['product'=> function($q) use($price_range){
        $q->whereBetween('totalprice',$price_range);
    }]);

    $products_colors = $request->input('color',['black','blue','gold','green','grey','red','white']);
    $products_OS = $request->input('operatingSystem',['Android','DOS','IOS','Win 10']);
    settype($products_OS,'array');
    settype($products_colors,'array');


    $brands->withCount(['product'=> function($q) use($products_OS,$products_colors,$price_range){
        $q->whereBetween('totalprice',$price_range)
        ->whereHas('productDetail', function($q2) use($products_OS,$products_colors){
            $q2->whereIn('OperatingSystem',$products_OS)->whereIn('color',$products_colors);
        });
    }]);

    $brands = $brands->get();
    return response()->json($brands , 200);
    
}


public function productColor(Request $request){ 
    $price_range = [$request->input('min_price',0),$request->input('max_price',50000)];

    $colors = productDetail::whereHas('product',function($products) use($price_range,$request){
        $products->where('type',$request->type)->whereBetween('totalprice',$price_range);
    })->select('ar_color','color', DB::raw('count(color)quantity'))->groupBy('color','ar_color');


    $brands = DB::table('brands')->pluck('en_brand')->toArray();
    $products_brands = $request->input('brand',$brands);
    $products_OS = $request->input('operatingSystem',['Android','DOS','IOS','Win 10']);
    settype($products_OS,'array');
    settype($products_brands,'array');


    $colors->whereHas('product', function($product) use($price_range,$products_brands){
            $product->whereHas('brand', function($brand) use($products_brands){
                $brand->whereIn('en_brand',$products_brands);
            })->whereBetween('totalprice',$price_range);
        })->whereIn('OperatingSystem',$products_OS);


   $colors = $colors->get();
   return response()->json($colors , 200);
}



public function productOperatingSystem(Request $request){ 
    $price_range = [$request->input('min_price',0),$request->input('max_price',50000)];

    $OS = productDetail::whereHas('product',function($product) use($price_range,$request){
        $product->where('type',$request->type)->whereBetween('totalprice',$price_range);
    })->select('ar_OperatingSystem','OperatingSystem', DB::raw('count(OperatingSystem)quantity'))
    ->groupBy('OperatingSystem','ar_OperatingSystem');
    

    $brands = DB::table('brands')->pluck('en_brand')->toArray();
    $products_brands = $request->input('brand',$brands);
    $products_colors = $request->input('color',['black','blue','gold','green','grey','red','white']);
    settype($products_colors,'array');
    settype($products_brands,'array');

    $OS->whereHas('product', function($product) use($price_range,$products_brands){
        $product->whereHas('brand', function($brand) use($products_brands){
            $brand->whereIn('en_brand',$products_brands);
        })->whereBetween('totalprice',$price_range);
    })->whereIn('color',$products_colors);

    $OS = $OS->get();
    return response()->json($OS , 200);
}


public function search(Request $request){
    $searchInput = '';
    $searchInput = $request->searchInput;
    $products = Product::where('name','LIKE','%'.$searchInput.'%')->orWhere('ar_name','LIKE',$searchInput.'%')
    ->orWhere('type','LIKE',$searchInput.'%')->orWhere('ar_type','LIKE',$searchInput.'%')->paginate(10);
    return response()->json($products , 200);

}

public function productCategories(){
    $categories = Brand::select('type','en_brand','ar_brand')->get()->groupBy('type');
    return response()->json($categories , 200);
}
}
