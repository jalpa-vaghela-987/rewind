<?php

namespace Database\Seeders;

use App\Models\ProjectType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectType::insert($this->getTypes());
    }

    public function getTypes()
    {
        return [
            [
                "type" => "Forest- ARB",
                "abbreviation" => "F-ARB",
                "is_active" => true,
                "image" => 'icon-Forest-ERB',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Livestock-ARB",
                "abbreviation" => "LV-ARB",
                "is_active" => true,
                "image" => 'icon-Livestock-ERB',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Mine Methane Capture-ARB",
                "abbreviation" => "MMC-ARB",
                "is_active" => true,
                "image" => 'icon-Mine-Methane-Capture-ARB',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Ozone Depleting Substances-ARB",
                "abbreviation" => "ODS-ARB",
                "is_active" => true,
                "image" => 'icon-Ozone-Delpleting-Substances-ARB',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Rice-ARB",
                "abbreviation" => "Rice-ARB",
                "is_active" => true,
                "image" => 'icon-Rice-ARB',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Urban Forest-ARB",
                "abbreviation" => "UF-ARB",
                "is_active" => true,
"image" => 'icon-Urban-Forest-ARB',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Adipic Acid Production",
                "abbreviation" => "AAP",
                "is_active" => true,
"image" => 'icon-Adipic-Acid-Production',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Biochar",
                "abbreviation" => "B",
                "is_active" => true,
"image" => 'icon-Biochar',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Canada Grassland",
                "abbreviation" => "CG",
                "is_active" => true,
"image" => 'icon-Canada-Grassland',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Coal Mine Methane",
                "abbreviation" => "CMM",
                "is_active" => true,
"image" => 'icon-Coal-Mine-Methane',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Forest",
                "abbreviation" => "F",
                "is_active" => true,
"image" => 'icon-Rice-Cultivation',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Grassland",
                "abbreviation" => "G",
                "is_active" => true,
"image" => 'icon-Grassland',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Mexico Boiler Efficiency",
                "abbreviation" => "MBE",
                "is_active" => true,
"image" => 'icon-Mexico-Boiler-Efficiency',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Mexico Forest",
                "abbreviation" => "MF",
                "is_active" => true,
"image" => 'icon-Mexico-Forest',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Mexico Halocarbon",
                "abbreviation" => "MH",
                "is_active" => true,
"image" => 'icon-Mexico-Halocarbon',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Mexico Landfill",
                "abbreviation" => "MLF",
                "is_active" => true,
"image" => 'icon-Mexico-Landfill',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Mexico Livestock",
                "abbreviation" => "MLV",
                "is_active" => true,
"image" => 'icon-Mexico-Livestock',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Nitric Acid Production",
                "abbreviation" => "NAP",
                "is_active" => true,
"image" => 'icon-Nitric-Acid-Production',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Nitrogen Management",
                "abbreviation" => "NM",
                "is_active" => true,
"image" => 'icon-Nitrogen-Management',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Organic Waste Composting",
                "abbreviation" => "OWC",
                "is_active" => true,
"image" => 'icon-Organic-Waste-Composting',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Organic Waste Digesting",
                "abbreviation" => "OWD",
                "is_active" => true,
"image" => 'icon-Organic-Waste-Digesting',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Ozone Depleting Substances",
                "abbreviation" => "ODS",
                "is_active" => true,
"image" => 'icon-Ozone-Depleting-Substances',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Rice Cultivation",
                "abbreviation" => "RC",
                "is_active" => true,
"image" => 'icon-Rice-Cultivation',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Soil Enrichment",
                "abbreviation" => "SEP",
                "is_active" => true,
"image" => 'icon-Soil-Enrichment',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Urban Forest Management",
                "abbreviation" => "UFM",
                "is_active" => true,
"image" => 'icon-Urban-Forest-Management',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "Urban Tree Planting",
                "abbreviation" => "UTP",
                "is_active" => true,
"image" => 'icon-Urban-Tree-Planting',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "US Landfill",
                "abbreviation" => "LF",
                "is_active" => true,
"image" => 'icon-US-Landfill',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
            [
                "type" => "US Livestock",
                "abbreviation" => "LV",
                "is_active" => true,
"image" => 'icon-US-Livestock',
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
        ];
    }
}
