<?php

namespace App\Http\Controllers;

use App\PhysicalProduct;
use App\Product;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product) {
        // If user is logged in
        if (!auth()->guest())
            $this->authorize('view', $product);
        elseif ($product->active == false)
        {
        	session()->flash('error', 'Product is not available anymore!');
	        abort(404);
        }
        


        return view('product.index', [
            'product' => $product,
        ]);
    }

    public function showRules(Product $product) {
        // If user is logged in
        if (!auth()->guest())
            $this->authorize('view', $product);
        elseif ($product->active == false)
            abort(404);


        return view('product.rules', [
            'product' => $product,
        ]);
    }

    public function showFeedback(Product $product) {
        // If user is logged in
        if (!auth()->guest())
            $this->authorize('view', $product);
        elseif ($product->active == false)
            abort(404);

        return view('product.feedback', [
            'product' => $product,
        ]);
    }

    public function showDelivery(PhysicalProduct $product) {
        // If user is logged in
        if (auth()->check())
            $this->authorize('view', $product->product);
        elseif ($product->product->active == false)
            abort(404);

        return view('product.delivery', [
            'product' => $product->product,
        ]);
    }

    public function showVendor(Product $product) {
        // If user is logged in
        if (!auth()->guest())
            $this->authorize('view', $product);
        elseif ($product->active == false)
            abort(404);

        return view('product.vendor', [
            'product' => $product,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product) {
        //
    }
	
	
	public function statusProductShow(Product $product) {
  
		return view('profile.product.confirmstatus')->with([
			'product' => $product
		]);
	}
	
	public function statusProductPost(Product $product) {
		
		$this->authorize('status', $product);
		
		if($product->user->id !== auth()->user()->id)
		{
			session() -> flash('error', 'Not your product!');
			return redirect()->back();
		}
		
/*	 if(empty($_POST['iagreeterms']) || $_POST['iagreeterms'] != 'on')
	 {
		 session() -> flash('error', 'You must agree to Terms and Conditions');
		 return redirect()->back();
	 }*/
		
		try {
			$active = $product->active == 0 ? 1 : 0;//if inactive -> publish
			$product->active = $active;
			$product->save();
            
            if ($active == 1) {
                $product->makeSearchable();
            }


            session() -> flash('success', $product->name . ' is now ' . ($active ==1 ? 'Published' : 'inactive') );
			
		} catch (\Exception $e) {
			session() -> flash('error', $product->name . ' status change error ' . $e->getMessage() );
		}
		
		return redirect()->route('profile.vendor');
	}
	
	
	public function cloneProductShow(Product $product) {

        return view('profile.product.confirmclone')->with([
            'product' => $product
        ]);
    }

    
    public function cloneProductPost(Product $product) {


        \App\Jobs\CancelPurchase::dispatch($product)->delay(now()->addSeconds(5));
        \Log::channel('custom-log')->info('CancelPurchase dispatch started');
	
	    if(empty($_POST['iagreeterms']) || $_POST['iagreeterms'] != 'on')
	    {
		    session() -> flash('error', 'You must agree to Terms and Conditions');
		    return redirect()->back();
	    }
	    
        DB::beginTransaction();
        try {
            /**
             * Product cloning
             */
            $newProduct = $product->replicate();

            if(strlen($product->name . ' (Clone)') > 100)
            {
	            $newProduct->name = substr($product->name,0, 100 - strlen(' (Clone)')) . ' (Clone)';
            } else {
	            $newProduct->name = $product->name . ' (Clone)';
            }

            $newProduct->active = false;
            $newProduct->save();
            /**
             * Relations
             */

            if ($product->isDigital()) {
                $newDigitalProduct = $product->digital->replicate([
                    'content',
                    'sold',
                ]);
                $newDigitalProduct->id = $newProduct->id;
                $newDigitalProduct->save();
            }
            if ($product->isPhysical()) {
                $newPhysicalProduct = $product->physical->replicate();
                $newPhysicalProduct->id = $newProduct->id;
                $newPhysicalProduct->save();

                // shippings
                foreach ($product->physical->shippings as $shipping) {
                    $newShipping = $shipping->replicate();
                    $newShipping->product_id = $newProduct->id;
                    $newShipping->save();
                }
            }

            /**
             * Offers
             */
            foreach ($product->offers as $offer) {
                $newOffer = $offer->replicate();
                $newOffer->product_id = $newProduct->id;
                $newOffer->save();
            }

            /**
             * Images
             */
            foreach ($product->images as $image){
                $newImage = $image->replicate();
                $newImage->product_id = $newProduct->id;


                $content = \Storage::disk('public')->get($image->image);

                //$destination =  storage_path('app/public/products').strtolower(str_random(32));
                $randomName = strtolower(str_random(32));
                $name = "products/{$randomName}.jpg";

                \Storage::disk('public')->put($name,$content);
                $newImage->image = $name;
                $newImage->save();
            }
	
	        session() -> flash('success', 'Product cloned ' . $newProduct->name );
            DB::commit();
            
            
        } catch (\Exception $e) {

            DB::rollBack();
            session() -> flash('error', 'There was an error cloning ' .  $product->name . ' [ ' . $e->getMessage() . ' ] ');
        }

        return redirect()->route('profile.vendor');
    }
}
