@extends('layouts.app')

@section('titulo', 'Campañas')

@section('contenido')

<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <p style="color: var(--color-gris); font-size: 0.9rem;">Tus aventuras activas y pasadas</p>
    <button style="background: var(--color-rojo); color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-family: Georgia, serif;">+ Crear Campaña</button>
</div>

<!-- ACTIVAS -->
<section style="margin-bottom: 40px;">
    <h2 style="color: var(--color-gris); font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 15px;">Activas</h2>
    <div style="display: flex; flex-direction: column; gap: 12px;">
        <div style="background: #2a0a18; border-radius: 10px; padding: 25px; border-left: 4px solid var(--color-rojo);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 1.2rem; font-weight: bold;">La Maldición de Strahd</div>
                    <div style="color: var(--color-gris); font-size: 0.85rem; margin-top: 5px;">DM: raulramalf · 4 jugadores</div>
                    <div style="color: var(--color-gris); font-size: 0.85rem;">Sesión 7 · hace 2 días</div>
                    <div style="margin-top: 12px; display: flex; gap: 8px;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-rojo); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: bold;">R</div>
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-gris); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: bold;">M</div>
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--color-verde); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: bold;">J</div>
                    </div>
                </div>
                <span style="background: rgba(179,3,3,0.2); color: var(--color-rojo); padding: 4px 14px; border-radius: 20px; font-size: 0.8rem; border: 1px solid var(--color-rojo);">Activa</span>
            </div>
        </div>
    </div>
</section>

<!-- PAUSADAS -->
<section style="margin-bottom: 40px;">
    <h2 style="color: var(--color-gris); font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 15px;">Pausadas</h2>
    <div style="background: #2a0a18; border-radius: 10px; padding: 25px; border-left: 4px solid var(--color-gris);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: 1.2rem; font-weight: bold;">Descent into Avernus</div>
                <div style="color: var(--color-gris); font-size: 0.85rem; margin-top: 5px;">DM: darkmaster · 3 jugadores</div>
                <div style="color: var(--color-gris); font-size: 0.85rem;">Sesión 3 · hace 1 semana</div>
            </div>
            <span style="background: rgba(118,133,150,0.2); color: var(--color-gris); padding: 4px 14px; border-radius: 20px; font-size: 0.8rem; border: 1px solid var(--color-gris);">Pausada</span>
        </div>
    </div>
</section>

<!-- UNIRSE -->
<section style="margin-bottom: 40px;">
    <h2 style="color: var(--color-gris); font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 15px;">Unirse a una campaña</h2>
    <div style="background: #2a0a18; border-radius: 10px; padding: 20px; display: flex; gap: 10px; align-items: center;">
        <input type="text" placeholder="Introduce el código de invitación..." style="flex: 1; background: #120309; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 12px 15px; color: white; font-family: Georgia, serif; font-size: 0.9rem;">
        <button style="background: var(--color-rojo); color: white; border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-size: 1.1rem;">→</button>
    </div>
</section>

<!-- FINALIZADAS -->
<section>
    <h2 style="color: var(--color-gris); font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 15px;">Finalizadas</h2>
    <div style="background: #2a0a18; border-radius: 10px; padding: 20px; display: flex; align-items: center; gap: 15px; opacity: 0.6;">
        <div style="width: 50px; height: 50px; border-radius: 8px; background: #120309; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">🏰</div>
        <div>
            <div style="font-weight: bold;">La Tumba de la Aniquilación</div>
            <div style="color: var(--color-gris); font-size: 0.85rem;">15 sesiones · 5 jugadores</div>
            <span style="color: var(--color-gris); font-size: 0.75rem; border: 1px solid var(--color-gris); padding: 1px 8px; border-radius: 10px; margin-top: 5px; display: inline-block;">Completada</span>
        </div>
    </div>
</section>

@endsection