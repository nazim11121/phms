<?php

namespace App\Http\Controllers\Admin\Category;

Use Auth;
use League\Csv\Writer;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\DataTables\CategoryDataTable;

class CategoryController extends Controller
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
    public function index(CategoryDataTable $dataTable)
    {
        set_page_meta(__t('category'));
        $categoryList = Category::get();
        return $dataTable->render('admin.category.index', compact('categoryList'));
    }

    /*
     * This function is worked for
     * purchase create page render
     * */

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
            'status'       => 'nullable', 
            'image'        => 'nullable|file|mimes:jpg,jpeg,png', 
        ]);

        $file = $request->file('image');
        if(!empty($file)){
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/category', $fileName);
        }else{
            $fileName='';
        }

        $category = new Category();
        $category->name = $request->name;
        // $category->image = $fileName;
        $category->created_by = Auth::id();
        $category->save();
       
        if (!empty($category)) {
            flash(__t('category_create_successful'))->success();
        } else {
            flash(__t('category_create_failed'))->error();
        }

        return redirect()->route('admin.category.index');
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
        return view('admin.category.view', compact('categoryList'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        set_page_meta(__t('edit') . ' ' . __t('category'));
        $category = Category::find($id);
        // return response()->json($category);
        return view('admin.category.edit',compact('category'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        
        $request->validate([
            'name'  => 'required', 
            'status'       => 'nullable', 
            'image' => 'nullable|file|mimes:jpg,jpeg,png', 
        ]);

        $category = Category::findorfail($id);
       
        $file = $request->file('image');
        if(!empty($file)){
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/category', $fileName);
        }else{
            $fileName= $category->image;
        }
        
        if($request->status){
            $status = $request->status;
        }else{
            $status = 'Inactive';
        }
        $category->name = $request->name;
        $category->image = $fileName;
        $category->status = $status;
        $category->updated_by = Auth::id();
        $category->save();
        
        if (!empty($category)) {
            flash(__t('category_update_successful'))->success();
        } else {
            flash(__t('category_update_failed'))->error();
        }

        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id): RedirectResponse
    {
        if (Category::find($id)->delete()) {
            
            flash(__('custom.category_deleted_successfully'))->success();
        } else {
            flash(__('custom.category_delete_failed'))->error();
        }

        return redirect()->route('admin.category.index');
    }

}
