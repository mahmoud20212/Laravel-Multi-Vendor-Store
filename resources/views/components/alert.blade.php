@if (session()->has($type))
    <div class="alert alert-{{ $type }} mb-3">
        {{ session($type) }}
    </div>
@endif