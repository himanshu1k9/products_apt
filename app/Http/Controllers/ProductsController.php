<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $products = $this->getProducts();
        try {
            $products = Products::where('status', 0)->get();
            return view('index', compact('products'));
        } catch(\Exception $e) {
            $errMsg = '';
            if(env('APP_ENV') === 'production') {
                Log::error('Error from product fech' , $e->getMessage());
                $errMsg .= 'Internal server error';
            } else {
                $errMsg .= $e->getMessage();
            }

            $request->session()->flash('error', $errMsg);
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'sourceId' => 'required|numeric|min:1',
            'description' => 'required|string',
            'slug' => 'required|string',
            'category' => 'required|string',
            'imgUrl' => 'required|string'
        ]);

        try {
            DB::table('products')->insert([
                'title' => $data['title'],
                'source_id' => $data['sourceId'],
                'description' => $data['description'],
                'slug' => $data['slug'],
                'category' => $data['category'],
                'image' => $data['imgUrl'],
                'created_at' => now()
            ]);
            return response()->json(['success' => 'Product saved'], 200);
        } catch(\Exception $e) {
            $errMsg = '';
            if(env('APP_ENV') === 'production') {
                Log::error('Error from product fech' , $e->getMessage());
                $errMsg .= 'Internal server error';
            } else {
                $errMsg .= $e->getMessage();
            }
            return response()->json(['error' => $errMsg], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $productsLists = $this->getProducts();
        $data = json_decode($productsLists);
        foreach($data as $key => $item)
        {
            DB::beginTransaction();
            try {
                $formData = [
                    'title' => $item->title->rendered,
                    'source_id' => $item->id,
                    'description' => $item->content->rendered,
                    'slug' => $item->slug,
                    'category' => $item->categories[0],
                    'image' => $item->uagb_featured_image_src->full[0]
                ];

                DB::table('products')->insert($formData);

                DB::commit();
            } catch(\Exception $e) {
                DB::rollback();
                dd($e->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function getSingleProduct($id)
    {
        if($id > 0) {
            $data = DB::table('products')->where('id', $id)->first();
            return response()->json(['data' => $data], 200);
        } else {
            return response()->json(['error' => 'Invalid id'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
         $data = $request->validate([
            'title' => 'required|string|max:255',
            'sourceId' => 'required|numeric|min:1',
            'description' => 'required|string',
            'slug' => 'required|string',
            'category' => 'required|string',
            'imgUrl' => 'required|string'
        ]);

        try {
            DB::table('products')->where('id', $id)->update([
                'title' => $data['title'],
                'source_id' => $data['sourceId'],
                'description' => $data['description'],
                'slug' => $data['slug'],
                'category' => $data['category'],
                'image' => $data['imgUrl'],
                'created_at' => now()
            ]);
            return response()->json(['success' => 'Product saved'], 200);
        } catch(\Exception $e) {
            $errMsg = '';
            if(env('APP_ENV') === 'production') {
                Log::error('Error from product fech' , $e->getMessage());
                $errMsg .= 'Internal server error';
            } else {
                $errMsg .= $e->getMessage();
            }
            return response()->json(['error' => $errMsg], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if($id > 0) {
            DB::table('products')->where('id', $id)->update(['status' => 1]);
            return response()->json(['sucess' => 'deleted'], 200);
        } else {
            return response()->json(['error' => 'Invalid id'], 400);
        }
    }

    protected function getProducts()
    {
        try
        {
            // $responseData = Http::get('https://learn.circuit.rocks/wp-json/wp/v2/posts');
            $data = file_get_contents('https://learn.circuit.rocks/wp-json/wp/v2/posts');

            // echo '<pre>';
            // print_r($responseData); echo '</pre>';
            return $data;
        } catch(\Exception $e) {
            $errMsg = '';
            if(env('APP_ENV') === 'production') {
                Log::error('Error from product fech' , $e->getMessage());
                $errMsg .= 'Internal server error';
            } else {
                $errMsg .= $e->getMessage();
            }

            $request->session()->flash('error', $errMsg);
            return back();
        }
    }
}
