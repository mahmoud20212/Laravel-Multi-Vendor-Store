@if ($errors->any())
    <div class="alert alert-danger">
        <h3>Error Occured!</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <x-form.input label="name" class="form-control-lg" type="text" name="name" :value="$product->name" />
</div>
<div class="form-group">
    <label for="">Category</label>
    <select name="parent_id" class="form-control">
        <option value="">Primary Category</option>
        @foreach (App\Models\Category::all() as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $category->category_id) == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <x-form.input label="Price" class="form-control" type="number" name="price" :value="$product->price" />
</div>
<div class="form-group">
    <x-form.input label="Compare Price" class="form-control" type="number" name="compare_price" :value="$product->compare_price" />
</div>
<div class="form-group">
    <label for="">Description</label>
    <x-form.textarea name="description" :value="$product->description" />
</div>
<div class="form-group">
    <x-form.label id="image">Image</x-form.label>
    <x-form.input type="file" name="image" accept="image/*" />
    @if ($product->image)
        <img class="rounded shadow-sm my-2" src="{{ asset('storage/' . $product->image) }}" alt="" height="100">
    @endif
</div>
<div class="form-group">
    <x-form.input label="Tags" class="form-control" type="text" name="tags" :value="$tags" />
</div>
<div class="form-group">
    <label>Status</label>
    <x-form.radio name="status" :checked="$product->status" :options="['active' => 'Active', 'archived' => 'Archived', 'draft' => 'Draft']" />
</div>
<div class="form-group">
    <button class="btn btn-primary">{{ $button_label ?? 'Save'}}</button>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
<script>
    var inputElm = document.querySelector('[name=tags]'),
    tagify = new Tagify (inputElm);
</script>
@endpush