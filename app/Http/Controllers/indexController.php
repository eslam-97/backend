<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\rating;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class indexController extends Controller
{
    public function productSpecs(Request $request){
        if($request->lang == 'en'){
        $specs = Product::find($request->id)->productDetail;
        $specs->makeHidden('ar_OperatingSystem');
        }elseif($request->lang == 'ar'){
            $specs = Product::find($request->id)->productDetail;
            $specs->makeHidden('OperatingSystem');
        }
        return response()->json($specs , 200);
    }


    public function ratingUsers(Request $request){

        $rating = Product::find($request->id)->rating;
        $users = [] ;

        foreach ($rating as $rate){
            $user = rating::find($rate->id)->user;
            array_push($users, $user);
        }

        return response()->json($users, 200);
    }


    public function addToUserCart(Request $request){

        $user = User::find($request->usersId);

        if(is_null($user->cart)){
            $cart = new Cart();
            $cart->users_id = $request->usersId;
            $cart->save();

        } else{
           $cart =  $user->cart;
        }

        if(!is_null($cart->product->where('id',$request->productId)->first())){

            $cart->product()->detach($request->productId);
            $cart->product()->attach($request->productId, ['quantity' =>  $request->quantity]);
            $cart->product()->syncWithoutDetaching($request->productId);

        }else{

            $cart->product()->attach($request->productId, ['quantity' =>  $request->quantity]);
            $cart->product()->syncWithoutDetaching($request->productId);
        }

        return response()->json(["status" => "succes", "message" => "product added Successfully"],200);

    }

    public function userCartProducts(Request $request){

        $user = User::find($request->id);
        $cart = $user->cart;
        if(is_null($cart)){
            return response()->json(["status" => "failed"], 204);
        }
        $cartProducts = $cart->product;
        return response()->json( $cart, 200);

    }


    public function deleteCartProduct(Request $request){

        $user = User::find($request->userId);
        $cart = $user->cart;
        $cartProducts = $cart->product;

        if($cart->product()->detach($request->productId)){
        return response()->json( ["status" => "succes", "message" => "product added Successfully"],200);
        }
    }

    public function addToUserWishList(Request $request){
        
        $user = User::find($request->usersId);

        if(is_null($user->wishlist)){
            $wishlist = new Wishlist();
            $wishlist->User_id  = $request->usersId;
            $wishlist->save();

        } else{
           $wishlist =  $user->wishlist;
        }

        if(is_null($wishlist->product->where('id',$request->productId)->first())){
            $wishlist->product()->attach($request->productId);
            $wishlist->product()->syncWithoutDetaching($request->productId);
            return response()->json(["status" => "succes", "message" => "product added Successfully"],200);

        }


    }

    public function userWishlistProducts(Request $request){

        $user = User::find($request->usersId);
        $wishList = $user->wishlist;
        if(is_null($wishList)){
            return response()->json(["status" => "failed"], 204);
        }

        $wishListProduct = $user->wishlist->product;

        $idList = array();
        foreach($wishListProduct as $product){
            array_push($idList,$product->id);
        }
        $products = Product::whereIn('id',$idList)->with('rating')->get();
        if(is_null($wishListProduct)){
            return response()->json(["status" => "failed"], 204);
        }
        return response()->json(['product'=>$products], 200);

    }

    public function deleteWishlistProduct(Request $request){

        $user = User::find($request->userId);
        $wishlist = $user->wishlist;

        if($wishlist->product()->detach($request->productId)){
        return response()->json( ["status" => "succes", "message" => "product added Successfully"],200);
        }
    }

    public function addRating(Request $request){

        $rating = new rating();
        $rating->text = $request->text;
        $rating->rate = $request->rate;
        $rating->user_id = $request->usersId;
        $rating->product_id = $request->productId;
        $rating->save();

        return response()->json( ["status" => "succes", "message" => "Review added Successfully", 'review' => $rating],200);
    }
}
