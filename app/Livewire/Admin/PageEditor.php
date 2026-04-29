<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Page;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('components.layouts.admin')]
class PageEditor extends Component
{
    public $pageId;
    public $title = '';
    public $slug = '';
    public $content = '';
    public $is_active = true;

    public function mount($page = null)
    {
        if ($page) {
            $pageModel = Page::findOrFail($page);
            $this->pageId = $pageModel->id;
            $this->title = $pageModel->title;
            $this->slug = $pageModel->slug;
            $this->content = $pageModel->content;
            $this->is_active = $pageModel->is_active;
        }
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug' . ($this->pageId ? ',' . $this->pageId : ''),
            'content' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $finalSlug = $this->slug ?: Str::slug($this->title);

        Page::updateOrCreate(
            ['id' => $this->pageId],
            [
                'title' => $this->title,
                'slug' => $finalSlug,
                'content' => $this->content,
                'is_active' => $this->is_active ? true : false,
            ]
        );

        session()->flash('message', $this->pageId ? 'Página actualizada correctamente.' : 'Página creada correctamente.');
        
        return redirect()->route('admin.pages');
    }

    public function render()
    {
        return view('livewire.admin.page-editor');
    }
}
