<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::table('products')->insert([
            ['name' => 'MacBook Pro 14" M2', 'description' => 'Ordinateur portable Apple performant avec puce M2', 'price' => 2299, 'stock' => 10, 'image' => '/images/products/macbook_pro_14.jpg', 'category_id' => 1],
            ['name' => 'Dell XPS 15', 'description' => 'Ultrabook Dell avec écran 15 pouces et processeur puissant', 'price' => 1899, 'stock' => 15, 'image' => '/images/products/dell_xps_15.jpg', 'category_id' => 1],
            ['name' => 'Lenovo ThinkPad X1 Carbon', 'description' => 'Ultrabook léger et robuste pour les professionnels', 'price' => 1699, 'stock' => 12, 'image' => '/images/products/thinkpad_x1_carbon.jpg', 'category_id' => 1],
            ['name' => 'Asus ROG Strix G16', 'description' => 'PC portable gamer avec carte graphique dédiée', 'price' => 1499, 'stock' => 8, 'image' => '/images/products/asus_rog_strix_g16.jpg', 'category_id' => 1],
            ['name' => 'HP Pavilion 15', 'description' => 'Ordinateur portable grand public avec bon rapport qualité-prix', 'price' => 899, 'stock' => 20, 'image' => '/images/products/hp_pavilion_15.jpg', 'category_id' => 1],
            ['name' => 'Epson EcoTank ET-2850', 'description' => 'Imprimante sans cartouche avec réservoirs d\'encre rechargeables', 'price' => 249, 'stock' => 25, 'image' => '/images/products/epson_ecotank_et_2850.jpg', 'category_id' => 2],
            ['name' => 'HP LaserJet Pro MFP M428fdw', 'description' => 'Imprimante laser multifonction pour les entreprises', 'price' => 379, 'stock' => 10, 'image' => '/images/products/hp_laserjet_pro.jpg', 'category_id' => 2],
            ['name' => 'Canon PIXMA TS6350', 'description' => 'Imprimante jet d\'encre couleur avec connectivité Wi-Fi', 'price' => 149, 'stock' => 30, 'image' => '/images/products/canon_pixma_ts6350.jpg', 'category_id' => 2],
            ['name' => 'Logitech MX Master 3S', 'description' => 'Souris ergonomique sans fil pour les professionnels', 'price' => 99, 'stock' => 50, 'image' => '/images/products/logitech_mx_master_3s.jpg', 'category_id' => 3],
            ['name' => 'Razer BlackWidow V4', 'description' => 'Clavier mécanique gaming avec switches Razer', 'price' => 189, 'stock' => 40, 'image' => '/images/products/razer_blackwidow_v4.jpg', 'category_id' => 3],
            ['name' => 'SteelSeries Arctis Nova Pro', 'description' => 'Casque gaming haute fidélité avec réduction de bruit', 'price' => 349, 'stock' => 15, 'image' => '/images/products/steelseries_arctis_nova_pro.jpg', 'category_id' => 3],
            ['name' => 'UGREEN USB-C Hub 7-en-1', 'description' => 'Hub USB-C multifonction avec ports HDMI et USB', 'price' => 49, 'stock' => 60, 'image' => '/images/products/ugreen_usb_c_hub.jpg', 'category_id' => 4],
            ['name' => 'Apple USB-C Digital AV Adapter', 'description' => 'Adaptateur Apple USB-C vers HDMI et USB', 'price' => 79, 'stock' => 35, 'image' => '/images/products/apple_usb_c_adapter.jpg', 'category_id' => 4],
            ['name' => 'Belkin Thunderbolt 4 Dock', 'description' => 'Station d\'accueil Thunderbolt 4 haut de gamme', 'price' => 299, 'stock' => 10, 'image' => '/images/products/belkin_thunderbolt_4_dock.jpg', 'category_id' => 4]
        ]);
    }

    public function down()
    {
        DB::table('products')->whereIn('name', [
            'MacBook Pro 14" M2', 'Dell XPS 15', 'Lenovo ThinkPad X1 Carbon', 'Asus ROG Strix G16', 'HP Pavilion 15',
            'Epson EcoTank ET-2850', 'HP LaserJet Pro MFP M428fdw', 'Canon PIXMA TS6350',
            'Logitech MX Master 3S', 'Razer BlackWidow V4', 'SteelSeries Arctis Nova Pro',
            'UGREEN USB-C Hub 7-en-1', 'Apple USB-C Digital AV Adapter', 'Belkin Thunderbolt 4 Dock'
        ])->delete();
    }
};
