<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Page;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('components.layouts.admin')]
class PagesAdmin extends Component
{
    public $pages;
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    
    public $pageId, $title, $slug, $content, $is_active = true;

    public function mount()
    {
        $this->loadPages();
    }

    public function loadPages()
    {
        $this->pages = Page::orderBy('id', 'desc')->get();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
        $this->dispatch('content-updated', content: $this->content);
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function resetInputFields()
    {
        $this->pageId = null;
        $this->title = '';
        $this->slug = '';
        $this->content = '';
        $this->is_active = true;
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $this->pageId,
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

        session()->flash('message', $this->pageId ? 'Página actualizada con éxito.' : 'Página creada con éxito.');

        $this->loadPages();
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        $this->pageId = $page->id;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->content = $page->content;
        $this->is_active = $page->is_active;
    }

    public function destroy($id)
    {
        if ($id) {
            Page::find($id)->delete();
            session()->flash('message', 'Página eliminada correctamente.');
        }

        $this->closeDeleteModal();
        $this->loadPages();
    }

    public function render()
    {
        return view('livewire.admin.pages-admin');
    }
}
