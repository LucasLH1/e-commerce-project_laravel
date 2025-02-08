<x-app-layout>
    <div class="container mx-auto max-w-4xl py-6 px-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">📩 Contactez-nous</h1>
        <p class="text-gray-700 mb-4">
            Vous avez une question ou besoin d'aide ? Remplissez le formulaire ci-dessous et nous vous répondrons rapidement.
        </p>

        <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700">Nom</label>
                <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg" required>
            </div>

            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg" required>
            </div>

            <div>
                <label class="block text-gray-700">Message</label>
                <textarea name="message" class="w-full px-4 py-2 border rounded-lg h-32" required></textarea>
            </div>

            <button type="submit" class="bg-green-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-600 transition">
                ✉️ Envoyer
            </button>
        </form>
    </div>
</x-app-layout>
