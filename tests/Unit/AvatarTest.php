<?php

namespace Tests\Unit;

use App\Support\Avatar;
use PHPUnit\Framework\TestCase;

class AvatarTest extends TestCase
{
    public function test_una_url_http_se_devuelve_tal_cual(): void
    {
        $this->assertSame(
            'https://ejemplo.com/foto.png',
            Avatar::url('https://ejemplo.com/foto.png', 'Thorgrim'),
        );
    }

    public function test_sin_avatar_se_genera_un_marcador_svg_local(): void
    {
        $url = Avatar::url(null, 'Thorgrim');

        $this->assertStringStartsWith('data:image/svg+xml,', $url);
        $this->assertStringNotContainsString('ui-avatars', $url, 'No debe depender de un servicio externo');
        $this->assertStringContainsString('>T<', rawurldecode($url), 'Muestra la inicial en mayúscula');
    }

    public function test_la_inicial_se_pone_en_mayuscula_incluso_con_acentos(): void
    {
        $this->assertStringContainsString('>Á<', rawurldecode(Avatar::marcador('ángel')));
    }

    public function test_el_marcador_escapa_caracteres_especiales(): void
    {
        $svg = rawurldecode(Avatar::marcador('<script>alert(1)</script>'));

        $this->assertStringNotContainsString('<script>', $svg);
        $this->assertStringContainsString('&lt;', $svg);
    }

    public function test_un_nombre_vacio_no_rompe_el_marcador(): void
    {
        $this->assertStringContainsString('>?<', rawurldecode(Avatar::marcador('   ')));
    }
}
