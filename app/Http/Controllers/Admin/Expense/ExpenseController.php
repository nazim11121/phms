<?php

namespace App\Http\Controllers\Admin\Expense;

Use Auth;
use League\Csv\Writer;
use App\Models\Expense;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\DataTables\CategoryDataTable;

class ExpenseController extends Controller
{

    /*NN*
     * __construct
     *
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
    public function index()
    {
        set_page_meta(__t('category'));
        $data = Expense::get();
        return view('admin.expense.index',compact('data'));
    }

    public function create()
    {
        set_page_meta(__t('add') . ' ' . __t('category'));

        return view('admin.category.create');
    }

    /*
     * This function is worked for
     * Product Purchase Request Save
     * */

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'         => 'required', 
            'comments'     => 'required', 
            'amount'     => 'required', 
        ]);

        $expense = new Expense();
        $expense->name     = $request->name;
        $expense->comments = $request->comments;
        $expense->amount   = $request->amount;
        $expense->created_by = Auth::id();
        $expense->save();
       
        if (!empty($expense)) {
            flash(__t('expense_create_successful'))->success();
        } else {
            flash(__t('expense_create_failed'))->error();
        }

        return redirect()->route('admin.account.dedited');
    }


    /**
     * show
     *
     * @param  mixed $purchase
     * @return void
     */
    public function show($id)
    {

        $categoryList = Category::find($id);
        // dd($recordList->countData);
        return view('admin.category.view', compact('categoryList'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        
        $expenses = Expense::find($id);
        return view('admin.expense.edit',compact('expenses'));
    }

    /**
     * This function is worker for
     * Purchase request Update
     */

    public function update(Request $request, $id): RedirectResponse
    {
        
        $request->validate([
            'name'         => 'required', 
            'comments'     => 'required', 
            'amount'     => 'required', 
        ]);

        $expense = Expense::find($id);
        $expense->name     = $request->name;
        $expense->comments = $request->comments;
        $expense->amount   = $request->amount;
        $expense->created_by = Auth::id();
        $expense->save();
       
        if (!empty($expense)) {
            flash(__t('expense_update_successful'))->success();
        } else {
            flash(__t('expense_update_failed'))->error();
        }

        return redirect()->route('admin.account.dedited');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id): RedirectResponse
    {

        if (Expense::find($id)->delete()) {
            
            flash(__('custom.category_deleted_successfully'))->success();
        } else {
            flash(__('custom.category_delete_failed'))->error();
        }

        return redirect()->route('admin.category.index');
    }

}
