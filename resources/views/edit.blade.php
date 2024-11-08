<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Editar Producto</h1>

        {{-- Formulario para editar el producto --}}
        <form action="{{ route('producto.update', $producto->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 font-semibold">Nombre del producto</label>
                <input type="text" class="w-full mt-1 p-2 border border-gray-300 rounded-md" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                @error('nombre')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="descripcion" class="block text-gray-700 font-semibold">Descripción</label>
                <textarea class="w-full mt-1 p-2 border border-gray-300 rounded-md" id="descripcion" name="descripcion" required>{{ old('descripcion', $producto->descripcion) }}</textarea>
                @error('descripcion')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="precio_compra" class="block text-gray-700 font-semibold">Precio de Compra (en colones)</label>
                <input type="number" step="0.01" class="w-full mt-1 p-2 border border-gray-300 rounded-md" id="precio_compra" name="precio_compra" value="{{ old('precio_compra', $producto->precio_compra) }}" required>
                @error('precio_compra')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="precio_consumidor" class="block text-gray-700 font-semibold">Precio de Consumidor (en colones)</label>
                <input type="number" step="0.01" class="w-full mt-1 p-2 border border-gray-300 rounded-md" id="precio_consumidor" name="precio_consumidor" value="{{ old('precio_consumidor', $producto->precio_consumidor) }}" required>
                @error('precio_consumidor')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="stock" class="block text-gray-700 font-semibold">Cantidad en inventario</label>
                <input type="number" class="w-full mt-1 p-2 border border-gray-300 rounded-md" id="stock" name="stock" value="{{ old('stock', $producto->stock) }}" required>
                @error('stock')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between">
                <button type="submit" class="bg-blue-500 text-white font-semibold px-4 py-2 rounded-md hover:bg-blue-600">Actualizar</button>
                <a href="{{ route('producto.create') }}" class="bg-gray-500 text-white font-semibold px-4 py-2 rounded-md hover:bg-gray-600">Cancelar</a>
            </div>
        </form>
    </div>

</body>
</html>
