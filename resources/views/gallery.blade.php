@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-center mb-8">Image Gallery</h1>
    
    <!-- Filter Section -->
    <div class="mb-8 flex flex-wrap gap-4">
        <div class="w-full md:w-auto">
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select id="categoryFilter" class="w-full md:w-48 p-2 rounded border focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->category }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="w-full md:w-auto">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
            <select id="tagFilter" class="w-full md:w-48 p-2 rounded border focus:ring-2 focus:ring-blue-500 focus:border-blue-500" multiple>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->tag }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="galleryGrid">
        @foreach($imageStyles as $image)
            <div class="gallery-item relative group overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-all duration-300" 
                 data-category="{{ $image->category->id }}"
                 data-tags="{{ $image->tags->pluck('id')->join(',') }}">
                <!-- Image with Lazy Loading -->
                <img 
                    src="{{ asset('storage/' . $image->image) }}" 
                    alt="{{ $image->title }}"
                    loading="lazy"
                    class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105"
                >
                
                <!-- Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-black/40 opacity-0 group-hover:opacity-100 transition-all duration-300 p-4 flex flex-col justify-end">
                    <div class="text-white">
                        <h3 class="font-bold text-lg mb-2">{{ $image->title }}</h3>
                        <div class="bg-black/40 rounded px-3 py-2 mb-2 backdrop-blur-sm">
                            <code class="text-sm font-mono">{{ $image->image_code }}</code>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-sm bg-blue-500/80 rounded px-2 py-1">{{ $image->category->category }}</span>
                        </div>
                        
                        <!-- Tags -->
                        <div class="flex flex-wrap gap-1">
                            @foreach($image->tags as $tag)
                                <span class="text-xs bg-gray-700/80 text-white rounded px-2 py-1">
                                    {{ $tag->tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $imageStyles->links() }}
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('categoryFilter');
    const tagFilter = document.getElementById('tagFilter');
    const galleryItems = document.querySelectorAll('.gallery-item');

    function filterGallery() {
        const selectedCategory = categoryFilter.value;
        const selectedTags = Array.from(tagFilter.selectedOptions).map(option => option.value);

        galleryItems.forEach(item => {
            const itemCategory = item.dataset.category;
            const itemTags = item.dataset.tags.split(',');

            const categoryMatch = !selectedCategory || itemCategory === selectedCategory;
            const tagMatch = selectedTags.length === 0 || selectedTags.some(tag => itemTags.includes(tag));

            if (categoryMatch && tagMatch) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    categoryFilter.addEventListener('change', filterGallery);
    tagFilter.addEventListener('change', filterGallery);
});
</script>
@endpush
@endsection