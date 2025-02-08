<x-app-layout>
    <div class="container mx-auto max-w-6xl py-6 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white p-6 rounded-lg shadow-lg">
            <!-- Galerie d'images alignée à gauche -->
            <div class="flex space-x-4"
                 x-data="{
    activeImage: '{{ asset($product->images->first()->image_path ?? '/images/default.jpg') }}',
    images: {{ json_encode($product->images->pluck('image_path')->map(fn($path) => asset($path))) }}
}"
            >

                <div class="flex flex-col space-y-2">
                    <template x-for="image in images" :key="image">
                        <img :src="image" class="w-16 h-16 object-cover rounded-lg cursor-pointer border hover:border-blue-500 transition" @click="activeImage = image">
                    </template>
                </div>
                <div class="relative w-full h-96 flex items-center justify-center rounded-lg overflow-hidden">
                    <img :src="activeImage" alt="{{ $product->name }}" class="object-contain w-full h-full rounded-lg transition duration-300 transform hover:scale-105">
                </div>
            </div>

            <!-- Détails du produit avec mise en page améliorée -->
            <div>
                <h1 class="text-4xl font-bold text-gray-800">{{ $product->name }}</h1>
                <p class="text-lg text-gray-600 mt-2">{{ $product->description }}</p>

                <!-- Prix & stock amélioré -->
                <div class="mt-4 flex items-center space-x-4">
                    <p class="text-3xl font-bold text-green-600">{{ number_format($product->price, 2) }} €</p>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full"
                          :class="{
                            'bg-red-500 text-white': {{ $product->stock }} === 0,
                            'bg-orange-400 text-white': {{ $product->stock }} > 0 && {{ $product->stock }} < 10,
                            'bg-green-500 text-white': {{ $product->stock }} >= 10
                        }">
                        @if ($product->stock === 0)
                            ❌ Article indisponible
                        @elseif ($product->stock < 10)
                            ⚠️ Plus que {{ $product->stock }} articles disponibles !
                        @else
                            ✅ En stock
                        @endif
                    </span>
                </div>

                <!-- Boutons d'achat avec animations -->
                <div class="mt-6 flex space-x-4" x-data="{ showNotification: false, message: '' }">
                    <button @click.prevent="
                        fetch('{{ route('cart.add', $product->id) }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        })
                        .then(response => response.json())
                        .then(data => {
                            showNotification = true;
                            message = data.success ? '✅ Produit ajouté au panier !' : '❌ Erreur lors de l\'ajout.';
                            setTimeout(() => showNotification = false, 3000);
                        })
                        .catch(() => {
                            showNotification = true;
                            message = '❌ Erreur lors de l\'ajout au panier.';
                            setTimeout(() => showNotification = false, 3000);
                        });"
                            class="bg-blue-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-600 transition transform hover:scale-105 shadow-lg">
                        🛒 Ajouter au panier
                    </button>
                    <a href="{{ route('checkout.quickBuy', $product->id) }}"
                       class="bg-green-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-600 transition transform hover:scale-105 shadow-lg flex items-center justify-center">
                        ⚡ Acheter maintenant
                    </a>
                    <!-- Notification -->
                    <div x-show="showNotification" x-transition class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
                        <span x-text="message"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avis clients avec animation et filtres -->
        <div class="mt-12" x-data="{ showReviews: true }">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-semibold text-gray-800">Avis des clients ⭐</h2>
                <button @click="showReviews = !showReviews" class="text-blue-500 hover:underline">Afficher / Cacher les avis</button>
            </div>
            <div x-show="showReviews" x-transition>
                @if($product->reviews->isEmpty())
                    <p class="text-gray-500">Aucun avis pour ce produit.</p>
                @else
                    <div class="space-y-4 mt-4">
                        @foreach($product->reviews as $review)
                            <div class="border-b pb-3 p-4 rounded-lg shadow-md bg-gray-50">
                                <p class="font-semibold">{{ $review->user->name }} - {{ $review->created_at->format('d/m/Y') }}</p>
                                <p class="text-yellow-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</p>
                                <p class="text-gray-700">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
