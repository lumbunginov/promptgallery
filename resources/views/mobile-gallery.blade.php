<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery</title>
    @if(config('app.env') === 'local')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
        <script src="{{ asset('build/assets/app.js') }}" defer></script>
    @endif
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex justify-center">
        <div class="w-full max-w-md mx-auto bg-white min-h-screen shadow-lg">
            <div class="p-4">
                <!-- Title -->
                <h1 class="text-2xl font-bold text-center mb-0">Style Gambar</h1>
                <p class="text-lg text-center text-gray-600 mb-4">Rara AI Photo Product</p>

                <!-- Filter Toggle Button -->
                <div class="fixed top-4 right-4 z-50">
                    <button id="filterToggle" class="p-2 flex items-center justify-center transition" style="background:transparent; border:none; box-shadow:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="black" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="4" fill="white" stroke="black" stroke-width="2"/>
                            <path d="M7 8h10M9 12h6M11 16h2" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <!-- Filter Panel Overlay (untuk klik di luar panel) -->
                <div id="filterPanelOverlay" class="fixed inset-0 bg-black bg-opacity-10 z-30 hidden"></div>
                <!-- Filter Panel -->
                <div id="filterPanel" class="fixed top-0 right-0 h-full w-48 max-w-[180px] bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-40 p-4">
                    <div class="mb-4">
                        <h3 class="font-bold mb-2">Categories</h3>
                        <select id="categoryFilter" class="w-full p-2 border rounded">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <h3 class="font-bold mb-2">Tags</h3>
                        <div class="space-y-2">
                            @foreach($tags as $tag)
                                <label class="flex items-center">
                                    <input type="checkbox" value="{{ $tag->id }}" class="tag-checkbox mr-2">
                                    <span>{{ $tag->tag }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Gallery Grid -->
                <div class="grid grid-cols-2 gap-2" id="galleryGrid"></div>
                <!-- Pagination -->
                <div class="mt-6 pb-6 flex justify-center" id="paginationContainer"></div>
            </div>
        </div>
    </div>

    <!-- Simplified Image Detail Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden">
        <div class="max-w-lg w-full mx-auto my-8 bg-white rounded-lg overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Modal Header with Close Button -->
            <div class="flex justify-between items-center p-4 border-b">
                <div>
                    <h2 class="text-xl font-bold" id="modalTitle"></h2>
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Kategori:</span>
                        <span id="modalCategory"></span>
                    </div>
                </div>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 ml-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body - Scrollable -->
            <div class="flex-1 overflow-y-auto p-4">
                <!-- Image Container -->
                <div id="modalImageContainer" class="relative w-full aspect-square mb-4">
                    <img id="modalImage" src="" alt="" class="w-full h-full object-contain">
                    <div id="modalLoading" class="absolute inset-0 flex items-center justify-center bg-gray-100">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
                    </div>
                </div>
                
                <!-- Code Section - Centered -->
                <div class="text-center">
                    <div class="inline-flex items-baseline">
                        <span class="text-xl font-bold mr-2">Kode:</span>
                        <code id="modalCode" class="text-xl font-bold font-mono"></code>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="mt-4">
                    <h3 class="text-lg font-bold mb-2">Deskripsi</h3>
                    <div id="modalDescription" class="text-gray-700 whitespace-pre-line"></div>
                </div>

                <!-- Tags Section -->
                <div class="mt-4">
                    <div id="modalTags" class="flex flex-wrap gap-2"></div>
                </div>
                <!-- Variasi Section -->
                <div class="mt-4">
                    <h3 class="text-lg font-bold mb-2">Variasi</h3>
                    <div id="modalVariations" class="flex flex-col gap-2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Intro Overlay -->
    <div id="filterIntroOverlay" class="fixed inset-0 bg-black bg-opacity-70 z-[100] flex flex-col items-center justify-center" style="display:none;">
        <div class="flex flex-col items-center w-full">
            <div class="relative flex justify-center w-full mb-6">
                <button id="filterOverlayBtn" class="p-2 flex items-center justify-center transition" style="background:transparent; border:none; box-shadow:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="black" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="4" fill="white" stroke="black" stroke-width="2"/>
                        <path d="M7 8h10M9 12h6M11 16h2" stroke="black" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
            <div class="text-center text-white text-2xl font-bold leading-relaxed">
                Gunakan filter <br>untuk mencari style <br>yang sesuai produk
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full max-w-md mx-auto px-4 pb-4 pt-6 mt-4">
        <div class="flex flex-col items-center gap-2 text-center">
            <div class="flex flex-row gap-4 w-full justify-center mb-1">
                <a href="https://api.whatsapp.com/send?phone=6285187408202&text=halo%20rara" target="_blank" rel="noopener" class="flex items-center gap-1 text-blue-600 hover:underline font-medium text-sm"><span>💬</span>Rara</a>
                <a href="https://api.whatsapp.com/send?phone=6285646645065" target="_blank" rel="noopener" class="flex items-center gap-1 text-blue-600 hover:underline font-medium text-sm"><span>💬</span>CS</a>
                <a href="/tutorial" class="flex items-center gap-1 text-blue-600 hover:underline font-medium text-sm"><span>▶️</span>Tutorial</a>
            </div>
            <div class="text-gray-400 text-xs mt-1">Made with <span class="text-red-500">❤️</span> by Rara AI</div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lazy loading implementation
            const lazyLoadImages = () => {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                                img.classList.remove('lazy');
                                observer.unobserve(img);
                            }
                        }
                    });
                }, {
                    rootMargin: '50px 0px' // Start loading images 50px before they enter viewport
                });

                // Observe all lazy images
                document.querySelectorAll('img.lazy').forEach(img => {
                    imageObserver.observe(img);
                });
            };

            // Initialize lazy loading
            lazyLoadImages();

            const filterToggle = document.getElementById('filterToggle');
            const filterPanel = document.getElementById('filterPanel');
            const filterPanelOverlay = document.getElementById('filterPanelOverlay');
            const categoryFilter = document.getElementById('categoryFilter');
            const tagCheckboxes = document.querySelectorAll('.tag-checkbox');
            const galleryGrid = document.getElementById('galleryGrid');
            const paginationContainer = document.getElementById('paginationContainer');

            // Toggle filter panel
            filterToggle.addEventListener('click', () => {
                filterPanel.classList.remove('translate-x-full');
                filterPanelOverlay.classList.remove('hidden');
                filterToggle.classList.add('hidden');
            });

            // Filter functionality
            function fetchGallery(page = 1) {
                const category = categoryFilter.value;
                const tags = Array.from(tagCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value)
                    .join(',');
                let url = `/api/image-styles?page=${page}`;
                if (category) url += `&category=${category}`;
                if (tags) url += `&tags=${tags}`;
                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        // Render grid
                        galleryGrid.innerHTML = '';
                        data.data.forEach(image => {
                            const div = document.createElement('div');
                            div.className = 'gallery-item relative aspect-square cursor-pointer';
                            div.setAttribute('data-category', image.category_id);
                            div.setAttribute('data-tags', (image.tags || []).map(t => t.id).join(','));
                            div.onclick = () => showImageDetail(div, image.id);
                            div.innerHTML = `<img src="/storage/${image.image}" alt="${image.title}" class="w-full h-full object-cover rounded">`;
                            galleryGrid.appendChild(div);
                        });
                        // Render pagination
                        if (data.meta.last_page > 1) {
                            paginationContainer.style.display = '';
                            paginationContainer.innerHTML = `
                                <div class="flex items-center gap-3">
                                    <button ${data.meta.current_page === 1 ? 'disabled' : ''} class="w-10 h-10 flex items-center justify-center bg-white text-gray-700 rounded-full shadow transition-colors" onclick="window.fetchGallery && fetchGallery(${data.meta.current_page - 1})">←</button>
                                    <span class="px-4 py-2 bg-white rounded-full shadow">${data.meta.current_page}/${data.meta.last_page}</span>
                                    <button ${data.meta.current_page === data.meta.last_page ? 'disabled' : ''} class="w-10 h-10 flex items-center justify-center bg-white text-gray-700 rounded-full shadow transition-colors" onclick="window.fetchGallery && fetchGallery(${data.meta.current_page + 1})">→</button>
                                </div>
                            `;
                        } else {
                            paginationContainer.style.display = 'none';
                        }
                    });
            }
            // Event listeners
            categoryFilter.addEventListener('change', () => fetchGallery(1));
            tagCheckboxes.forEach(cb => cb.addEventListener('change', () => fetchGallery(1)));
            // Inisialisasi pertama
            window.fetchGallery = fetchGallery;
            fetchGallery(1);

            // Overlay filter intro logic
            const overlay = document.getElementById('filterIntroOverlay');
            const filterOverlayBtn = document.getElementById('filterOverlayBtn');
            let overlayDismissed = false;
            function showOverlay() {
                overlay.style.display = 'flex';
                localStorage.setItem('filterIntroDismissed', '1');
            }
            function hideOverlay() {
                overlay.style.display = 'none';
                overlayDismissed = true;
                localStorage.setItem('filterIntroDismissed', '1');
            }
            // Tampilkan overlay saat pertama kali load, hanya jika belum pernah dismiss
            if (!localStorage.getItem('filterIntroDismissed')) {
                showOverlay();
            } else {
                hideOverlay();
            }
            // Klik pada overlay atau tombol filter di overlay akan menutup overlay
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) hideOverlay();
            });
            filterOverlayBtn.addEventListener('click', function(e) {
                hideOverlay();
                // Buka filter panel jika ingin langsung buka
                filterToggle.click();
            });
            // Klik tombol filter utama juga menutup overlay jika overlay masih aktif
            filterToggle.addEventListener('click', function() {
                if (!overlayDismissed) hideOverlay();
            });

            // Klik di luar panel untuk menutup
            filterPanelOverlay.addEventListener('click', function(e) {
                filterPanel.classList.add('translate-x-full');
                filterPanelOverlay.classList.add('hidden');
                filterToggle.classList.remove('hidden');
            });
        });

        // Modal functions
        function showImageDetail(element, imageId) {
            const modal = document.getElementById('imageModal');
            const modalLoading = document.getElementById('modalLoading');
            const modalImage = document.getElementById('modalImage');
            
            // Show loading state
            document.getElementById('modalTitle').textContent = 'Loading...';
            document.getElementById('modalCategory').textContent = '';
            document.getElementById('modalCode').textContent = '';
            document.getElementById('modalDescription').textContent = '';
            document.getElementById('modalTags').innerHTML = '';
            document.getElementById('modalVariations').innerHTML = '';
            modalImage.src = '';
            modalLoading.style.display = 'flex';
            modalImage.style.display = 'none';
            
            // Show modal
            modal.classList.remove('hidden');
            
            // Fetch image details from API
            fetch(`/api/image-styles/${imageId}`)
                .then(response => {
                    console.log('API Response Status:', response.status);
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('API Response Data:', data);
                    // Update modal content
                    document.getElementById('modalTitle').textContent = data.data.title || 'No Title';
                    document.getElementById('modalCategory').textContent = data.data.category?.name || 'Uncategorized';
                    document.getElementById('modalCode').textContent = data.data.code || 'No code available';
                    
                    // Update description with HTML support
                    const descriptionEl = document.getElementById('modalDescription');
                    if (data.data.description) {
                        // Allow specific HTML tags while preventing XSS
                        const sanitizedDescription = data.data.description
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            // Safely restore allowed HTML tags
                            .replace(/&lt;strong&gt;/g, '<strong>')
                            .replace(/&lt;\/strong&gt;/g, '</strong>')
                            .replace(/&lt;b&gt;/g, '<b>')
                            .replace(/&lt;\/b&gt;/g, '</b>')
                            .replace(/&lt;em&gt;/g, '<em>')
                            .replace(/&lt;\/em&gt;/g, '</em>')
                            .replace(/&lt;i&gt;/g, '<i>')
                            .replace(/&lt;\/i&gt;/g, '</i>')
                            .replace(/&lt;br&gt;/g, '<br>');
                        
                        descriptionEl.innerHTML = sanitizedDescription;
                    } else {
                        descriptionEl.textContent = 'No description available';
                    }
                    
                    // Handle tags
                    const tagsContainer = document.getElementById('modalTags');
                    tagsContainer.innerHTML = ''; // Clear existing tags
                    if (data.data.tags && data.data.tags.length > 0) {
                        data.data.tags.forEach(tag => {
                            const tagElement = document.createElement('span');
                            tagElement.className = 'bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm';
                            tagElement.textContent = tag.tag;
                            tagsContainer.appendChild(tagElement);
                        });
                    } else {
                        const noTagsElement = document.createElement('span');
                        noTagsElement.className = 'text-gray-500 text-sm';
                        noTagsElement.textContent = 'No tags available';
                        tagsContainer.appendChild(noTagsElement);
                    }

                    // Handle variations
                    const variationsContainer = document.getElementById('modalVariations');
                    variationsContainer.innerHTML = '';
                    if (data.data.variations && data.data.variations.length > 0) {
                        // Buat grid 2 kolom
                        variationsContainer.className = 'grid grid-cols-2 gap-3';
                        data.data.variations.forEach(variation => {
                            const wrapper = document.createElement('div');
                            wrapper.className = 'flex flex-col items-center p-2 rounded cursor-pointer hover:bg-gray-50 transition';
                            wrapper.style.minHeight = '110px';

                            // Gambar full width
                            if (variation.image) {
                                const img = document.createElement('img');
                                img.src = '/storage/' + variation.image;
                                img.alt = variation.title;
                                img.className = 'w-full aspect-square object-cover rounded mb-1';
                                wrapper.appendChild(img);
                            }

                            // Kode di bawah gambar
                            const code = document.createElement('div');
                            code.className = 'font-mono font-bold text-center text-sm';
                            code.textContent = `kode : ${variation.code}`;
                            wrapper.appendChild(code);

                            // Event klik: buka detail style variasi
                            wrapper.addEventListener('click', function(e) {
                                e.stopPropagation();
                                showImageDetail(null, variation.id);
                            });

                            variationsContainer.appendChild(wrapper);
                        });
                    } else {
                        variationsContainer.className = 'flex flex-col gap-2';
                        const noVar = document.createElement('span');
                        noVar.className = 'text-gray-500 text-sm';
                        noVar.textContent = 'Tidak ada variasi.';
                        variationsContainer.appendChild(noVar);
                    }

                    if (data.data.image) {
                        const imagePath = `/storage/${data.data.image}`;
                        console.log('Loading image from:', imagePath);
                        
                        // Preload image
                        const img = new Image();
                        img.onload = function() {
                            modalImage.src = imagePath;
                            modalImage.style.display = 'block';
                            modalLoading.style.display = 'none';
                        };
                        img.onerror = function(e) {
                            console.error('Failed to load image:', imagePath, e);
                            modalLoading.style.display = 'none';
                            modalImage.style.display = 'none';
                            document.getElementById('modalTitle').textContent = 'Failed to load image';
                        };
                        img.src = imagePath;
                    } else {
                        modalLoading.style.display = 'none';
                        modalImage.style.display = 'none';
                        document.getElementById('modalTitle').textContent = 'No image available';
                    }
                })
                .catch(error => {
                    console.error('Error fetching image details:', error);
                    document.getElementById('modalTitle').textContent = 'Error';
                    document.getElementById('modalCategory').textContent = '';
                    document.getElementById('modalCode').textContent = '';
                    document.getElementById('modalDescription').textContent = '';
                    document.getElementById('modalTags').innerHTML = '';
                    document.getElementById('modalVariations').innerHTML = '';
                    modalLoading.style.display = 'none';
                    modalImage.style.display = 'none';
                });
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.getElementById('modalImage').src = '';
        }

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html> 