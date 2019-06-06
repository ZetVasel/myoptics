<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Admin\AdminBaseController;

use App\Models\ProductReviews;
use App\Models\Products;

class AdminProductReviewsController extends AdminBaseController
{
    public function getIndex()
    {
        $title = 'Отзывы к продуктам';
        $reviews = ProductReviews::leftJoin('dv_products', 'dv_product_reviews.product_id', '=', 'dv_products.id')
                                ->select(['dv_product_reviews.*', 'dv_products.name AS prod_name', 'dv_products.slug AS prod_slug'])
                                ->orderBy('dv_product_reviews.id', 'DESC')
                                ->paginate(15);
        $reviews_count = ProductReviews::count();

        return view('admin.showProductReviews', compact(['title', 'reviews','reviews_count']));
    }


    public function postIndex( Request $request ) {

        // ajax
        if( $request->ajax() ) {
            $post = ProductReviews::where( 'id', '=', $request->get('id') )->first();
            $post->update( ['visible' => $request->get('visible')] );


            $qwe = '0';
            if ( $request->get('visible') == 0 ){
                $product = Products::whereId( $post->product_id )->first();
                if( $product->rating_sum > 0) $product->rating_sum -= $post->rating;
                $product->votes--;
                $product->save();
            }
            if ( $request->get('visible') == 1 ){
                $product = Products::whereId( $post->product_id )->first();
                $product->rating_sum += $post->rating;
                $qwe = 5;
                $product->votes  = $product->votes +1;
                $product->save();                
            }            
            return $product->rating_sum;
        }

        $ids = $request->get('check');
        // если действие для удаления
        if( $request->get('action') == 'delete' ) {
            $pr_rev = ProductReviews::whereIn( 'id', $ids )->get();

            //получаем ид товаров на которые есть отзывы
            $pr_rev_ids = ProductReviews::select('dv_product_reviews.product_id')->whereIn( 'id', $ids )->groupBy('product_id')->get();
            $pr_ids[] = '';
            foreach ($pr_rev_ids as $kays) {
                $pr_ids[] = $kays->product_id;
            }

            //отнимаем отзывы у товара
            
            $product = Products::whereIn('id', $pr_ids)->get();
            // print_r($product[0]->votes);
            foreach ($product as $pr) {
                foreach ($pr_rev as $rev) {
                    if( $pr->id == $rev->product_id ){
                        if( $rev->visible == 1 ){
                            $pr->rating_sum -= $rev->rating;
                            $pr->votes--;
                        }
                    }
                }
            }

            $product->each(function ($query) use ($product){
                    foreach ($product as $pr) {
                       $query->update([ 'rating_sum' => $pr->rating_sum, 'votes' => $pr->votes ]);
                    }                    
                });
            ProductReviews::destroy( $ids );
            
        }

        return redirect()->back();
    } 
}
