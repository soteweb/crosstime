<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Club;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class ClubAdmin extends Component
{
    use WithFileUploads;

    public $isFormOpen = false;
    public $clubId, $name, $city, $logo, $is_active = true;
    public $newLogo;

    public function resetInputFields()
    {
        $this->clubId = null;
        $this->name = '';
        $this->city = '';
        $this->logo = null;
        $this->newLogo = null;
        $this->is_active = true;
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isFormOpen = true;
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
        $this->resetInputFields();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'newLogo' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $logoPath = $this->logo;
        if ($this->newLogo) {
            $logoPath = $this->newLogo->store('clubs', 'public');
        }

        if ($this->clubId) {
            Club::find($this->clubId)->update([
                'name' => $this->name,
                'city' => $this->city,
                'logo' => $logoPath,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Club actualizado con éxito.');
        } else {
            Club::create([
                'name' => $this->name,
                'city' => $this->city,
                'logo' => $logoPath,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Club creado con éxito.');
        }

        $this->closeForm();
    }

    public function edit($id)
    {
        $club = Club::findOrFail($id);
        $this->clubId = $club->id;
        $this->name = $club->name;
        $this->city = $club->city;
        $this->logo = $club->logo;
        $this->is_active = $club->is_active;
        $this->isFormOpen = true;
    }

    public function delete($id)
    {
        $club = Club::find($id);
        if ($club) {
            // Update athletes to nullify club_id
            \App\Models\Athlete::where('club_id', $club->id)->update(['club_id' => null]);
            $club->delete();
            session()->flash('message', 'Club eliminado correctamente.');
        }
    }

    public function render()
    {
        return view('livewire.admin.club-admin', [
            'clubs' => Club::orderBy('name', 'asc')->get(),
        ]);
    }
}
