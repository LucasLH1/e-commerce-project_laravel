<x-app-layout>

    <div class="container mx-auto max-w-6xl py-6 grid grid-cols-3 gap-8">
        <div class="col-span-2 bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center border-b pb-2">
                <h2 class="text-2xl font-bold mb-4">🛒 Détails de votre panier</h2>
                <a href="{{ route('products.index') }}" class="text-white bg-yellow-500 px-4 py-2 rounded-lg font-medium text-lg shadow-md hover:bg-yellow-600 transition duration-200 transform hover:scale-105">➕ Continuer mes achats</a>
            </div>
            @if(!empty($cartItems))
                @foreach($cartItems as $productId => $item)
                    <div class="flex items-center justify-between border-b py-4">
                        <div class="flex items-center space-x-4">
                            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-24 h-24 object-contain rounded">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $item['name'] }}</h3>
                                <p class="text-gray-600">{{ $item['price'] }} €</p>
                                <p class="text-green-600 text-sm">En stock</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button onclick="updateQuantity({{ $productId }}, {{ $item['quantity'] - 1 }})" class="px-3 py-2 text-gray-600 hover:bg-gray-200">−</button>
                                <span class="px-4">{{ $item['quantity'] }}</span>
                                <button onclick="updateQuantity({{ $productId }}, {{ $item['quantity'] + 1 }})" class="px-3 py-2 text-gray-600 hover:bg-gray-200">+</button>
                            </div>
                            <button onclick="removeFromCart({{ $productId }})" class="text-red-500 hover:text-red-700 font-semibold">Supprimer</button>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-gray-500 text-center py-6">Votre panier est vide.</p>
            @endif
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-bold mb-4">Récapitulatif</h3>
            <div class="border-b pb-4">
                @if(!empty($cartItems))
                    @foreach($cartItems as $item)
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-700">{{ $item['name'] }}</p>
                        <p class="text-gray-700">{{ $item['price'] }} €</p>
                    </div>
                @endforeach
                @else
                    <p class="text-gray-500 text-center py-6">Votre panier est vide.</p>
                @endif
            </div>
            <div class="mt-4">
                <label for="coupon_code" class="block text-sm font-medium text-gray-700">Code promo</label>
                <div class="flex space-x-4">
                    <input type="text" id="coupon_code" placeholder="Entrez votre code promo"
                           class="border p-2 rounded-lg w-full focus:ring focus:ring-blue-300">
                    <button onclick="applyCoupon()"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200">
                        Appliquer
                    </button>
                </div>
                <p id="coupon_error" class="text-red-500 text-sm mt-2 hidden">Le coupon n'est plus valide ou a expiré.</p>
            </div>

            <div class="mt-4 flex justify-between font-semibold text-lg">
                <p>Total TTC</p>
                <p>
                    <span id="old_total" class="text-gray-500 line-through hidden">{{ $total }} €</span>
                    <span id="new_total" class="text-green-600 font-bold">{{ $total }} €</span>
                </p>
            </div>

            <a href="{{ route('checkout') }}"
               class="mt-6 w-full bg-black text-white py-3 rounded-lg font-medium
          hover:bg-gray-800 transition duration-300 shadow-lg
          text-center flex justify-center items-center space-x-2 transform hover:scale-105">
                <span>Continuer</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

        </div>
    </div>

    <script>
        function updateQuantity(productId, quantity) {
            fetch(`/cart/update/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ quantity: quantity })
            }).then(() => window.location.reload());
        }

        function removeFromCart(productId) {
            fetch(`/cart/remove/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => window.location.reload());
        }

        function applyCoupon() {
            let couponInput = document.getElementById('coupon_code');
            let couponError = document.getElementById('coupon_error');
            let oldTotalElement = document.getElementById('old_total');
            let newTotalElement = document.getElementById('new_total');

            fetch('/cart/apply-coupon', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ code: couponInput.value })
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data); // 🔍 Vérifie la réponse du serveur dans la console

                    if (data.success) {
                        // Masquer le message d'erreur
                        couponError.style.display = 'none';

                        // Afficher l'ancien prix barré et mettre à jour le nouveau prix
                        oldTotalElement.style.display = 'inline';
                        oldTotalElement.innerText = `${data.old_total} €`;

                        newTotalElement.innerText = `Nouveau prix : ${data.new_total} €`;
                    } else {
                        // Afficher le message d'erreur
                        couponError.style.display = 'block';
                        couponError.innerText = "Le coupon n'est plus valide ou a expiré.";
                    }
                })
                .catch(error => console.error('Erreur lors de l\'application du coupon:', error));
        }

    </script>
</x-app-layout>
