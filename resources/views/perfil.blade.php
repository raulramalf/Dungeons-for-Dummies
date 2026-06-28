@extends('layouts.app')

@section('titulo', 'Perfil')

@section('contenido')

<div style="max-width: 1000px; margin: 0 auto;">

    <!-- CABECERA PERFIL -->
    <div style="background: #2a0a18; border-radius: 10px; padding: 30px; display: flex; align-items: center; gap: 25px; margin-bottom: 25px;">
        <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--color-rojo); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; flex-shrink: 0; border: 3px solid var(--color-rojo);">R</div>
        <div>
            <div style="font-size: 1.3rem; font-weight: bold;">RAULRAMALF</div>
            <div style="color: var(--color-gris); font-size: 0.85rem;">Jugador · Miembro desde 2026</div>
            <div style="display: flex; gap: 20px; margin-top: 12px;">
                <div style="text-align: center;">
                    <div style="font-size: 1.2rem; font-weight: bold; color: var(--color-rojo);">2</div>
                    <div style="color: var(--color-gris); font-size: 0.75rem;">Personajes</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.2rem; font-weight: bold; color: var(--color-rojo);">2</div>
                    <div style="color: var(--color-gris); font-size: 0.75rem;">Campañas</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.2rem; font-weight: bold; color: var(--color-rojo);">10</div>
                    <div style="color: var(--color-gris); font-size: 0.75rem;">Sesiones</div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORMULARIO -->
    <div style="background: #2a0a18; border-radius: 10px; padding: 25px; margin-bottom: 15px;">
        <h2 style="color: var(--color-gris); font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 20px;">Mi Perfil</h2>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <div>
                <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Nombre</label>
                <input type="text" value="Raúl" style="width: 100%; background: #120309; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 12px 15px; color: white; font-family: Georgia, serif; font-size: 0.9rem;">
            </div>
            <div>
                <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Apellidos</label>
                <input type="text" value="Ramírez" style="width: 100%; background: #120309; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 12px 15px; color: white; font-family: Georgia, serif; font-size: 0.9rem;">
            </div>
            <div>
                <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Correo electrónico</label>
                <input type="email" value="raulramalf@email.com" style="width: 100%; background: #120309; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 12px 15px; color: white; font-family: Georgia, serif; font-size: 0.9rem;">
            </div>
            <button style="background: var(--color-rojo); color: white; border: none; padding: 14px; border-radius: 8px; cursor: pointer; font-family: Georgia, serif; font-size: 1rem; letter-spacing: 1px; margin-top: 5px;">Guardar Cambios</button>
        </div>
    </div>

    <!-- OPCIONES -->
    <div style="display: flex; flex-direction: column; gap: 10px;">
        <div style="background: #2a0a18; border-radius: 10px; padding: 18px 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
            <span>Preferencias de notificaciones</span>
            <span style="color: var(--color-gris);">›</span>
        </div>
        <div style="background: #2a0a18; border-radius: 10px; padding: 18px 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
            <span>Privacidad y seguridad</span>
            <span style="color: var(--color-gris);">›</span>
        </div>
        <div style="background: #2a0a18; border-radius: 10px; padding: 18px 20px; cursor: pointer; color: var(--color-rojo);">
            Cerrar sesión
        </div>
    </div>

</div>

@endsection