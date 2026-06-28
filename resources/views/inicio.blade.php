@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')

<div>

    <!-- PERSONAJES RECIENTES -->
    <section style="margin-bottom: 40px;">
        <h2 style="color: var(--color-gris); font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 15px;">Personajes Recientes</h2>
        <div class="personajes-row" style="display: flex; gap: 15px;">
            <div style="background: #2a0a18; border-radius: 10px; padding: 20px; display: flex; align-items: center; gap: 15px; flex: 1;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--color-rojo); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: bold;">A</div>
                <div>
                    <div style="font-weight: bold;">Arathorn</div>
                    <div style="color: var(--color-gris); font-size: 0.85rem;">Guerrero · Humano</div>
                    <div style="margin-top: 5px;"><span style="background: var(--color-verde); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem;">Nivel 5</span></div>
                </div>
            </div>
            <div style="background: #2a0a18; border-radius: 10px; padding: 20px; display: flex; align-items: center; gap: 15px; flex: 1;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--color-verde); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: bold;">Z</div>
                <div>
                    <div style="font-weight: bold;">Zyla</div>
                    <div style="color: var(--color-gris); font-size: 0.85rem;">Maga · Elfa</div>
                    <div style="margin-top: 5px;"><span style="background: var(--color-verde); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem;">Nivel 3</span></div>
                </div>
            </div>
            <div style="background: #2a0a18; border-radius: 10px; padding: 20px; display: flex; align-items: center; justify-content: center; flex: 1; border: 2px dashed rgba(179,3,3,0.3); cursor: pointer;">
                <div style="text-align: center; color: var(--color-gris);">
                    <div style="font-size: 2rem;">+</div>
                    <div style="font-size: 0.85rem;">Nuevo personaje</div>
                </div>
            </div>
        </div>
    </section>

    <!-- DOS COLUMNAS -->
    <div class="grid-dos-columnas" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

        <!-- CAMPAÑAS -->
        <section>
            <h2 style="color: var(--color-gris); font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 15px;">Tus Campañas</h2>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="background: #2a0a18; border-radius: 10px; padding: 20px; border-left: 4px solid var(--color-rojo);">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-weight: bold;">La Maldición de Strahd</div>
                            <div style="color: var(--color-gris); font-size: 0.85rem; margin-top: 4px;">DM: raulramalf · 4 jugadores · Sesión 7</div>
                        </div>
                        <span style="background: rgba(179,3,3,0.2); color: var(--color-rojo); padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; border: 1px solid var(--color-rojo);">Activa</span>
                    </div>
                </div>
                <div style="background: #2a0a18; border-radius: 10px; padding: 20px; border-left: 4px solid var(--color-gris);">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-weight: bold;">Descent into Avernus</div>
                            <div style="color: var(--color-gris); font-size: 0.85rem; margin-top: 4px;">DM: darkmaster · 3 jugadores · Sesión 3</div>
                        </div>
                        <span style="background: rgba(118,133,150,0.2); color: var(--color-gris); padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; border: 1px solid var(--color-gris);">Pausada</span>
                    </div>
                </div>
                <div style="background: #2a0a18; border-radius: 10px; padding: 15px 20px; border: 2px dashed rgba(179,3,3,0.3); text-align: center; color: var(--color-gris); cursor: pointer;">
                    + Crear o unirse a campaña
                </div>
            </div>
        </section>

        <!-- GUÍA + STATS -->
        <section>
            <h2 style="color: var(--color-gris); font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 15px;">Guía del Aventurero</h2>
            <div style="background: #2a0a18; border-radius: 10px; padding: 20px; display: flex; align-items: center; gap: 20px; margin-bottom: 12px;">
                <div style="width: 50px; height: 50px; background: var(--color-rojo); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">📖</div>
                <div>
                    <div style="font-weight: bold;">¿Nuevo en D&D?</div>
                    <div style="color: var(--color-gris); font-size: 0.85rem;">Aquí tienes la guía básica de esta aplicación</div>
                    <a href="#" style="color: var(--color-rojo); font-size: 0.85rem; margin-top: 8px; display: inline-block;">Ver guía →</a>
                </div>
            </div>

            <!-- STATS RÁPIDAS -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 5px;">
                <div style="background: #2a0a18; border-radius: 10px; padding: 15px; text-align: center;">
                    <div style="font-size: 1.8rem; font-weight: bold; color: var(--color-rojo);">2</div>
                    <div style="color: var(--color-gris); font-size: 0.8rem;">Personajes</div>
                </div>
                <div style="background: #2a0a18; border-radius: 10px; padding: 15px; text-align: center;">
                    <div style="font-size: 1.8rem; font-weight: bold; color: var(--color-rojo);">2</div>
                    <div style="color: var(--color-gris); font-size: 0.8rem;">Campañas</div>
                </div>
                <div style="background: #2a0a18; border-radius: 10px; padding: 15px; text-align: center;">
                    <div style="font-size: 1.8rem; font-weight: bold; color: var(--color-rojo);">10</div>
                    <div style="color: var(--color-gris); font-size: 0.8rem;">Sesiones</div>
                </div>
                <div style="background: #2a0a18; border-radius: 10px; padding: 15px; text-align: center;">
                    <div style="font-size: 1.8rem; font-weight: bold; color: var(--color-naranja);">5</div>
                    <div style="color: var(--color-gris); font-size: 0.8rem;">Enemigos</div>
                </div>
            </div>
        </section>

    </div>
</div>

<style>
    @media (max-width: 1024px) {
        .grid-dos-columnas {
            grid-template-columns: 1fr !important;
        }
    }

    @media (max-width: 600px) {
        .personajes-row {
            flex-direction: column !important;
        }
    }
</style>

@endsection