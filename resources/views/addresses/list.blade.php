<div x-data="{
    menuOpen: null,
    showDeleteConfirm: false,
    showEditModal: false,
    showCreateModal: false,
    editAddress: { street: '', city: '', country: '', is_active: false },
    newAddress: { street: '', city: '', country: '', is_active: false },

    toggleMenu(id) {
        this.menuOpen = this.menuOpen === id ? null : id;
    },

    openEditModal(id) {
        fetch(`/addresses/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                this.editAddress = data;
                this.showEditModal = true;
            });
    },

    updateAddress() {
        fetch(`/addresses/update/${this.editAddress.id}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(this.editAddress)
        }).then(() => {
            window.location.reload();
        });
    },

    confirmDelete(id) {
        this.showDeleteConfirm = true;
        this.deleteAddressId = id;
    },

    deleteAddress() {
        fetch(`/addresses/delete/${this.deleteAddressId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
        }).then(() => {
            window.location.reload();
        });
    },

    openCreateModal() {
        this.showCreateModal = true;
    },

    storeAddress() {
        fetch('/addresses/store', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                street: this.newAddress.street,
                city: this.newAddress.city,
                country: this.newAddress.country,
                postal_code: this.newAddress.postal_code,
                is_active: this.newAddress.is_active ? true : false
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.address) {
                alert('✅ Adresse ajoutée avec succès !');
                this.newAddress = { street: '', city: '', country: '', postal_code: '', is_active: false }; // Réinitialise le formulaire
                this.showCreateModal = false; // Ferme le modal
                location.reload(); // Recharge la liste des adresses sans quitter la page
            } else {
                alert('❌ Une erreur est survenue.');
            }
        })
        .catch(error => console.error('Erreur:', error));
    },

    setAsDefault(id) {
    fetch(`/addresses/set-active/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Adresse principale mise à jour !');
            location.reload();
        } else {
            alert('❌ Une erreur est survenue.');
        }
    })
    .catch(error => console.error('Erreur:', error));
    },



}">

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">📍 Mes Adresses</h2>

        <div class="space-y-4">
            @foreach(auth()->user()->addresses as $address)
                <div class="border p-4 rounded-lg flex justify-between items-center">
                    <div>
                        <p class="text-gray-700">{{ $address->street }}, {{ $address->city }}, {{ $address->postal_code }}, {{ $address->country }}</p>
                        @if($address->is_active)
                            <span class="text-green-500 font-semibold">✔ Adresse principale</span>
                        @endif
                    </div>
                    <div class="relative" x-data="{ menuOpen: false }">
                        <button @click="menuOpen = !menuOpen" class="text-gray-600 hover:text-black">⋮</button>
                        <div x-show="menuOpen" class="absolute right-0 bg-white shadow-md rounded-lg w-48 p-2 z-10">
                            @if(!$address->is_active)
                                <button @click="setAsDefault({{ $address->id }})" class="block w-full text-left px-4 py-2 text-blue-600 hover:bg-gray-100">
                                    Définir comme adresse par défaut
                                </button>
                            @endif
                            <button @click="openEditModal({{ $address->id }})" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Modifier l'adresse
                            </button>
                            <button @click="confirmDelete({{ $address->id }})" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                Supprimer l'adresse
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button @click="openCreateModal()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            ➕ Ajouter une adresse
        </button>
    </div>


    <div x-show="showDeleteConfirm" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold">Êtes-vous sûr de vouloir supprimer cette adresse ?</h2>
            <div class="mt-4 flex space-x-4">
                <button @click="deleteAddress()" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Supprimer</button>
                <button @click="showDeleteConfirm = false" class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">Annuler</button>
            </div>
        </div>
    </div>

    @include('addresses.form')
    @include('addresses.create-modal')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('addressManager', () => ({
                menuOpen: null,
                showDeleteConfirm: false,
                showEditModal: false,
                showCreateModal: false,
                editAddress: {},
                newAddress: { street: '', city: '', country: '', is_active: false },

                toggleMenu(id) {
                    this.menuOpen = this.menuOpen === id ? null : id;
                },

                openEditModal(id) {
                    fetch(`/addresses/${id}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            this.editAddress = data;
                            this.showEditModal = true;
                        });
                },

                updateAddress() {
                    fetch(`/addresses/update/${this.editAddress.id}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(this.editAddress)
                    }).then(() => {
                        window.location.reload();
                    });
                },

                confirmDelete(id) {
                    this.showDeleteConfirm = true;
                    this.deleteAddressId = id;
                },

                deleteAddress() {
                    fetch(`/addresses/delete/${this.deleteAddressId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                    }).then(() => {
                        window.location.reload();
                    });
                },

                openCreateModal() {
                    this.showCreateModal = true;
                },

                storeAddress() {
                    fetch('/addresses/store', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            street: this.newAddress.street,
                            city: this.newAddress.city,
                            country: this.newAddress.country,
                            postal_code: this.newAddress.postal_code,
                            is_active: this.newAddress.is_active ? true : false
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.address) {
                                alert('✅ Adresse ajoutée avec succès !');
                                this.newAddress = { street: '', city: '', country: '', postal_code: '', is_active: false }; // Réinitialise le formulaire
                                this.showCreateModal = false; // Ferme le modal
                                location.reload(); // Recharge la liste des adresses sans quitter la page
                            } else {
                                alert('❌ Une erreur est survenue.');
                            }
                        })
                        .catch(error => console.error('Erreur:', error));
                }
            }));
        });
    </script>

</div>

