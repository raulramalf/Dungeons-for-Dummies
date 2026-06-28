@extends('layouts.app')

@section('titulo', 'Personajes')

@section('contenido')

<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <p style="color: var(--color-gris); font-size: 0.9rem;">Gestiona tus personajes</p>
    <button style="background: var(--color-rojo); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-family: Georgia, serif;">+ Nuevo Personaje</button>
</div>

<!-- MIS PERSONAJES -->
<section style="margin-bottom: 40px;">
    <h2 style="color: var(--color-gris); font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 15px;">Mis Personajes</h2>
    <div style="display: flex; flex-direction: column; gap: 12px;">

        <div style="background: #2a0a18; border-radius: 10px; padding: 20px; display: flex; align-items: center; gap: 20px; cursor: pointer; transition: background 0.2s;">
            <div style="width: 55px; height: 55px; border-radius: 50%; background: var(--color-rojo); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; flex-shrink: 0;">A</div>
            <div style="flex: 1;">
                <div style="font-weight: bold; font-size: 1.1rem;">Arathorn</div>
                <div style="color: var(--color-gris); font-size: 0.85rem; margin-top: 3px;">Guerrero · Humano · Caótico Bueno</div>
                <div style="margin-top: 8px; display: flex; gap: 8px;">
                    <span style="background: var(--color-verde); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem;">Nivel 5</span>
                    <span style="background: rgba(179,3,3,0.2); color: var(--color-rojo); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; border: 1px solid var(--color-rojo);">Activo</span>
                </div>
            </div>
            <div style="text-align: right; color: var(--color-gris); font-size: 0.85rem;">
                <div>HP <span style="color: white;">42/52</span></div>
                <div>CA <span style="color: white;">16</span></div>
                <div>Init <span style="color: white;">+2</span></div>
            </div>
        </div>

        <div style="background: #2a0a18; border-radius: 10px; padding: 20px; display: flex; align-items: center; gap: 20px; cursor: pointer;">
            <div style="width: 55px; height: 55px; border-radius: 50%; background: var(--color-verde); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; flex-shrink: 0;">Z</div>
            <div style="flex: 1;">
                <div style="font-weight: bold; font-size: 1.1rem;">Zyla Darkwhisper</div>
                <div style="color: var(--color-gris); font-size: 0.85rem; margin-top: 3px;">Maga · Elfa de la Luna · Neutral</div>
                <div style="margin-top: 8px; display: flex; gap: 8px;">
                    <span style="background: var(--color-verde); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem;">Nivel 3</span>
                    <span style="background: rgba(179,3,3,0.2); color: var(--color-rojo); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; border: 1px solid var(--color-rojo);">Activo</span>
                </div>
            </div>
            <div style="text-align: right; color: var(--color-gris); font-size: 0.85rem;">
                <div>HP <span style="color: white;">18/18</span></div>
                <div>CA <span style="color: white;">13</span></div>
                <div>Init <span style="color: white;">+3</span></div>
            </div>
        </div>

    </div>
</section>

<!-- PERSONAJES ARCHIVADOS -->
<section>
    <h2 style="color: var(--color-gris); font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 15px;">Archivados</h2>
    <div style="background: #2a0a18; border-radius: 10px; padding: 20px; display: flex; align-items: center; gap: 20px; opacity: 0.6;">
        <div style="width: 55px; height: 55px; border-radius: 50%; background: var(--color-gris); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: bold; flex-shrink: 0;">K</div>
        <div style="flex: 1;">
            <div style="font-weight: bold;">Kryx el Sombrío</div>
            <div style="color: var(--color-gris); font-size: 0.85rem; margin-top: 3px;">Pícaro · Semiorco · Nivel 7</div>
        </div>
        <span style="color: var(--color-gris); font-size: 0.8rem; border: 1px solid var(--color-gris); padding: 2px 10px; border-radius: 20px;">Archivado</span>
    </div>
</section>

<style>
    @media (max-width: 600px) {
        .stats-personaje { display: none; }
    }
</style>

@endsection