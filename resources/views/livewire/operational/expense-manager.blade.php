<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
    <h3 class="font-bold text-gray-700 mb-4 uppercase text-sm tracking-widest">📝 Input Pengeluaran Operasional</h3>
    
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" wire:model="description" placeholder="Deskripsi (ex: Listrik)" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
            <input type="number" wire:model="amount" placeholder="Nominal (Rp)" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
            <input type="date" wire:model="date" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition text-sm">
            Simpan Pengeluaran
        </button>
    </form>

    <div class="mt-8">
        <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">5 Pengeluaran Terakhir</h4>
        <table class="min-w-full text-sm">
            <tbody class="divide-y divide-gray-100">
                @foreach($expenses as $item)
                <tr>
                    <td class="py-2 text-gray-600">{{ $item->description }}</td>
                    <td class="py-2 text-red-600 font-medium">- Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                    <td class="py-2 text-gray-400 text-xs text-right">{{ $item->date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>