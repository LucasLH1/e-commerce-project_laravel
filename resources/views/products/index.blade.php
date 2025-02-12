<x-app-layout>
    <div class="container mx-auto max-w-6xl py-6">
        <div x-data="{ showNotification: false, message: '', type: '' }" class="relative">
            <div x-show="showNotification"
                 x-transition
                 class="fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg text-white"
                 :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'"
                 x-init="setTimeout(() => showNotification = false, 3000)">
                <span x-text="message"></span>
            </div>


        <div class="flex justify-between items-center mb-6">
            <div class="flex space-x-4" x-data="{
                openPrice: false, openCategory: false, openSort: false,
                minPrice: new URLSearchParams(window.location.search).get('min_price') || 0,
                maxPrice: new URLSearchParams(window.location.search).get('max_price') || 2000,
                selectedCategories: (new URLSearchParams(window.location.search).get('categories') || '').split(',').filter(Boolean),
                sort: new URLSearchParams(window.location.search).get('sort') || ''
            }">

                <div class="relative">
                    <button @click="openPrice = !openPrice" class="bg-gray-200 px-3 py-1 rounded-full text-gray-700 text-sm font-semibold">Prix</button>
                    <div x-show="openPrice" @click.away="openPrice = false" class="absolute bg-white shadow-lg p-4 rounded-lg mt-2 w-64 z-50">
                        <h3 class="text-md font-bold text-gray-800 mb-2">Prix</h3>
                        <div class="flex justify-between items-center text-sm text-gray-700">
                            <div>
                                <label for="min-price">Min (EUR)</label>
                                <input type="number" id="min-price" x-model="minPrice" class="border rounded p-1 w-16 text-center">
                            </div>
                            <div>
                                <label for="max-price">Max (EUR)</label>
                                <input type="number" id="max-price" x-model="maxPrice" class="border rounded p-1 w-16 text-center">
                            </div>
                        </div>
                        <button @click="let params = new URLSearchParams(window.location.search);
                                        params.set('min_price', minPrice);
                                        params.set('max_price', maxPrice);
                                        window.location.search = params.toString();"
                                class="mt-3 w-full bg-black text-white py-2 rounded-lg font-semibold">
                            Voir les produits
                        </button>
                    </div>
                </div>

                <div class="relative">
                    <button @click="openCategory = !openCategory" class="bg-gray-200 px-3 py-1 rounded-full text-gray-700 text-sm font-semibold">Catégorie</button>
                    <div x-show="openCategory" @click.away="openCategory = false" class="absolute bg-white shadow-lg p-4 rounded-lg mt-2 w-64 z-50">
                        <h3 class="text-md font-bold text-gray-800 mb-2">Catégorie</h3>
                        <div class="flex flex-col space-y-2 text-gray-700">
                            @foreach($categories as $category)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" x-model="selectedCategories" value="{{ $category->id }}" class="rounded">
                                    <span>{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <button @click="let params = new URLSearchParams(window.location.search);
                                        params.set('categories', selectedCategories.join(','));
                                        window.location.search = params.toString();"
                                class="mt-3 w-full bg-black text-white py-2 rounded-lg font-semibold">
                            Voir les produits
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex space-x-4" x-data="{ openSort: false }">
                <div class="relative">
                    <button @click="openSort = !openSort" class="bg-gray-200 px-3 py-1 rounded-full text-gray-700 text-sm font-semibold">Trier ▼</button>
                    <div x-show="openSort" @click.away="openSort = false" class="absolute bg-white shadow-lg p-4 rounded-lg mt-2 w-48 z-50">
                        <h3 class="text-md font-bold text-gray-800 mb-2">Trier par</h3>
                        <div class="flex flex-col space-y-2 text-gray-700">
                            <button @click="let params = new URLSearchParams(window.location.search);
                                            params.set('sort', 'asc');
                                            window.location.search = params.toString();"
                                    class="block text-left px-4 py-2 hover:bg-gray-200 rounded">
                                Prix croissant
                            </button>
                            <button @click="let params = new URLSearchParams(window.location.search);
                                            params.set('sort', 'desc');
                                            window.location.search = params.toString();"
                                    class="block text-left px-4 py-2 hover:bg-gray-200 rounded">
                                Prix décroissant
                            </button>
                        </div>
                    </div>
                </div>
                <button @click="window.location.href = window.location.pathname" class="bg-red-500 px-3 py-1 rounded-full text-white text-sm font-semibold hover:bg-red-600">Réinitialiser</button>
            </div>
        </div>

        @if($products->isEmpty())
            <div class="flex flex-col items-center justify-center bg-gray-100 p-6 rounded-lg shadow-md mt-10">
                <h2 class="text-xl font-bold text-gray-800">Aucun produit trouvé</h2>
                <p class="text-gray-600 mt-2">On a cherché partout, mais aucun produit ne correspond à vos critères.</p>
                <img src="/images/no-products-found.png" alt="Aucun produit trouvé" class="w-40 h-auto mt-4">
                <a href="{{ route('products.index') }}" class="mt-4 bg-black text-white px-6 py-2 rounded-lg font-medium">Retour aux produits</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-6">
                @foreach($products as $product)
                    <a href="{{ route('products.show', $product->id) }}">
                    <div class="bg-white p-3 rounded-lg shadow-lg hover:shadow-2xl transition duration-300 text-sm w-68 h-80 cursor-pointer relative">
                        <div class="relative overflow-hidden rounded-lg h-48 flex items-center justify-center">
                            <img src="{{ asset($product->images->first()->image_path ?? '/images/default.jpg') }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-contain rounded-lg">
                        </div>

                        <h3 class="text-md font-bold text-gray-800 truncate mt-3">{{ $product->name }}</h3>
                        <p class="text-gray-600 font-semibold">À partir de <span class="text-lg font-bold text-black">{{ $product->price }} €</span></p>
                        <div class="flex items-center mt-1">
                            @include('components.rating', ['rating' => $product->reviews_avg_rating, 'reviews_count' => $product->reviews_count])
                        </div>

                        <p class="text-sm font-semibold mt-2">
                            @if ($product->stock === 0)
                                <span class="text-red-600">❌ Article indisponible</span>
                            @elseif ($product->stock < 10)
                                <span class="text-orange-500">⚠️ Plus que {{ $product->stock }} articles disponibles !</span>
                            @else
                                <span class="text-green-600">✅ En stock</span>
                            @endif
                        </p>

                        <button @click.prevent="
                                fetch('{{ route('cart.add', $product->id) }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    added = data.success ? 'success' : 'error';
                                    setTimeout(() => added = null, 2000);
                                    showNotification = true;
                                    message = data.success ? '✅ Produit ajouté au panier !' : '❌ Erreur lors de l\'ajout.';
                                    setTimeout(() => showNotification = false, 3000);
                                })
                                .catch(() => {
                                    added = 'error';
                                    showNotification = true;
                                    message = '❌ Erreur lors de l\'ajout au panier.';
                                    type = 'error';
                                    setTimeout(() => showNotification = false, 3000);
                                });
                            "
                                class="absolute bottom-3 right-3 bg-blue-500 text-white w-9 h-9 flex items-center justify-center rounded-full transition duration-300 hover:bg-blue-600 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>

                    </div>
                    </a>
                @endforeach
            </div>
        @endif
        </div>
    </div>
</x-app-layout>
