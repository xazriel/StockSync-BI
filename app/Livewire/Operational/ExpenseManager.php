<?php

namespace App\Livewire\Operational;

use App\Models\Expense;
use Livewire\Component;

class ExpenseManager extends Component
{
    public $description, $amount, $date;

    protected $rules = [
        'description' => 'required|min:3',
        'amount' => 'required|numeric',
        'date' => 'required|date',
    ];

    public function save()
    {
        $this->validate();

        Expense::create([
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $this->date,
        ]);

        $this->reset(['description', 'amount', 'date']);
        session()->flash('message', 'Pengeluaran berhasil dicatat!');
    }

    public function render()
    {
        return view('livewire.operational.expense-manager', [
            'expenses' => Expense::orderBy('date', 'desc')->take(5)->get()
        ]);
    }
}