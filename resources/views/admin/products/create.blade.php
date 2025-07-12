<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Menampilkan Error Validasi jika ada --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oops!</strong>
                            <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                            <ul class="list-disc list-inside mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form harus memiliki enctype untuk upload file --}}
                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                        @csrf
<!-- Di dalam <form> -->
<div class="mt-4">
    <x-input-label for="category_id" :value="__('Kategori')" />
    <select name="category_id" id="category_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
        <option value="">Pilih Kategori</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @if(isset($product) && $product->category_id == $category->id) selected @endif>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>
                        <!-- Nama Produk -->
                        <div>
                            <x-input-label for="name" :value="__('Nama Produk')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        </div>

                        <!-- Deskripsi -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                        </div>

                        <!-- Harga -->
                        <div class="mt-4">
                            <x-input-label for="price" :value="__('Harga (contoh: 25000)')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price')" required />
                        </div>
                        
                        <!-- Gambar Produk -->
                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Gambar Produk (Opsional)')" />
                            <input type="file" name="image" id="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            <p class="mt-1 text-sm text-gray-500" id="file_input_help">PNG, JPG, atau GIF (Maks. 2MB).</p>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button>
                                {{ __('Simpan Produk') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>