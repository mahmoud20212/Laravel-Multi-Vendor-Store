<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $request = request();
        // $query = Category::query();
        
        // if ($name = $request->query('name')) {
        //     $query->where('name', 'like', "%{$name}%");
        // }

        // if ($status = $request->query('status')) {
        //     $query->where('status', '=', "{$status}");
        // }

        // $categories = $query->paginate(1); // Return Collection object
        // $categories = Category::filter($request->query())->orderBy('name')->paginate(1);
        $categories = Category::
        /* ايجاد عدد المنتجات في الصنف بدون علاقات الخاصة بالجداول
            ----------------------------------------------------------
            select('categories.*')
            ->selectRaw('(SELECT COUNT(*) FROM products WHERE category_id = categories.id) as products_count')
        */
        withCount([
            'products' => function($query) {
                $query->where('status', '=' ,'active');
            }
        ]) //  ايجاد عدد المنتجات في الصنف باستخدام علاقات الخاصة بالجداول
        ->filter($request->query()) // تم تعريفها بستخدام scope في المودل
        ->latest()
        // ->withTrashed()
        // ->onlyTrashed()
        ->paginate();
        // dd($categories);

        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Category::all();
        $category = new Category();
        return view('dashboard.categories.create', compact('parents', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->input('name');
        // $request->post('name');
        // $request->query('name');
        // $request->get('name');
        // $request->name;
        // $request['name'];
        $clean_data = $request->validate(Category::rules());

        $request->merge([
            'slug' => Str::slug($request->post('name')),
        ]);
        $data = $request->except('image');
        
        $data['image'] = $this->uploadImage($request);

        Category::create($data);
        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'Category created!!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('dashboard.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);
        $parents = Category::where('id', '<>', $id)
            ->where(function($query) use ($id) {
                $query->whereNull('parent_id')
                ->orWhere('parent_id', '<>', $id);
            })
            ->get();
        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        // $request->validate(Category::rules($id));
        
        $category = Category::findOrFail($id);
        $old_image = $request->image;
        $data = $request->except('image');
        $new_image = $this->uploadImage($request);

        if ($new_image) {
            $data['image'] = $new_image;
        }

        $category->update($data);
        if ($old_image && $new_image) {
            //Storage::delete($old_image); // use default disk
            Storage::disk('public')->delete($old_image);
        }

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'Category updated!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // $category = Category::findOrFail($id);
        $category->delete();
        
        // if ($category->image) {
        //     Storage::disk('public')->delete($category->image);
        // }
        
        //Category::destroy($id);
        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'Category deleted!!');
    }

    protected function uploadImage(Request $request) {
        if (!$request->hasFile('image')){
            return;
        }

        $file = $request->file('image'); // return uploaded file object
        // $file->getClientOriginalName();
        // $file->getSize();
        // $file->getClientOriginalExtension();
        // $file->getMimeType(); // image/png
        $path = $file->store('uploads', [
            'disk' => 'public',
        ]);
        return $path;
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->paginate();
        return view('dashboard.categories.trash', compact('categories'));
    }
    
    public function restore(Request $request, $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        
        return redirect()
            ->route('dashboard.categories.trash')
            ->with('success', 'Category restore!!');
    }
    
    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        return redirect()
            ->route('dashboard.categories.trash')
            ->with('success', 'Category deleted forever!!');
    }
}
