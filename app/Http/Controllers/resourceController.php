<?php


namespace App\Http\Controllers;

use App\Events\addToCartNotification;
use App\Events\addToWishlistNotification;
use App\Events\deleteFromCartNotification;
use App\Events\deleteFromWishlistNotification;
use App\Events\wishlistNotification;
use App\Models\Cart;
use App\Models\Product;
use App\Models\rating;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class resourceController extends Controller
{
    public function productSpecs(Request $request, $id){
        if($request->lang == 'en'){
        $specs = Product::find($id)->productDetail;
        $specs->makeHidden('ar_OperatingSystem');
        }elseif($request->lang == 'ar'){
            $specs = Product::find($id)->productDetail;
            $specs->makeHidden('OperatingSystem');
        }
        return response()->json($specs , 200);
    }


    public function showReviewers($id){

        $rating = Product::find($id)->rating;
        $users = [] ;

        foreach ($rating as $rate){
            $user = rating::find($rate->id)->user;
            array_push($users, $user);
        }

        return response()->json($users, 200);
    }


    public function addToCart(Request $request,$product_id){
        $user_id = auth('sanctum')->user()->id;
        $user = User::find($user_id);

        if(is_null($user->cart)){
            $cart = new Cart();
            $cart->users_id = $user_id;
            $cart->save();

        } else{
           $cart =  $user->cart;
        }
        event(new addToCartNotification(["status" => "succes", "message" => "product added Successfully"]));

        if(!is_null($cart->product->where('id',$product_id)->first())){

            $cart->product()->detach($product_id);
            $cart->product()->attach($product_id, ['quantity' =>  $request->quantity]);
            $cart->product()->syncWithoutDetaching($product_id);

        }else{

            $cart->product()->attach($product_id, ['quantity' =>  $request->quantity]);
            $cart->product()->syncWithoutDetaching($product_id);
        }

        return response()->json(["status" => "succes", "message" => "product added Successfully",$user_id],200);

    }

    public function showCartProducts(){
        $user_id = auth('sanctum')->user()->id;
        $user = User::find($user_id);
        $cart = $user->cart;
        if(is_null($cart)){
            return response()->json(["status" => "failed"], 204);
        }
        $cartProducts = $cart->product;

        return response()->json( $cart, 200);

    }


    public function deleteCartProduct($product_id){
        $user_id = auth('sanctum')->user()->id;
        $user = User::find($user_id);
        $cart = $user->cart;
        $cartProducts = $cart->product;

        if($cart->product()->detach($product_id)){
        event(new deleteFromCartNotification(["status" => "succes", "message" => "product deleted Successfully"]));
        return response()->json( ["status" => "succes", "message" => "product deleted Successfully"],200);
        }
    }

    public function addToWishList($product_id){
        $user_id = auth('sanctum')->user()->id;
        $user = User::find($user_id);

        if(is_null($user->wishlist)){
            $wishlist = new Wishlist();
            $wishlist->User_id  = $user_id;
            $wishlist->save();

        } else{
           $wishlist =  $user->wishlist;
        }

        if(is_null($wishlist->product->where('id',$product_id)->first())){
            $wishlist->product()->attach($product_id);
            $wishlist->product()->syncWithoutDetaching($product_id);
            event(new addToWishlistNotification(["status" => "succes", "message" => "product added Successfully"]));
            return response()->json(["status" => "succes", "message" => "product added Successfully"],200);
        }


    }

    public function showWishlistProducts(){
        $user_id = auth('sanctum')->user()->id;
        $user = User::find($user_id);
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

    public function deleteWishlistProduct($product_id){
        $user_id = auth('sanctum')->user()->id;
        $user = User::find($user_id);
        $wishlist = $user->wishlist;


        if($wishlist->product()->detach($product_id)){
        event(new deleteFromWishlistNotification(["status" => "succes", "message" => "product deleted Successfully"]));
        return response()->json( ["status" => "succes", "message" => "product deleted Successfully"],200);
        }
    }

    public function addReview(Request $request, $id){

        $rating = new rating();
        $rating->text = $request->text;
        $rating->rate = $request->rate;
        $rating->user_id = $request->usersId;
        $rating->product_id = $id;
        $rating->save();

        return response()->json( ["status" => "succes", "message" => "Review added Successfully", 'review' => $rating],200);
    }
}
