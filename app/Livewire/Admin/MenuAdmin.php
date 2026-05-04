<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\MenuItem;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class MenuAdmin extends Component
{
    public $items;
    public $isFormOpen = false;
    
    public $itemId, $name, $url, $order = 0, $target = '_self', $placement = 'header';

    public function mount()
    {
        $this->loadItems();
    }

    public function loadItems()
    {
        $this->items = MenuItem::orderBy('placement')->orderBy('order', 'asc')->get();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->order = MenuItem::where('placement', $this->placement)->max('order') + 1;
        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->itemId = null;
        $this->name = '';
        $this->url = '';
        $this->order = 0;
        $this->target = '_self';
        $this->placement = 'header';
    }

    public function store()
    {
        // Normalize target: checkbox sends false when unchecked
        if (!$this->target || $this->target === false || $this->target === '0') {
            $this->target = '_self';
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'order' => 'required|integer',
            'target' => 'required|in:_self,_blank',
            'placement' => 'required|in:header,footer',
        ]);

        if ($this->itemId) {
            MenuItem::find($this->itemId)->update([
                'name' => $this->name,
                'url' => $this->url,
                'placement' => $this->placement,
                'order' => $this->order,
                'target' => $this->target,
            ]);
            session()->flash('message', 'Enlace actualizado con éxito.');
        } else {
            MenuItem::create([
                'name' => $this->name,
                'url' => $this->url,
                'placement' => $this->placement,
                'order' => $this->order,
                'target' => $this->target,
            ]);
            session()->flash('message', 'Enlace creado con éxito.');
        }

        $this->closeForm();
        $this->loadItems();
    }

    public function edit($id)
    {
        $item = MenuItem::findOrFail($id);
        $this->itemId = $item->id;
        $this->name = $item->name;
        $this->url = $item->url;
        $this->order = $item->order;
        $this->target = $item->target;
        $this->placement = $item->placement;
        $this->isFormOpen = true;
    }

    public function delete($id)
    {
        MenuItem::find($id)?->delete();
        session()->flash('message', 'Elemento de menú eliminado.');
        $this->loadItems();
    }

    public function render()
    {
        return view('livewire.admin.menu-admin', [
            'headerItems' => $this->items->where('placement', 'header'),
            'footerItems' => $this->items->where('placement', 'footer')
        ]);
    }
}
