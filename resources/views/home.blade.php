<x-app-layout>
    <div class="container mx-auto px-6 py-6">

        <div class="relative w-full bg-cover bg-center h-[400px] rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition duration-500 ease-in-out"
             style="background-image: url('{{ asset('images/banner.webp') }}');">
            <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-start text-start p-28 text-white">
                <h1 class="text-5xl font-extrabold">Nouveaux codes promo ! 🎉</h1>
                <p class="mt-2 text-xl">Jusqu'à -20% sur une tous les produits !</p>
                <a href="{{ route('products.index') }}" class="mt-4 bg-green-500 px-6 py-3 text-white font-bold rounded-lg hover:bg-green-600 transition duration-300">
                    Voir les Offres
                </a>
            </div>
        </div>

        <div class="mt-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Explorez nos Catégories</h2>
            <div class="grid grid-cols-2 md:grid-cols-6 gap-6">
                @foreach($categories as $category)
                    @php
                        $firstProduct = $category->products->first();
                        $imagePath = $firstProduct ? asset($firstProduct->images->first()->image_path) : asset('images/default-category.jpg');
                    @endphp
                    <a href="{{ url('/products?categories=' . $category->id) }}"
                       class="block bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105">
                        <img src="{{ $imagePath }}" alt="{{ $category->name }}"
                             class="w-auto relative overflow-hidden rounded-lg h-48 flex items-center justify-center">
                        <h3 class="mt-3 text-lg font-bold text-gray-800 text-center">{{ $category->name }}</h3>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="mt-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Produits Populaires</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($popularProducts as $product)
                    <a href="{{ route('products.show', $product->id) }}"
                       class="block bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105">
                        <img src="{{ asset($product->images->first()->image_path ?? '/images/default.jpg') }}" alt="{{ $product->name }}"
                             class="w-full h-48 object-contain rounded-md">
                        <h3 class="mt-3 text-lg font-bold text-gray-800">{{ $product->name }}</h3>
                        <p class="text-gray-600 mt-1 text-lg font-semibold">{{ number_format($product->price, 2) }} €</p>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="mt-16 bg-gray-100 p-12 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Ce que disent nos clients</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($testimonials as $testimonial)
                    <div class="bg-white p-6 rounded-lg shadow-md transform hover:scale-105 transition duration-300">
                        <p class="text-gray-700 italic text-lg">"{{ $testimonial->message }}"</p>
                        <div class="mt-4 flex items-center space-x-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $testimonial->name }}</h3>
                                <p class="text-gray-500">{{ $testimonial->location }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <footer class="bg-gray-900 text-white mt-16 py-10">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-2xl font-bold">🛒 Sonic</h3>
                <p class="text-gray-400 mt-2">Votre destination pour les meilleures offres en ligne.</p>
            </div>
            <div>
                <h3 class="text-xl font-semibold">Liens rapides</h3>
                <ul class="mt-3 space-y-2 text-lg">
                    <li><a href="{{ route('products.index') }}" class="hover:underline">Produits</a></li>
                    <li><a href="{{ route('about') }}" class="hover:underline">À propos</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:underline">Contact</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-xl font-semibold">Suivez-nous</h3>
                <div class="flex space-x-6 mt-3">
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook text-3xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter text-3xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram text-3xl"></i></a>
                </div>
            </div>
        </div>
    </footer>

</x-app-layout>
