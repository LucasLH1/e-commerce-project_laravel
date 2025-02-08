<x-app-layout>
    <div class="container mx-auto max-w-4xl py-6" x-data="{ showAddressModal: false }">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">💳 Paiement</h1>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-500 text-white rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-500 text-white rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Sélection de l'adresse -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">📍 Adresse de Livraison</h2>

            @php
                $activeAddress = auth()->user()->addresses()->where('is_active', true)->first();
            @endphp

            @if($activeAddress)
                <p class="text-gray-700 mb-2">
                    {{ $activeAddress->street }}, {{ $activeAddress->city }}, {{ $activeAddress->postal_code }}, {{ $activeAddress->country }}
                </p>
                <button @click="showAddressModal = true" class="text-blue-500 hover:underline">Changer d'adresse</button>
            @else
                <p class="text-red-500">Vous devez ajouter une adresse pour continuer.</p>
                <button @click="showAddressModal = true" class="mt-4 bg-green-500 text-white px-4 py-2 rounded-lg">➕ Ajouter une adresse</button>
            @endif
        </div>

        <!-- Modal de gestion des adresses -->
        <div x-show="showAddressModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-lg font-semibold mb-4">📍 Gérer mes adresses</h2>
                @include('addresses.list')
                <button @click="showAddressModal = false" class="mt-4 bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400 w-full">
                    Fermer
                </button>
            </div>
        </div>

        <!-- Récapitulatif de la commande -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">🧾 Récapitulatif de votre commande</h2>

            <!-- Affichage des produits en fonction du type de paiement -->
            <ul class="space-y-4">
                @if(isset($quickProduct))
                    <!-- Achat rapide : afficher uniquement ce produit -->
                    <li class="flex justify-between items-center border-b pb-2">
                        <div class="flex items-center space-x-4">
                            <img src="{{ asset($quickProduct['image']) }}" alt="{{ $quickProduct['name'] }}" class="w-16 h-16 object-contain">
                            <div>
                                <h3 class="text-gray-800 font-semibold">{{ $quickProduct['name'] }}</h3>
                                <p class="text-gray-600">{{ number_format($quickProduct['price'], 2) }} € x 1</p>
                            </div>
                        </div>
                        <p class="text-gray-900 font-bold">{{ number_format($quickProduct['price'], 2) }} €</p>
                    </li>
                @else
                    <!-- Achat normal : afficher les articles du panier -->
                    @foreach($cart as $id => $item)
                        <li class="flex justify-between items-center border-b pb-2">
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-contain">
                                <div>
                                    <h3 class="text-gray-800 font-semibold">{{ $item['name'] }}</h3>
                                    <p class="text-gray-600">{{ number_format($item['price'], 2) }} € x {{ $item['quantity'] }}</p>
                                </div>
                            </div>
                            <p class="text-gray-900 font-bold">{{ number_format($item['price'] * $item['quantity'], 2) }} €</p>
                        </li>
                    @endforeach
                @endif
            </ul>

            <div class="mt-6 flex justify-between font-bold text-xl">
                <p>Total à payer :</p>
                <p>
                    {{ isset($quickProduct) ? number_format($quickProduct['price'], 2) : number_format($total, 2) }} €
                </p>
            </div>

            <!-- Formulaire de paiement -->
            <!-- Formulaire de paiement -->
            <form action="{{ route('checkout.process') }}" method="POST" x-data="{ paymentMethodId: null, quickProductId: '{{ request('quick_product_id') }}' }">
                @csrf
                <input type="hidden" name="payment_method_id" x-model="paymentMethodId">
                <input type="hidden" name="quick_product_id" value="{{ $quickProduct['id'] ?? '' }}">

                <h2 class="text-lg font-semibold text-gray-800 mb-4">🔒 Sélectionnez votre méthode de paiement</h2>

                <div class="flex space-x-4">
                    @foreach($paymentMethods as $method)
                        <label class="flex items-center space-x-2 border p-3 rounded-lg cursor-pointer w-1/3"
                               :class="{ 'border-blue-500': paymentMethodId == '{{ $method->id }}' }"
                               @click="paymentMethodId = '{{ $method->id }}'">
                            @if($method->slug === 'card')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                            @elseif($method->slug === 'paypal')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 12.08v-.08a10 10 0 1 0-9.66 10 10.06 10.06 0 0 0 9.66-9.92z"></path>
                                    <line x1="8" y1="15" x2="16" y2="15"></line>
                                    <line x1="12" y1="9" x2="12" y2="15"></line>
                                </svg>
                            @elseif($method->slug === 'apple_pay')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.537 12.625a4.421 4.421 0 0 0 2.684 4.047 10.96 10.96 0 0 1-1.384 2.845c-.834 1.218-1.7 2.432-3.062 2.457-1.34.025-1.77-.794-3.3-.794-1.531 0-2.01.769-3.275.82-1.316.049-2.317-1.318-3.158-2.532-1.72-2.484-3.032-7.017-1.27-10.077A4.9 4.9 0 0 1 8.91 6.884c1.292-.025 2.51.869 3.3.869.789 0 2.27-1.075 3.828-.917a4.67 4.67 0 0 1 3.66 1.984 4.524 4.524 0 0 0-2.16 3.805"/>
                                </svg>
                            @endif
                            <span>{{ $method->name }}</span>
                        </label>
                    @endforeach
                </div>

                <button type="submit" class="mt-6 w-full bg-green-500 text-white py-3 rounded-lg font-medium hover:bg-green-600 transition duration-200">
                    🛒 Valider le paiement
                </button>
            </form>



        </div>
    </div>
</x-app-layout>
