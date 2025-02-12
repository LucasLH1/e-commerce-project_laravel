<x-app-layout>
    <div class="container mx-auto max-w-4xl py-6 text-center">
        <h1 class="text-3xl font-bold text-green-600 mb-6">🎉 Commande confirmée !</h1>

        <p class="text-lg text-gray-800">Merci pour votre achat, <strong>{{ auth()->user()->name }}</strong> !</p>
        <p class="text-gray-600">Votre commande <strong>#{{ $order->id }}</strong> a été enregistrée avec succès.</p>

        <div class="mt-6 p-6 bg-white shadow-lg rounded-lg">
            <h2 class="text-lg font-semibold text-gray-800">📦 Récapitulatif de votre commande</h2>
            <p class="text-gray-700 mt-2">Total payé : <strong>{{ number_format($order->total_price, 2) }} €</strong></p>
            <p class="text-gray-700">Méthode de paiement : <strong>{{ $order->paymentMethod->name }}</strong></p>

            <a href="{{ route('profile.index') }}" class="mt-6 inline-block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                📜 Voir mes commandes
            </a>
        </div>
    </div>
</x-app-layout>
