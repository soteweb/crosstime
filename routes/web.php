<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\EventRegistration;
use App\Livewire\EventAdmin;

use App\Livewire\EventList;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Athletes;

Route::get('/', EventList::class)->name('home');
Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
Route::get('/logout', function() {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::middleware(['auth'])->prefix('admin')->group(function() {
    Route::get('/', Dashboard::class)->name('admin.dashboard');
    Route::get('/events', EventAdmin::class)->name('admin.events');
    Route::get('/events/create', \App\Livewire\Admin\EventEditor::class)->name('admin.events.create');
    Route::get('/events/{event}/edit', \App\Livewire\Admin\EventEditor::class)->name('admin.events.edit');
    Route::get('/event-types', \App\Livewire\Admin\EventTypeAdmin::class)->name('admin.event-types');
    Route::get('/pages', \App\Livewire\Admin\PagesAdmin::class)->name('admin.pages');
    Route::get('/pages/create', \App\Livewire\Admin\PageEditor::class)->name('admin.pages.create');
    Route::get('/pages/{page}/edit', \App\Livewire\Admin\PageEditor::class)->name('admin.pages.edit');
    Route::get('/menus', \App\Livewire\Admin\MenuAdmin::class)->name('admin.menus');
    Route::get('/athletes', Athletes::class)->name('admin.athletes');
    Route::get('/finanzas', \App\Livewire\Admin\FinanceReport::class)->name('admin.finance');
    Route::get('/usuarios', \App\Livewire\Admin\UsersAdmin::class)->name('admin.users');

    // TinyMCE image upload endpoint
    Route::post('/upload-image', function (\Illuminate\Http\Request $request) {
        $request->validate(['file' => 'required|image|max:4096']);
        $path = $request->file('file')->store('cms', 'public');
        return response()->json(['location' => asset('storage/' . $path)]);
    })->name('admin.upload-image');
});
Route::get('/events/{event}/register', EventRegistration::class)->name('events.register');
Route::get('/eventos/{event}', \App\Livewire\EventShow::class)->name('event.show');
Route::get('/eventos/{event}/inscriptos', \App\Livewire\EventInscribedList::class)->name('event.inscriptos');

// Paginated specific routes
Route::get('/eventos-proximos', \App\Livewire\EventsUpcoming::class)->name('events.upcoming');
Route::get('/resultados-pasados', \App\Livewire\EventsPast::class)->name('events.past');

// CMS Dynamic Catch-All Route (must stay at the bottom)
Route::get('/{slug}', \App\Livewire\PublicPage::class)->name('page.show');
