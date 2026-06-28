@extends('layouts.app')

@section('titulo', 'Enemigos')

@section('contenido')

<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <p style="color: var(--color-gris); font-size: 0.9rem;">Bestiario de tu campaña</p>
    <button style="background: var(--color-rojo); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-family: Georgia, serif;">+ Añadir Enemigo</button>
</div>

<!-- BUSCADOR -->
<div style="margin-bottom: 25px;">
    <input type="text" placeholder="Buscar enemigo..." style="width: 100%; max-width: 400px; background: #2a0a18; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 12px 15px; color: white; font-family: Georgia, serif; font-size: 0.9rem;">
</div>

<!-- LISTA -->
<section>
    <h2 style="color: var(--color-gris); font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 15px;">Criaturas</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px;">

        <div style="background: #2a0a18; border-radius: 10px; padding: 20px; cursor: pointer;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                <div>
                    <div style="font-weight: bold; font-size: 1.05rem;">Vampiro Anciano</div>
                    <div style="color: var(--color-gris); font-size: 0.8rem;">No-muerto · Mediano</div>
                </div>
                <span style="background: rgba(212,96,67,0.2); color: var(--color-naranja); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; border: 1px solid var(--color-naranja);">CR 13</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; text-align: center;">
                <div style="background: #120309; border-radius: 6px; padding: 8px;">
                    <div style="font-size: 1rem; font-weight: bold;">144</div>
                    <div style="color: var(--color-gris); font-size: 0.7rem;">HP</div>
                </div>
                <div style="background: #120309; border-radius: 6px; padding: 8px;">
                    <div style="font-size: 1rem; font-weight: bold;">16</div>
                    <div style="color: var(--color-gris); font-size: 0.7rem;">CA</div>
                </div>
                <div style="background: #120309; border-radius: 6px; padding: 8px;">
                    <div style="font-size: 1rem; font-weight: bold;">+5</div>
                    <div style="color: var(--color-gris); font-size: 0.7rem;">Init</div>
                </div>
            </div>
        </div>

        <div style="background: #2a0a18; border-radius: 10px; padding: 20px; cursor: pointer;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                <div>
                    <div style="font-weight: bold; font-size: 1.05rem;">Lobo Sombrío</div>
                    <div style="color: var(--color-gris); font-size: 0.8rem;">Bestia · Mediano</div>
                </div>
                <span style="background: rgba(64,72,52,0.4); color: #a8b89a; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; border: 1px solid #a8b89a;">CR 1</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; text-align: center;">
                <div style="background: #120309; border-radius: 6px; padding: 8px;">
                    <div style="font-size: 1rem; font-weight: bold;">22</div>
                    <div style="color: var(--color-gris); font-size: 0.7rem;">HP</div>
                </div>
                <div style="background: #120309; border-radius: 6px; padding: 8px;">
                    <div style="font-size: 1rem; font-weight: bold;">13</div>
                    <div style="color: var(--color-gris); font-size: 0.7rem;">CA</div>
                </div>
                <div style="background: #120309; border-radius: 6px; padding: 8px;">
                    <div style="font-size: 1rem; font-weight: bold;">+2</div>
                    <div style="color: var(--color-gris); font-size: 0.7rem;">Init</div>
                </div>
            </div>
        </div>

        <div style="background: #2a0a18; border-radius: 10px; padding: 20px; border: 2px dashed rgba(179,3,3,0.3); display: flex; align-items: center; justify-content: center; cursor: pointer; min-height: 120px;">
            <div style="text-align: center; color: var(--color-gris);">
                <div style="font-size: 2rem;">+</div>
                <div style="font-size: 0.85rem;">Añadir enemigo</div>
            </div>
        </div>

    </div>
</section>

@endsection