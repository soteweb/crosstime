<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Page;
use Livewire\Attributes\Layout;

class PublicPage extends Component
{
    public $page;

    public function mount($slug)
    {
        $this->page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public-page', [
            'page' => $this->page
        ])->layout('components.layouts.app', ['title' => $this->page->title . ' - Itapúa Cross']);
    }
}
