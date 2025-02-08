
        @if($orders->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-600 text-lg">😔 Vous n'avez encore passé aucune commande.</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-blue-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-600 transition">Commencer vos achats 🛒</a>
            </div>
        @else
            <div class="p-6" x-data="{ openOrder: null }">
                @foreach($orders as $order)
                    <div class="border-b pb-4 mb-4">
                        <button @click="openOrder === {{ $order->id }} ? openOrder = null : openOrder = {{ $order->id }}"
                                class="flex justify-between items-center w-full text-left text-gray-800 font-semibold py-3 px-4 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                            <span>Commande <strong>#{{ $order->id }}</strong> - {{ number_format($order->total_price, 2) }} €</span>
                            <span class="px-3 py-1 rounded-lg text-white text-sm"
                                  :class="{
                                    'bg-yellow-500': '{{ $order->status }}' === 'pending',
                                    'bg-blue-500': '{{ $order->status }}' === 'processing',
                                    'bg-gray-500': '{{ $order->status }}' === 'shipped',
                                    'bg-green-500': '{{ $order->status }}' === 'delivered',
                                    'bg-red-500': '{{ $order->status }}' === 'cancelled'
                                }">
                                {{ $order->statusLabel() }}
                            </span>
                        </button>

                        <div x-show="openOrder === {{ $order->id }}" x-collapse class="mt-2 bg-gray-50 p-6 rounded-lg shadow-inner">
                            <p class="text-gray-700 text-lg">📅 Passée le : <strong>{{ $order->created_at->format('d/m/Y') }}</strong></p>
                            <p class="text-gray-700">💳 Paiement : <strong>{{ $order->paymentMethod->name ?? 'Non spécifié' }}</strong></p>
                            <p class="text-gray-700">📍 Livraison :
                                <strong>
                                    @if($order->address)
                                        {{ $order->address->street }}, {{ $order->address->city }}
                                    @else
                                        <span class="text-red-500">❌ Adresse non disponible</span>
                                    @endif
                                </strong>
                            </p>

                            <div class="mt-4">
                                <h2 class="text-lg font-semibold text-gray-800">📦 Suivi de commande</h2>
                                <div class="w-full bg-gray-200 rounded-full h-3 mt-2">
                                    <div class="bg-blue-500 h-3 rounded-full transition-all duration-300"
                                         :style="'width:' +
                                         ({{ $order->status === 'pending' ? 25 : ($order->status === 'processing' ? 50 : ($order->status === 'shipped' ? 75 : 100)) }}) + '%';">
                                    </div>
                                </div>
                                @if($order->estimatedDelivery())
                                    <p class="text-gray-700 mt-2 text-sm">📅 Livraison estimée : <strong>{{ $order->estimatedDelivery() }}</strong></p>
                                @endif
                            </div>

                            <h2 class="text-lg font-semibold text-gray-800 mt-4">🛍 Produits achetés</h2>
                            <ul class="mt-2 space-y-2">
                                @foreach($order->orderDetails as $detail)
                                    <li class="flex justify-between border-b pb-2">
                                        <span>{{ $detail->product->name }}</span>
                                        <span>{{ $detail->quantity }} x {{ number_format($detail->price, 2) }} €</span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-4 flex justify-between items-center">
                                <a href="{{ route('orders.invoice', $order->id) }}" class="text-blue-500 hover:underline">📄 Télécharger la facture</a>
                                @if($order->canBeCancelled())
                                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                            ❌ Annuler la commande
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <button @click="openOrder = null" class="mt-4 w-full bg-gray-500 text-white py-2 rounded-lg hover:bg-gray-600 transition">
                                ❌ Fermer
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
