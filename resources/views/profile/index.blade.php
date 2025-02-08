<x-app-layout>
    <div x-data="{ tab: 'informations' }" class="container mx-auto max-w-6xl py-6 p-6">

        <!-- Navigation des onglets -->
        <div class="flex space-x-6 border-b pb-4 mb-6">
            <button @click="tab = 'informations'"
                    :class="tab === 'informations' ? 'border-b-2 border-blue-500 text-blue-500 font-semibold' : 'text-gray-600'"
                    class="px-6 py-3 transition duration-300 hover:text-blue-500">
                Informations personnelles
            </button>
            <button @click="tab = 'orders'"
                    :class="tab === 'orders' ? 'border-b-2 border-blue-500 text-blue-500 font-semibold' : 'text-gray-600'"
                    class="px-6 py-3 transition duration-300 hover:text-blue-500">
                Historique de commande
            </button>
            <button @click="tab = 'security'"
                    :class="tab === 'security' ? 'border-b-2 border-blue-500 text-blue-500 font-semibold' : 'text-gray-600'"
                    class="px-6 py-3 transition duration-300 hover:text-blue-500">
                Sécurité
            </button>
        </div>

        <!-- Contenu des onglets -->
        <div class="w-full px-8 py-6">
            <div x-show="tab === 'informations'" class="bg-white shadow rounded-lg p-6" x-cloak>
                <h2 class="text-xl font-semibold mb-4 text-gray-700">📝 Informations générales</h2>
                @livewire('profile.update-profile-information-form')
                @include('addresses.list')

            </div>

            <div x-show="tab === 'orders'" class="bg-white shadow rounded-lg p-6" x-cloak>
                <h2 class="text-xl font-semibold mb-4 text-gray-700">📅 Historique de commande</h2>
                @php
                    $orders = auth()->user()->orders;
                @endphp
                @include('orders.history', ['orders' => $orders])
            </div>

            <div x-show="tab === 'security'" class="bg-white shadow rounded-lg p-6" x-cloak>
                <h2 class="text-xl font-semibold mb-4 text-gray-700">🔒 Sécurité du compte</h2>
                @livewire('profile.update-password-form')
                @livewire('profile.two-factor-authentication-form')
                @livewire('profile.logout-other-browser-sessions-form')
                @livewire('profile.delete-user-form')            </div>
        </div>
    </div>
</x-app-layout>
