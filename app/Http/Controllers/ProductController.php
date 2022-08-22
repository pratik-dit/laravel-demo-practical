<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Products\Product;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Redirect,Response,DB;
use File;

class ProductController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Product::select('*'))
            ->addColumn('action', 'action')
            ->addColumn('image', 'image')
            ->rawColumns(['action','image'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('product.index');
    }

    public function store(Request $request)
    {
        request()->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
       ]);

        $productId = $request->product_id;

        $details = ['name' => $request->name, 'upc' => $request->upc, 'price' => $request->price];

        if ($files = $request->file('image')) {

           //delete existing file
           \File::delete('public/product/'.$request->hidden_image);

           //insert file
           $destinationPath = 'public/product/';
           $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
           $files->move($destinationPath, $profileImage);
           $details['image'] = "$profileImage";
        }

        $product   =   Product::updateOrCreate(['id' => $productId], $details);

        return Response::json($product);
    }

    public function edit($id)
    {
        $where = array('id' => $id);
        $product  = Product::where($where)->first();

        return Response::json($product);
    }

    public function destroy($id)
    {
        $data = Product::where('id',$id)->first(['image']);
        \File::delete('public/product/'.$data->image);
        $product = Product::where('id',$id)->delete();

        return Response::json($product);
    }
}