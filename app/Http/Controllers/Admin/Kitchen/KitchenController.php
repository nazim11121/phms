<?php

namespace App\Http\Controllers\Admin\Kitchen;

use Auth;
use App\Models\Item;
use App\Models\Kitchen;
use App\Models\KitchenTotal;
use App\Models\Expense;
use League\Csv\Writer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\DataTables\KitchenDataTable;

class KitchenController extends Controller
{
    protected $services;

    /**
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
        $this->middleware(['permission:Edit Customer'])->only(['show']);
        $this->middleware(['permission:Delete Customer'])->only(['destroy']);
    }

    /**
     * index
     *
     * @param  mixed $dataTable
     * @return void
     */
    public function index(KitchenDataTable $dataTable)
    {
        set_page_meta(__t('kitchen'));
        $kitchenList = Kitchen::latest()->get();
        return $dataTable->render('admin.kitchen.index', compact('kitchenList'));
    }

    /*
     * This function is worked for
     * purchase create page render
     * */

    public function create()
    {
        set_page_meta(__t('add') . ' ' . __t('kitchen'));
        $itemList = Item::get();
        return view('admin.kitchen.create', compact('itemList'));
    }

    /*
     * This function is worked for
     * Product Purchase Request Save
     * */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'item_name'  => 'required', 
            'weight'     => 'required', 
            'price'      => 'required', 
            'commute'    => 'required', 
            'total'      => 'nullable', 
        ]);

        $date = date('ym');
        $randNumber = rand('1','100');
        $kitchen_id = $date.$randNumber;
        $sum = 0;
        for ($i = 0; $i < count($request['item_name']); $i++) {
            
            $kitchen             = new Kitchen();
            $kitchen->kitchen_id = $kitchen_id;
            $kitchen->item_name  = $request['item_name'][$i];
            $kitchen->weight     = $request['weight'][$i];
            $kitchen->price      = $request['price'][$i];
            $kitchen->created_by = Auth::id();
            $sum = $sum+$request['price'][$i];
            $kitchen->save();
        }

        $kitchenTotal             = new KitchenTotal();
        $kitchenTotal->kitchen_id = $kitchen->kitchen_id;
        $kitchenTotal->commute    = $request->commute;
        $kitchenTotal->total      = $sum+$request->commute;
        $kitchenTotal->created_by = Auth::id();
        $kitchenTotal->save();

        $expenses             = new Expense();
        $expenses->name       = $kitchen->kitchen_id;
        $expenses->comments   = 'Kitchen';
        $expenses->amount     = $kitchenTotal->total;
        $expenses->created_by = Auth::id();
        $expenses->save();
       
        if (!empty($kitchenTotal)) {
            flash(__t('kitchen_create_successful'))->success();
        } else {
            flash(__t('kitchen_create_failed'))->error();
        }

        return redirect()->route('admin.kitchen.index');
    }


    /**
     * show
     *
     * @param  mixed $purchase
     * @return void
     */
    public function show($id)
    {
        $kitchenId = KitchenTotal::find($id);
        $kitchenList = Kitchen::where('kitchen_id',$kitchenId->kitchen_id)->get();
        return view('admin.kitchen.view', compact('kitchenList','kitchenId'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        set_page_meta(__t('edit') . ' ' . __t('kitchen'));
        $kitchenTotal = KitchenTotal::find($id);
        $kitchen = Kitchen::where('kitchen_id',$kitchenTotal->kitchen_id)->get();

        return view( 'admin.kitchen.edit',compact('kitchen','kitchenTotal'));
    }

    /**
     * This function is worker for
     * Purchase request Update
     */

    public function update(Request $request, $id): RedirectResponse
    {
        
        $request->validate([
            'item_name'  => 'required', 
            'weight'     => 'required', 
            'price'      => 'required', 
            'commute'    => 'required', 
            'total'      => 'nullable', 
        ]);

        $ids = Kitchen::where('kitchen_id',$request->kitchen_id)->get();
        foreach($ids as $data){
            $kitchenDelete   = Kitchen::find($data->id)->delete();
        }

        $sum = 0;

        for ($i = 0; $i < count($request['item_name']); $i++) {
            
            $kitchen             = new Kitchen();
            $kitchen->kitchen_id = $request->kitchen_id;
            $kitchen->item_name  = $request['item_name'][$i];
            $kitchen->weight     = $request['weight'][$i];
            $kitchen->price      = $request['price'][$i];
            $kitchen->updated_by = Auth::id();
            $sum = $sum+$request['price'][$i];
            $kitchen->save();
        }

        $kitchenTotal             = KitchenTotal::find($id);
        $kitchenTotal->commute    = $request->commute;
        $kitchenTotal->total      = $sum+$request->commute;
        $kitchenTotal->updated_by = Auth::id();
        $kitchenTotal->save();

        $id2 = Expense::where('name',$kitchenTotal->kitchen_id)->get()->first();
        $expenses             =  Expense::find($id2->id);
        $expenses->amount     = $kitchenTotal->total;
        $expenses->created_by = Auth::id();
        $expenses->save();
        
        if (!empty($kitchen)) {
            flash(__t('kitchen_update_successful'))->success();
        } else {
            flash(__t('kitchen_update_failed'))->error();
        }

        return redirect()->route('admin.kitchen.index');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id): RedirectResponse
    {
        if ($id) {
            $kitchenTotal = KitchenTotal::find($id);
            $ids = Kitchen::where('kitchen_id',$kitchenTotal->kitchen_id)->get();
            foreach($ids as $data){
                $kitchenDelete   = Kitchen::find($data->id)->delete();
            }
            $id2 = Expense::where('name',$kitchenTotal->kitchen_id)->get()->first();
            $expense = Expense::find($id2->id);
            $expense->delete();
            $kitchenTotal->delete();

            flash(__('custom.kitchen_deleted_successfully'))->success();
        } else {
            flash(__('custom.kitchen_delete_failed'))->error();
        }

        return redirect()->route('admin.kitchen.index');
    }

}
