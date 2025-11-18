<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamRoom;

class ExamRoomsSeeder extends Seeder
{
    /**
     * Créer les salles d'examen par défaut
     */
    public function run()
    {
        $rooms = [
            // Salles d'excellence (pour les meilleurs)
            [
                'name' => 'Salle A1',
                'code' => 'A1',
                'building' => 'Bâtiment Principal',
                'capacity' => 40,
                'level' => 'excellence',
                'is_active' => true,
            ],
            [
                'name' => 'Salle A2',
                'code' => 'A2',
                'building' => 'Bâtiment Principal',
                'capacity' => 40,
                'level' => 'excellence',
                'is_active' => true,
            ],

            // Salles moyennes
            [
                'name' => 'Salle B1',
                'code' => 'B1',
                'building' => 'Bâtiment Principal',
                'capacity' => 45,
                'level' => 'moyen',
                'is_active' => true,
            ],
            [
                'name' => 'Salle B2',
                'code' => 'B2',
                'building' => 'Bâtiment Principal',
                'capacity' => 45,
                'level' => 'moyen',
                'is_active' => true,
            ],
            [
                'name' => 'Salle B3',
                'code' => 'B3',
                'building' => 'Bâtiment Annexe',
                'capacity' => 45,
                'level' => 'moyen',
                'is_active' => true,
            ],

            // Salles faibles
            [
                'name' => 'Salle C1',
                'code' => 'C1',
                'building' => 'Bâtiment Annexe',
                'capacity' => 40,
                'level' => 'faible',
                'is_active' => true,
            ],
            [
                'name' => 'Salle C2',
                'code' => 'C2',
                'building' => 'Bâtiment Annexe',
                'capacity' => 40,
                'level' => 'faible',
                'is_active' => true,
            ],
        ];

        foreach ($rooms as $room) {
            ExamRoom::create($room);
        }

        $this->command->info('Salles d\'examen créées avec succès!');
    }
}
