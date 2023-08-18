<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Mannequin;
use Livewire\Component;
use Livewire\WithPagination;

class TableFilter extends Component
{
    use WithPagination;

    public $categoryFilter = '';

    public function render()
    {
        // $categories = Category::all();
        // $mannequins = Mannequin::when($this->categoryFilter, function ($query, $categoryFilter) {
        //     return $query->where('category', 'like', '%' . $categoryFilter . '%');
        // })->paginate(10);

        // return view('livewire.table-filter',  compact('mannequins', 'categories'));

        $categories = Category::all();
        $mannequins = Mannequin::query();
        $mannequins = Mannequin::paginate(10);
        if (!empty($this->categoryFilter)) {
            $mannequins->where('category', 'like', '%' . $this->categoryFilter . '%');
        }
        // $mannequins = $mannequins->get();

        return view('livewire.table-filter', ['mannequins' => $mannequins, 'categories'=>$categories]);


    }
}

