<?php
namespace Database\Seeders;
use App\Models\{Category, Product, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        foreach ([['Admin', 'admin@volt.test', 'admin'], ['Editor', 'editor@volt.test', 'editor'], ['Customer', 'customer@volt.test', 'customer']] as [$n, $e, $r]) {
            $u = new User(['name' => $n, 'email' => $e, 'phone' => '201000000000', 'password' => 'Volt@12345']);
            $u->role = $r; $u->save();
        }
        $c = [];
        foreach ([['ساعات ذكية', 'Smart Watches', '⌚', 'tech'], ['سماعات الأذن', 'Earbuds', '🎧', 'tech'], ['إكسسوارات الموبايل', 'Mobile Accessories', '🔌', 'tech'],
                  ['عطور رجالية', "Men's Perfumes", '🧴', 'perfume'], ['عطور نسائية', "Women's Perfumes", '🌸', 'perfume']] as $i => [$ar, $en, $ic, $s])
            $c[$i] = Category::create(['name_ar' => $ar, 'name_en' => $en, 'icon' => $ic, 'section' => $s]);

        $rows = [
            [0, 'ساعة فولت برو', 'Volt Pro Watch', 'Volt', 2499, 2999, 20, 1, 'شاشة AMOLED وبطارية 7 أيام', 'AMOLED display, 7-day battery'],
            [0, 'ساعة سبورت إكس', 'Sport X Watch', 'Xiaomi', 1599, null, 35, 1, 'مقاومة للماء مع GPS', 'Water resistant with GPS'],
            [0, 'ساعة أولترا', 'Ultra Watch', 'Huawei', 3899, 4300, 12, 0, 'تصميم تيتانيوم فاخر', 'Premium titanium design'],
            [1, 'سماعات إيربودز ويف', 'Wave Earbuds', 'Volt', 899, 1199, 50, 1, 'عزل ضوضاء نشط', 'Active noise cancelling'],
            [1, 'سماعات بلوتوث ميني', 'Mini BT Earbuds', 'Anker', 599, null, 60, 1, 'صوت نقي وشحن سريع', 'Clear sound, fast charging'],
            [1, 'سماعات رأس لاسلكية', 'Wireless Headphones', 'Sony', 2799, 3200, 8, 0, 'بطارية 30 ساعة', '30-hour battery'],
            [2, 'شاحن سريع 65W', '65W Fast Charger', 'Anker', 450, 550, 80, 1, 'يشحن 3 أجهزة معًا', 'Charges 3 devices at once'],
            [2, 'كفر حماية', 'Armor Case', 'Spigen', 250, null, 100, 0, 'حماية ضد السقوط', 'Drop protection'],
            [2, 'باور بانك 20000', '20000mAh Power Bank', 'Baseus', 780, 900, 40, 1, 'شحن سريع PD', 'PD fast charging'],
            [3, 'عطر أوداسيتي', 'Audacity EDP', 'Volt Essence', 1350, 1600, 25, 1, 'خشبي حار بلمسة عنبر', 'Woody spicy with amber'],
            [4, 'عطر بتلات الليل', 'Night Petals EDP', 'Volt Essence', 1450, null, 18, 1, 'زهري مسكي ناعم', 'Soft floral musk'],
        ];
        foreach ($rows as [$ci, $ar, $en, $b, $p, $o, $s, $f, $da, $de])
            Product::create(['category_id' => $c[$ci]->id, 'name_ar' => $ar, 'name_en' => $en, 'brand' => $b, 'price' => $p, 'old_price' => $o,
                             'stock' => $s, 'is_featured' => (bool) $f, 'desc_ar' => $da, 'desc_en' => $de]);
    }
}
