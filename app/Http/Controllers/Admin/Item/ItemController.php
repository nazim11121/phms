<?php

namespace App\Http\Controllers\Admin\Item;

use Auth;
use App\Models\Item;
use App\Models\Category;
use League\Csv\Writer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\DataTables\ItemDataTable;

class ItemController extends Controller
{
    /*NN*
     * __construct
     *
     * @param  mixed $ServicesServices
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:List Customer'])->only(['index']);
        $this->middleware(['permission:Add Customer'])->only(['create']);
        $this->middleware(['permission:Edit Customer'])->only(['edit']);
        $this->middleware(['permission:Show Customer'])->only(['show']);
        $this->middleware(['permission:Delete Customer'])->only(['destroy']);
    }

    /**
     * index
     *
     * @param  mixed $dataTable
     * @return void
     */
    public function index(ItemDataTable $dataTable)
    {
        set_page_meta(__t('item'));
        $itemList = Item::latest()->get();

        return $dataTable->render('admin.item.index', compact('itemList'));
    }

    public function create()
    {
        set_page_meta(__t('add') . ' ' . __t('item'));
        $category = Category::where('status','Active')->get();

        return view('admin.item.create', compact('category'));
    }

    /*
     * This function is worked for
     * Item Request Save
     * */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'  => 'required', 
            'category'  => 'required', 
            'price'  => 'required', 
            'status'  => 'nullable', 
            'image' => 'nullable|file|mimes:jpg,jpeg,png', 
        ]);

        $file = $request->file('image');
        if(!empty($file)){
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/item', $fileName);
        }else{
            $fileName='';
        }

        $item = new Item();
        $item->name = $request->name;
        $item->category = $request->category;
        $item->price = $request->price;
        $item->image = $fileName;
        $item->created_by = Auth::id();
        $item->save();
       
        if (!empty($item)) {
            flash(__t('item_create_successful'))->success();
        } else {
            flash(__t('item_create_failed'))->error();
        }

        return redirect()->route('admin.item.index');
    }


    /**
     * show
     *
     * @param  mixed $itemList
     * @return void
     */
    public function show($id)
    {

        $itemList = Item::find($id);
        return view('admin.item.view', compact('itemList'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        set_page_meta(__t('edit') . ' ' . __t('item'));
        $item = Item::find($id);
        $category = Category::where('status','Active')->get();

        return view( 'admin.item.edit',compact('item','category'));
    }

    /**
     * This function is worker for
     * Purchase request Update
     */

    public function update(Request $request, $id): RedirectResponse
    {
        
        $request->validate([
            'name'  => 'required', 
            'category'  => 'required', 
            'price'  => 'required', 
            'status'  => 'nullable', 
            'image' => 'nullable|file|mimes:jpg,jpeg,png',  
        ]);

        $file = $request->file('image');
        if(!empty($file)){
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/item', $fileName);
        }else{
            $fileName='';
        }
        
        $item = Item::findorfail($id);
        $item->name = $request->name;
        $item->category = $request->category;
        $item->price = $request->price;
        $item->image = $fileName;
        $item->updated_by = Auth::id();
        $item->save();
        
        if (!empty($item)) {
            flash(__t('item_update_successful'))->success();
        } else {
            flash(__t('item_update_failed'))->error();
        }

        return redirect()->route('admin.item.index');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id): RedirectResponse
    {
        if (Item::find($id)->delete()) {
            
            flash(__('custom.item_deleted_successfully'))->success();
        } else {
            flash(__('custom.item_deleted_failed'))->error();
        }

        return redirect()->route('admin.item.index');
    }

}
