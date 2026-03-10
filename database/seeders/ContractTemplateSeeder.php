<?php

namespace Database\Seeders;

use App\Models\ContractTemplate;
use Illuminate\Database\Seeder;

class ContractTemplateSeeder extends Seeder
{
    public function run(): void
    {
        ContractTemplate::firstOrCreate(
            ['slug' => 'arrendamiento_vivienda'],
            [
                'name'        => 'Arrendamiento de Vivienda',
                'description' => 'Contrato privado de arrendamiento para uso habitacional. Válido en la República de El Salvador.',
                'price'       => 5.00,
                'is_active'   => true,
                'default_clauses' => [
                    'El Arrendatario se obliga a pagar el canon de arrendamiento puntualmente dentro de los primeros cinco (5) días hábiles de cada mes calendario.',
                    'El inmueble objeto de este contrato será destinado exclusivamente para uso habitacional, quedando expresamente prohibido su uso comercial o industrial.',
                    'Queda prohibido al Arrendatario subarrendar total o parcialmente el inmueble, ni ceder sus derechos y obligaciones derivados de este contrato sin autorización escrita previa del Arrendador.',
                    'Al vencimiento del plazo, el Arrendatario deberá entregar el inmueble en el mismo estado en que lo recibió, salvo el desgaste natural por el uso ordinario.',
                    'Las reparaciones menores necesarias para el mantenimiento del inmueble serán a cargo del Arrendatario; las reparaciones mayores o estructurales corresponden al Arrendador.',
                    'El depósito de garantía será devuelto al Arrendatario al término del contrato, previa inspección del inmueble y deducción de daños si los hubiere.',
                    'Para todo lo no previsto en el presente contrato, las partes se someten a las leyes de la República de El Salvador.',
                ],
            ]
        );
    }
}
