<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class QuienesSomosPageSeeder extends Seeder
{
    public function run(): void
    {
        $content = <<<'HTML'
<div style="text-align: center; margin-bottom: 2.5rem;">
    <img src="/storage/pages/quienes-somos-banner.png"
         alt="Itapúa Cross - Organización de Eventos Deportivos"
         style="width: 100%; max-height: 480px; object-fit: cover; border-radius: 1.25rem; box-shadow: 0 20px 60px rgba(0,0,0,0.15);" />
</div>

<p style="font-size: 1.15rem; color: #374151; line-height: 1.8;">
    Somos una empresa encargada de la organización de <strong>Eventos Deportivos</strong>, Cicloturismo, Running, Carreras de Aventura, Turismo Aventura, Ciclismo extremo y <strong>Fabricación de trofeos personalizados</strong>.
</p>

<div style="background: linear-gradient(135deg, #E30613 0%, #f97316 100%); color: white; border-radius: 1rem; padding: 2rem 2.5rem; margin: 2rem 0; text-align: center;">
    <p style="font-size: 1.4rem; font-weight: 900; letter-spacing: -0.02em; margin: 0; line-height: 1.5;">
        Nuestro principal objetivo es que <span style="text-transform: uppercase; font-size: 1.6rem;">TU EVENTO</span> sea todo un Éxito,<br>
        que todos se diviertan y que vuelvan en la Próxima!!!
    </p>
</div>

<h2 style="font-size: 1.5rem; font-weight: 900; color: #111827; margin-top: 2rem; margin-bottom: 1rem;">
    🎪 Nuestros Servicios
</h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin: 1.5rem 0;">
    <div style="background: #fff7ed; border: 2px solid #fed7aa; border-radius: 1rem; padding: 1.5rem; text-align: center;">
        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🏃</div>
        <h3 style="font-weight: 800; color: #ea580c; margin: 0 0 0.5rem 0;">Running &amp; Trail</h3>
        <p style="color: #78350f; font-size: 0.9rem; margin: 0;">Carreras de fondo, trail running y eventos nocturnos.</p>
    </div>
    <div style="background: #fff7ed; border: 2px solid #fed7aa; border-radius: 1rem; padding: 1.5rem; text-align: center;">
        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🚴</div>
        <h3 style="font-weight: 800; color: #ea580c; margin: 0 0 0.5rem 0;">Ciclismo</h3>
        <p style="color: #78350f; font-size: 0.9rem; margin: 0;">Cicloturismo, mountain bike y ciclismo extremo.</p>
    </div>
    <div style="background: #fff7ed; border: 2px solid #fed7aa; border-radius: 1rem; padding: 1.5rem; text-align: center;">
        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🏔️</div>
        <h3 style="font-weight: 800; color: #ea580c; margin: 0 0 0.5rem 0;">Aventura</h3>
        <p style="color: #78350f; font-size: 0.9rem; margin: 0;">Carreras de aventura y turismo de naturaleza.</p>
    </div>
    <div style="background: #fff7ed; border: 2px solid #fed7aa; border-radius: 1rem; padding: 1.5rem; text-align: center;">
        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🏆</div>
        <h3 style="font-weight: 800; color: #ea580c; margin: 0 0 0.5rem 0;">Trofeos</h3>
        <p style="color: #78350f; font-size: 0.9rem; margin: 0;">Fabricación de trofeos y medallas personalizadas.</p>
    </div>
</div>

<h2 style="font-size: 1.5rem; font-weight: 900; color: #111827; margin-top: 2.5rem; margin-bottom: 1rem;">
    🎯 Equipamiento para tu Evento
</h2>

<p style="font-size: 1.05rem; color: #374151; line-height: 1.8;">
    Además realizamos <strong>alquiler de equipos</strong> como ser:
</p>

<ul style="list-style: none; padding: 0; margin: 1rem 0 1.5rem 0; display: flex; flex-direction: column; gap: 0.75rem;">
    <li style="display: flex; align-items: center; gap: 0.75rem; background: #f9fafb; border-radius: 0.75rem; padding: 0.875rem 1.25rem; border-left: 4px solid #E30613;">
        <span style="font-size: 1.4rem;">🎪</span>
        <span style="font-weight: 700; color: #111827;">Arcos inflables de largada / llegada</span>
    </li>
    <li style="display: flex; align-items: center; gap: 0.75rem; background: #f9fafb; border-radius: 0.75rem; padding: 0.875rem 1.25rem; border-left: 4px solid #E30613;">
        <span style="font-size: 1.4rem;">🔊</span>
        <span style="font-weight: 700; color: #111827;">Equipos de audio profesional</span>
    </li>
    <li style="display: flex; align-items: center; gap: 0.75rem; background: #f9fafb; border-radius: 0.75rem; padding: 0.875rem 1.25rem; border-left: 4px solid #E30613;">
        <span style="font-size: 1.4rem;">✅</span>
        <span style="font-weight: 700; color: #111827;">Todo lo que tu evento necesita para ser de calidad</span>
    </li>
</ul>

<div style="background: #111827; color: white; border-radius: 1rem; padding: 2rem 2.5rem; text-align: center; margin-top: 2.5rem;">
    <p style="font-size: 1.2rem; font-weight: 800; margin: 0 0 0.5rem 0;">¿Querés organizar tu evento con nosotros?</p>
    <p style="color: #9ca3af; font-size: 0.95rem; margin: 0;">Contactanos y hacemos de tu carrera una experiencia inolvidable.</p>
</div>
HTML;

        Page::updateOrCreate(
            ['slug' => 'quienes-somos'],
            [
                'title'     => 'Quiénes Somos',
                'slug'      => 'quienes-somos',
                'content'   => $content,
                'is_active' => true,
            ]
        );
    }
}
