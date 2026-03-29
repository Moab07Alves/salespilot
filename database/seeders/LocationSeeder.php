<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando importação IBGE...');

        $country = Country::updateOrCreate(
            ['code' => 'BR'],
            [
                'name' => 'Brasil',
                'iso3' => 'BRA',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $this->importStates($country);

        $this->command->info('✅ Importação finalizada!');
    }

    private function importStates(Country $country)
    {
        $this->command->info('📍 Importando estados...');

        $states = Http::timeout(60)
            ->retry(3, 1000)
            ->get('https://servicodados.ibge.gov.br/api/v1/localidades/estados')
            ->json();

        $states = collect($states)->sortBy('nome')->values();

        $sort = 1;

        foreach ($states as $state) {

            $stateModel = State::updateOrCreate(
                [
                    'country_id' => $country->id,
                    'name' => $state['nome'],
                ],
                [
                    'code' => $state['sigla'],
                    'is_active' => true,
                    'sort_order' => $sort++,
                ]
            );

            $this->importCities($stateModel, $state['id']);
        }
    }

    private function importCities(State $state, int $ibgeStateId)
    {
        $this->command->info("📍 Importando cidades de {$state->name}...");

        $cities = Http::timeout(60)
            ->retry(3, 1000)
            ->get("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$ibgeStateId}/municipios")
            ->json();

        $cities = collect($cities)->sortBy('nome')->values();

        $sort = 1;

        foreach ($cities as $city) {

            City::updateOrCreate(
                [
                    'state_id' => $state->id,
                    'name' => $city['nome'],
                ],
                [
                    'is_active' => true,
                    'sort_order' => $sort++,
                ]
            );
        }
    }
}
