@extends('layouts.app')

@section('titulo', 'Feed')

@section('contenido')

<div style="max-width: 1000px; margin: 0 auto;">

    <!-- FILTROS -->
    <div style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap;">
        <button onclick="filtrar(this)" class="filtro activo" data-tipo="todo" style="background: var(--color-rojo); color: white; border: 1px solid var(--color-rojo); padding: 8px 16px; border-radius: 20px; cursor: pointer; font-family: Georgia, serif; font-size: 0.85rem;">Todo</button>
        <button onclick="filtrar(this)" class="filtro" data-tipo="personaje" style="background: transparent; color: var(--color-gris); border: 1px solid var(--color-gris); padding: 8px 16px; border-radius: 20px; cursor: pointer; font-family: Georgia, serif; font-size: 0.85rem;">⚔ Personaje</button>
        <button onclick="filtrar(this)" class="filtro" data-tipo="diario" style="background: transparent; color: var(--color-gris); border: 1px solid var(--color-gris); padding: 8px 16px; border-radius: 20px; cursor: pointer; font-family: Georgia, serif; font-size: 0.85rem;">📖 Diario</button>
        <button onclick="filtrar(this)" class="filtro" data-tipo="sesion" style="background: transparent; color: var(--color-gris); border: 1px solid var(--color-gris); padding: 8px 16px; border-radius: 20px; cursor: pointer; font-family: Georgia, serif; font-size: 0.85rem;">🎲 Sesión</button>
        <button onclick="filtrar(this)" class="filtro" data-tipo="lore" style="background: transparent; color: var(--color-gris); border: 1px solid var(--color-gris); padding: 8px 16px; border-radius: 20px; cursor: pointer; font-family: Georgia, serif; font-size: 0.85rem;">📜 Lore</button>
    </div>

    <!-- NUEVA PUBLICACIÓN -->
    <div style="background: #2a0a18; border-radius: 10px; padding: 20px; margin-bottom: 25px;">
        <div style="display: flex; gap: 15px; align-items: flex-start;">
            <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--color-rojo); display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">R</div>
            <textarea placeholder="Comparte tu aventura, un personaje, o el resumen de tu última sesión..." style="flex: 1; background: #120309; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 12px 15px; color: white; font-family: Georgia, serif; font-size: 0.9rem; resize: none; height: 80px;"></textarea>
        </div>
        <div style="display: flex; justify-content: flex-end; margin-top: 12px; gap: 10px;">
            <button style="background: transparent; color: var(--color-gris); border: 1px solid var(--color-gris); padding: 8px 12px; border-radius: 8px; cursor: pointer; font-family: Georgia, serif; font-size: 0.85rem;">⚔ Personaje</button>
            <button style="background: transparent; color: var(--color-gris); border: 1px solid var(--color-gris); padding: 8px 12px; border-radius: 8px; cursor: pointer; font-family: Georgia, serif; font-size: 0.85rem;">📖 Diario</button>
            <button style="background: transparent; color: var(--color-gris); border: 1px solid var(--color-gris); padding: 8px 12px; border-radius: 8px; cursor: pointer; font-family: Georgia, serif; font-size: 0.85rem;">🎲 Sesión</button>
            <button style="background: transparent; color: var(--color-gris); border: 1px solid var(--color-gris); padding: 8px 12px; border-radius: 8px; cursor: pointer; font-family: Georgia, serif; font-size: 0.85rem;">📜 Lore</button>
            <button style="background: var(--color-rojo); color: white; border: none; padding: 8px 20px; border-radius: 8px; cursor: pointer; font-family: Georgia, serif;">Publicar</button>
        </div>
    </div>

    <!-- PUBLICACIONES -->
    <div style="display: flex; flex-direction: column; gap: 15px;">

        <div class="publicacion" data-tipo="sesion" style="background: #2a0a18; border-radius: 10px; padding: 20px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">
                <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--color-verde); display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">M</div>
                <div>
                    <div style="font-weight: bold;">darkmaster</div>
                    <div style="color: var(--color-gris); font-size: 0.8rem;">hace 2 horas</div>
                </div>
                <span style="margin-left: auto; background: rgba(64,72,52,0.4); color: #a8b89a; padding: 2px 10px; border-radius: 10px; font-size: 0.75rem; border: 1px solid #a8b89a;">📜 Sesión</span>
            </div>
            <p style="color: #ddd; font-size: 0.9rem; line-height: 1.6;">¡Sesión épica anoche! Los jugadores finalmente llegaron a Avernus y se encontraron con Zariel cara a cara. Nadie esperaba que el pícaro intentara robarle la espada... 🎲</p>
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; gap: 20px;">
                <button style="background: none; border: none; color: var(--color-gris); cursor: pointer; font-family: Georgia, serif; font-size: 0.85rem;">❤ 12</button>
                <button style="background: none; border: none; color: var(--color-gris); cursor: pointer; font-family: Georgia, serif; font-size: 0.85rem;">💬 3 comentarios</button>
            </div>
        </div>

       <div class="publicacion" data-tipo="personaje" style="background: #2a0a18; border-radius: 10px; padding: 20px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">
                <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--color-rojo); display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">R</div>
                <div>
                    <div style="font-weight: bold;">raulramalf</div>
                    <div style="color: var(--color-gris); font-size: 0.8rem;">hace 1 día</div>
                </div>
                <span style="margin-left: auto; background: rgba(179,3,3,0.2); color: var(--color-rojo); padding: 2px 10px; border-radius: 10px; font-size: 0.75rem; border: 1px solid var(--color-rojo);">⚔ Personaje</span>
            </div>
            <p style="color: #ddd; font-size: 0.9rem; line-height: 1.6;">Comparto mi personaje Arathorn, guerrero humano nivel 5. Lleva 7 sesiones sobreviviendo en Barovia. Su rasgo principal: nunca deja a nadie atrás, aunque eso le haya costado 3 muertes casi seguras 😅</p>
            <div style="background: #120309; border-radius: 8px; padding: 15px; margin-top: 12px; display: flex; align-items: center; gap: 15px;">
                <div style="width: 45px; height: 45px; border-radius: 50%; background: var(--color-rojo); display: flex; align-items: center; justify-content: center; font-weight: bold;">A</div>
                <div>
                    <div style="font-weight: bold;">Arathorn</div>
                    <div style="color: var(--color-gris); font-size: 0.8rem;">Guerrero · Humano · Nivel 5</div>
                </div>
            </div>
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; gap: 20px;">
                <button style="background: none; border: none; color: var(--color-gris); cursor: pointer; font-family: Georgia, serif; font-size: 0.85rem;">❤ 8</button>
                <button style="background: none; border: none; color: var(--color-gris); cursor: pointer; font-family: Georgia, serif; font-size: 0.85rem;">💬 1 comentario</button>
            </div>
        </div>

    </div>
</div>

<script>
    function filtrar(boton) {
        // Actualizar botones
        document.querySelectorAll('.filtro').forEach(b => {
            b.style.background = 'transparent';
            b.style.color = '#768596';
            b.style.borderColor = '#768596';
        });
        boton.style.background = '#B30303';
        boton.style.color = 'white';
        boton.style.borderColor = '#B30303';

        // Filtrar publicaciones
        const tipo = boton.dataset.tipo;
        document.querySelectorAll('.publicacion').forEach(p => {
            if (tipo === 'todo' || p.dataset.tipo === tipo) {
                p.style.display = 'block';
            } else {
                p.style.display = 'none';
            }
        });
    }
</script>

@endsection