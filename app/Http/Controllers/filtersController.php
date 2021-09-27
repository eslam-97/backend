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
    $products = Product::with('rating');


    if($request->has('type')){
      $products->where('type',$request->type);

    }
    if($request->has('order_by') && $request->has('order_dir')){
        if($request->order_by == 'popular'){
             $products->withCount('rating')->orderBy('rating_count',$request->order_dir);
        } else{

         $products->orderBy($request->order_by,$request->order_dir);
    }}

    if($request->has('min_price') && $request->has('max_price')){
         $products->where([
             ['price', '>=',$request->min_price],
             ['price', '<=', $request->max_price]
         ]);
    }

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
    
        $products = $products->get();
        return response()->json($products, 200);
}



public function productBrand(Request $request){ 
    $brands = Brand::select('ar_brand','en_brand')->where('type',$request->type)->withCount('product');

    if($request->has('min_price') && $request->has('max_price')){
        $brands->withCount(['product'=> function($q) use($request){
            $q->where([
                ['price', '>=',$request->min_price],
                ['price', '<=', $request->max_price]
            ]);
        }]);  
   }

    if($request->has('color')){
        $brands->withCount(['product'=> function($q) use($request){
            $q->whereHas('productDetail', function($q2) use($request){
                $q2->where('color', $request->color);
            });
        }]);
        if($request->has('min_price') && $request->has('max_price')){
            $brands->withCount(['product'=> function($q) use($request){
                $q->where([
                    ['price', '>=',$request->min_price],
                    ['price', '<=', $request->max_price]
                    ])->whereHas('productDetail', function($q2) use($request){
                    $q2->where('color', $request->color);
                });
            }]);
        }
    }

    if($request->has('OperatingSystem')){
        $brands->withCount(['product'=> function($q) use($request){
                $q->whereHas('productDetail', function($q2) use($request){
                    $q2->where('OperatingSystem', $request->OperatingSystem);
                });
        }]);
        if($request->has('min_price') && $request->has('max_price')){
            $brands->withCount(['product'=> function($q) use($request){
                $q->where([
                    ['price', '>=',$request->min_price],
                    ['price', '<=', $request->max_price]
                ])->whereHas('productDetail', function($q2) use($request){
                    $q2->where('OperatingSystem', $request->OperatingSystem);
                });
        }]);
        }
    }

    if($request->has('OperatingSystem') && $request->has('color')){
        $brands->withCount(['product'=> function($q) use($request){
            $q->whereHas('productDetail', function($q2) use($request){
                $q2->where([['color', $request->color],['OperatingSystem', $request->OperatingSystem]]);
            });
        }]);
        if($request->has('min_price') && $request->has('max_price')){
            $brands->withCount(['product'=> function($q) use($request){
                $q->where([
                    ['price', '>=',$request->min_price],
                    ['price', '<=', $request->max_price]
                ])->whereHas('productDetail', function($q2) use($request){
                    $q2->where([['color', $request->color],['OperatingSystem', $request->OperatingSystem]]);
                });
            }]);
        }
    }


    $brands = $brands->get();
    return response()->json($brands , 200);
    
}


public function productColor(Request $request){ 
    $colors = productDetail::whereHas('product',function($q) use($request){
        $q->where('type',$request->type);
    })->select('ar_color','color', DB::raw('count(color)quantity'))->groupBy('color','ar_color');


    if($request->has('min_price') && $request->has('max_price')){
        $colors->whereHas('product', function($q) use($request){
            $q->where([
                ['price', '>=',$request->min_price],
                ['price', '<=', $request->max_price]
            ]);
        });  
   }

    if($request->has('brand')){
        $colors->whereHas('product', function($q) use($request){
            $q->whereHas('brand', function($q2) use($request){
                $q2->where('en_brand',$request->brand);
            });
        });
        if($request->has('min_price') && $request->has('max_price')){
            $colors->whereHas('product', function($q) use($request){
                $q->where([
                    ['price', '>=',$request->min_price],
                    ['price', '<=', $request->max_price]
                ])->whereHas('brand', function($q2) use($request){
                    $q2->where('en_brand',$request->brand);
                });
            });
        }
    }

    if($request->has('OperatingSystem')){
        $colors->where('OperatingSystem',$request->OperatingSystem);

        if($request->has('min_price') && $request->has('max_price')){
            $colors->where('OperatingSystem',$request->OperatingSystem)->whereHas('product', function($q) use($request){
                $q->where([
                    ['price', '>=',$request->min_price],
                    ['price', '<=', $request->max_price]
                ]);
            });
        }
    }

    if($request->has('OperatingSystem') && $request->has('brand')){
        $colors->whereHas('product', function($q) use($request){
            $q->whereHas('brand', function($q2) use($request){
                $q2->where('en_brand',$request->brand);
            });
        })->where('OperatingSystem',$request->OperatingSystem);

        if($request->has('min_price') && $request->has('max_price')){
            $colors->whereHas('product', function($q) use($request){
                $q->where([
                    ['price', '>=',$request->min_price],
                    ['price', '<=', $request->max_price]
                ])->whereHas('brand', function($q2) use($request){
                    $q2->where('en_brand',$request->brand);
                });
            })->where('OperatingSystem',$request->OperatingSystem);
        }
    }

    
   $colors = $colors->get();
   return response()->json($colors , 200);
}



public function productOperatingSystem(Request $request){ 
    $OS = productDetail::whereHas('product',function($q) use($request){
        $q->where('type',$request->type);
    })->select('ar_OperatingSystem','OperatingSystem', DB::raw('count(OperatingSystem)quantity'))
    ->groupBy('OperatingSystem','ar_OperatingSystem');
    

    if($request->has('min_price') && $request->has('max_price')){
        $OS->whereHas('product', function($q) use($request){
            $q->where([
                ['price', '>=',$request->min_price],
                ['price', '<=', $request->max_price]
            ]);
        });  
   }

    if($request->has('brand')){
        $OS->whereHas('product', function($q) use($request){
            $q->whereHas('brand', function($q2) use($request){
                $q2->where('en_brand',$request->brand);
            });
        });

        if($request->has('min_price') && $request->has('max_price')){
            $OS->whereHas('product', function($q) use($request){
                $q->where([
                    ['price', '>=',$request->min_price],
                    ['price', '<=', $request->max_price]
                ])->whereHas('brand', function($q2) use($request){
                    $q2->where('en_brand',$request->brand);
                });
            });
        }
    }

    if($request->has('color')){
        $OS->where('color',$request->color);

        if($request->has('min_price') && $request->has('max_price')){
            $OS->where('color',$request->color)->whereHas('product', function($q) use($request){
                $q->where([
                    ['price', '>=',$request->min_price],
                    ['price', '<=', $request->max_price]
                ]);
            });
        }
    }

    if($request->has('brand') && $request->has('color')){
        $OS->whereHas('product', function($q) use($request){
            $q->whereHas('brand', function($q2) use($request){
                $q2->where('en_brand',$request->brand);
            });
        })->where('color',$request->color);

        if($request->has('min_price') && $request->has('max_price')){
            $OS->whereHas('product', function($q) use($request){
                $q->where([
                    ['price', '>=',$request->min_price],
                    ['price', '<=', $request->max_price]
                ])->whereHas('brand', function($q2) use($request){
                    $q2->where('en_brand',$request->brand);
                });
            })->where('color',$request->color);
        }
    }

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
