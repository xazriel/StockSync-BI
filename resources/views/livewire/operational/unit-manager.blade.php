<div class="p-6">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">📏 Manajemen Satuan (Units)</h2>
        </div>

        <div class="p-6">
            {{-- Notifikasi --}}
            @if (session()->has('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg font-bold text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Form Input --}}
            <div class="flex gap-4 mb-8 bg-blue-50 p-4 rounded-lg border border-blue-100 items-end">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-blue-700 uppercase mb-1">Nama Satuan</label>
                    <input type="text" wire:model="name" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500" placeholder="Contoh: Pcs">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-blue-700 uppercase mb-1">Singkatan</label>
                    <input type="text" wire:model="short_name" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500" placeholder="pcs">
                </div>
                <div>
                    <button wire:click="{{ $isEdit ? 'update' : 'store' }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold text-sm hover:bg-blue-700 transition-all uppercase">
                        {{ $isEdit ? 'UPDATE' : 'SIMPAN' }}
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 border-b">Nama Satuan</th>
                            <th class="px-6 py-3 border-b text-center">Singkatan</th>
                            <th class="px-6 py-3 border-b text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($units as $u)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $u->name }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-gray-100 px-2 py-1 rounded text-xs font-mono text-gray-600">{{ $u->short_name }}</span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <button wire:click="edit({{ $u->id }})" class="text-blue-600 font-bold hover:underline">Edit</button>
                                @if($u->id != 1)
                                    <button wire:click="delete({{ $u->id }})" class="text-red-600 font-bold hover:underline" onclick="return confirm('Yakin hapus?') || event.stopImmediatePropagation()">Hapus</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>