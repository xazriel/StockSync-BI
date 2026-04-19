<?php

namespace App\Livewire\Operational;

use App\Models\Unit;
use Livewire\Component;

class UnitManager extends Component
{
    public $units, $name, $short_name, $unitId;
    public $isEdit = false;

    public function render()
    {
        $this->units = Unit::all();
        return view('livewire.operational.unit-manager');
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|min:2',
            'short_name' => 'required'
        ]);

        Unit::create([
            'name' => $this->name,
            'short_name' => $this->short_name
        ]);

        $this->resetInput();
        session()->flash('success', 'Satuan berhasil ditambah!');
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $this->unitId = $id;
        $this->name = $unit->name;
        $this->short_name = $unit->short_name;
        $this->isEdit = true;
    }

    public function update()
    {
        $unit = Unit::findOrFail($this->unitId);
        $unit->update([
            'name' => $this->name,
            'short_name' => $this->short_name
        ]);

        $this->resetInput();
        $this->isEdit = false;
        session()->flash('success', 'Satuan berhasil diupdate!');
    }

    public function delete($id)
    {
        // Jangan hapus ID 1 karena itu default buat 13 produk lama kita tadi
        if($id == 1) {
            session()->flash('error', 'Satuan default tidak boleh dihapus!');
            return;
        }

        Unit::destroy($id);
        session()->flash('success', 'Satuan berhasil dihapus!');
    }

    private function resetInput()
    {
        $this->name = '';
        $this->short_name = '';
        $this->unitId = null;
    }
}