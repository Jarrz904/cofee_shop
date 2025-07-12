<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk: ') . $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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
                    
                    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Penting untuk form update --}}
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
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name)" required autofocus />
                        </div>

                        <!-- Deskripsi -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <!-- Harga -->
                        <div class="mt-4">
                            <x-input-label for="price" :value="__('Harga')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price', $product->price)" required />
                        </div>
                        
                        <!-- Gambar Produk -->
                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Ganti Gambar Produk (Opsional)')" />
                            <input type="file" name="image" id="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            <p class="mt-1 text-sm text-gray-500" id="file_input_help">Kosongkan jika tidak ingin mengganti gambar.</p>

                            @if($product->image)
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded-md">
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button>
                                {{ __('Perbarui Produk') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>