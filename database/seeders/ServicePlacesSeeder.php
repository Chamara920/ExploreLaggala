<?php

namespace Database\Seeders;

use App\Models\ServicePlace;
use App\Models\ServicePlaceReview;
use App\Models\ServicePlaceTranslation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicePlacesSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $user = User::first() ?? User::factory()->create();

            // 1. HEALTH SERVICES: Laggala Divisional Hospital (ප්‍රාදේශීය රෝහල)
            $h1 = ServicePlace::create([
                'section' => 'health',
                'sub_category' => 'hospital',
                'status' => 'published',
                'featured' => true,
                'is_24_hours' => true,
                'phone' => '066 227 5220',
                'emergency_hotline' => '1990',
                'email' => 'hospital.laggala@health.gov.lk',
                'website' => null,
                'latitude' => 7.5268000,
                'longitude' => 80.7435000,
                'sort_order' => 1,
            ]);

            ServicePlaceTranslation::create([
                'service_place_id' => $h1->id,
                'locale' => 'si',
                'name' => 'ලග්ගල ප්‍රාදේශීය මූලික රෝහල',
                'slug' => 'laggala-divisional-hospital',
                'location_name' => 'රෝහල් පාර, නව නගරය, පල්ලේගම, ලග්ගල',
                'operating_hours' => 'පැය 24 පුරා හදිසි ප්‍රතිකාර සහ නේවාසික සත්කාර (24/7)',
                'key_facilities' => "හදිසි ප්‍රතිකාර ඒකකය (ETU)\nබාහිර රෝගී අංශය (OPD)\nනේවාසික වාට්ටු පහසුකම්\nඖෂධාගාරය සහ පූර්ණ රසායනාගාර සේවා\n1990 සුවසැරිය ගිලන්රථ මධ්‍යස්ථානය",
                'short_description' => 'ලග්ගල නව නගරයේ පිහිටි ප්‍රධාන රජයේ රෝහල වන අතර පැය 24 පුරා හදිසි ප්‍රතිකාර සහ නේවාසික වෛද්‍ය පහසුකම් සපයයි.',
                'description' => '<p>නව ලග්ගල හරිත නගරයේ නවීන පහසුකම් සහිතව ඉදිකරන ලද ලග්ගල ප්‍රාදේශීය රෝහල මඟින් ප්‍රදේශවාසීන්ට සහ නකල්ස් සංචාරකයින්ට හදිසි ප්‍රතිකාර, මාතෘ සායන, දන්ත වෛද්‍ය සහ බාහිර රෝගී සත්කාර සපයනු ලැබේ.</p>',
            ]);

            ServicePlaceTranslation::create([
                'service_place_id' => $h1->id,
                'locale' => 'en',
                'name' => 'Laggala Divisional Hospital',
                'slug' => 'laggala-divisional-hospital-en',
                'location_name' => 'Hospital Road, New Town, Pallegama, Laggala',
                'operating_hours' => '24 Hours Emergency Care & Inpatient Service (24/7)',
                'key_facilities' => "Emergency Treatment Unit (ETU)\nOutpatient Department (OPD)\nInpatient Male / Female Wards\nPharmacy & Pathology Laboratory\n1990 Suwa Seriya Ambulance Station",
                'short_description' => 'The premier government hospital in Laggala New Town providing round-the-clock emergency medical care and outpatient facilities.',
                'description' => '<p>Equipped with modern healthcare amenities in the green town of Laggala, catering to both the local populace and travelers exploring the Knuckles Forest reserve.</p>',
            ]);

            // Review for Hospital
            ServicePlaceReview::create([
                'service_place_id' => $h1->id,
                'user_id' => $user->id,
                'rating' => 5,
                'comment' => 'Very attentive nursing staff and prompt emergency care when visiting Knuckles.',
                'status' => 'approved',
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
            ]);

            // 2. SHOPS & BUSINESSES: Pallegama Central Supermarket & Supplies
            $s1 = ServicePlace::create([
                'section' => 'shops-businesses',
                'sub_category' => 'grocery',
                'status' => 'published',
                'featured' => true,
                'is_24_hours' => false,
                'phone' => '066 227 5340',
                'latitude' => 7.5252000,
                'longitude' => 80.7418000,
                'sort_order' => 1,
            ]);

            ServicePlaceTranslation::create([
                'service_place_id' => $s1->id,
                'locale' => 'si',
                'name' => 'ලග්ගල සමුපකාර සුපිරි වෙළඳසැල සහ ප්‍රධාන වෙළඳ මධ්‍යස්ථානය',
                'slug' => 'laggala-coop-supermarket',
                'location_name' => 'ප්‍රධාන වීදිය, පල්ලේගම, ලග්ගල',
                'operating_hours' => 'සඳුදා - ඉරිදා: පෙ.ව. 7:30 - ප.ව. 8:30',
                'key_facilities' => "නැවුම් එළවළු සහ පලතුරු\nවියළි ආහාර ද්‍රව්‍ය සහ සහල් තොග\nසංචාරක උපකරණ සහ කඳවුරු බැඳීමේ අත්‍යවශ්‍ය ද්‍රව්‍ය\nකාඩ්පත් මඟින් මුදල් ගෙවීම් (POS)",
                'short_description' => 'ලග්ගල ප්‍රදේශයේ දෛනික අත්‍යවශ්‍ය ආහාර ද්‍රව්‍ය, නැවුම් නිෂ්පාදන සහ සංචාරක අවශ්‍යතා සඳහා ප්‍රධාන වෙළඳසැල.',
                'description' => '<p>පල්ලේගම නව නගර මධ්‍යයේ පිහිටා ඇති මෙම සුපිරි වෙළඳසැල ගම්වැසියන් මෙන්ම නකල්ස් කඳවුරු බඳින සංචාරකයින්ටද අවශ්‍ය සියලු ආහාර සහ ගෘහ උපකරණ සාධාරණ මිලට සපයයි.</p>',
            ]);

            ServicePlaceTranslation::create([
                'service_place_id' => $s1->id,
                'locale' => 'en',
                'name' => 'Laggala Co-op Supermarket & Central Stores',
                'slug' => 'laggala-coop-supermarket-en',
                'location_name' => 'Main Street, Pallegama, Laggala',
                'operating_hours' => 'Monday - Sunday: 7:30 AM - 8:30 PM',
                'key_facilities' => "Fresh Produce & Vegetables\nWholesale Provisions & Rice\nCamping supplies & essentials\nCard Payments accepted (POS)",
                'short_description' => 'Central grocery and supermarket in Laggala New Town offering camping rations and daily supplies.',
                'description' => '<p>Conveniently located in Pallegama town center, providing a complete range of food products, bottled water, and provisions for mountain hikers.</p>',
            ]);

            // 3. BANKS & ATMS: Bank of Ceylon (BOC) Laggala Branch & 24/7 ATM
            $b1 = ServicePlace::create([
                'section' => 'banks-atms',
                'sub_category' => 'bank_branch',
                'status' => 'published',
                'featured' => true,
                'is_24_hours' => false,
                'phone' => '066 227 5250',
                'website' => 'https://boc.lk',
                'latitude' => 7.5258000,
                'longitude' => 80.7425000,
                'sort_order' => 1,
            ]);

            ServicePlaceTranslation::create([
                'service_place_id' => $b1->id,
                'locale' => 'si',
                'name' => 'ලංකා බැංකුව (BOC) ලග්ගල ශාඛාව සහ 24/7 ATM',
                'slug' => 'boc-laggala-branch-atm',
                'location_name' => 'බැංකු සංකීර්ණය, නව නගරය, පල්ලේගම, ලග්ගල',
                'operating_hours' => 'බැංකු වේලාවන්: පෙ.ව. 8:30 - ප.ව. 3:00 (ATM පැය 24 පුරා)',
                'key_facilities' => "පැය 24 පුරා ස්වයංක්‍රීය ටෙලර් යන්ත්‍ර (ATM)\nමුදල් තැන්පත් කිරීමේ යන්ත්‍ර (CDM)\nවිදේශ විනිමය හුවමාරුව\nක්ෂණික ණය සහ ඉතුරුම් පහසුකම්",
                'short_description' => 'ලග්ගල පල්ලේගම නගරයේ පිහිටි ලංකා බැංකු ශාඛාව සහ පැය 24 පුරා ක්‍රියාත්මක ATM සේවාව.',
                'description' => '<p>දේශීය සහ විදේශීය කාඩ්පත් සඳහා මුදල් ලබාගත හැකි 24/7 ATM පහසුකම සහ සියලු වාණිජ බැංකු සේවාවන් සපයන ප්‍රධාන බැංකු ශාඛාවයි.</p>',
            ]);

            ServicePlaceTranslation::create([
                'service_place_id' => $b1->id,
                'locale' => 'en',
                'name' => 'Bank of Ceylon (BOC) Laggala Branch & 24/7 ATM',
                'slug' => 'boc-laggala-branch-atm-en',
                'location_name' => 'Banking Complex, New Town, Pallegama, Laggala',
                'operating_hours' => 'Branch: 8:30 AM - 3:00 PM (ATM: 24/7)',
                'key_facilities' => "24-Hour ATM Cash Dispenser\nCash Deposit Machine (CDM)\nForeign Currency Exchange\nSavings & Remittance Services",
                'short_description' => 'Official BOC branch with 24-hour ATM kiosk accepting Visa, Mastercard, and LankaPay cards.',
                'description' => '<p>Reliable cash withdrawal point for tourists traveling between Riverston, Pallegama, and Knuckles trails.</p>',
            ]);

            // 4. FUEL & EV CHARGING: Ceypetco Filling Station Laggala
            $f1 = ServicePlace::create([
                'section' => 'fuel-ev',
                'sub_category' => 'filling_station',
                'status' => 'published',
                'featured' => true,
                'is_24_hours' => true,
                'phone' => '066 227 5410',
                'latitude' => 7.5285000,
                'longitude' => 80.7448000,
                'sort_order' => 1,
            ]);

            ServicePlaceTranslation::create([
                'service_place_id' => $f1->id,
                'locale' => 'si',
                'name' => 'සිපෙට්කෝ (Ceypetco) ඉන්ධන පිරවුම්හල - ලග්ගල',
                'slug' => 'ceypetco-laggala-fuel-station',
                'location_name' => 'ප්‍රධාන පාර, පල්ලේගම, ලග්ගල',
                'operating_hours' => 'පැය 24 පුරා විවෘතයි (24/7 Service)',
                'key_facilities' => "Petrol 92 Octane & 95 Octane\nAuto Diesel & Super Diesel\nවායු පිරවීම (Tyre Air Station)\nරථවාහන ලිහිසි තෙල් සහ කූලන්ට්",
                'short_description' => 'ලග්ගල නගරයේ ප්‍රධාන රජයේ ඉන්ධන පිරවුම්හල වන අතර පැය 24 පුරා පෙට්‍රල් සහ ඩීසල් සපයයි.',
                'description' => '<p>නකල්ස් කඳුකර මාර්ගයේ ගමන් ගන්නා වාහන සඳහා අවසන් ප්‍රධාන ඉන්ධන මධ්‍යස්ථානයක් වන අතර 24 පැයේම විශ්වාසනීය ඉන්ධන සැපයුමක් පවතී.</p>',
            ]);

            ServicePlaceTranslation::create([
                'service_place_id' => $f1->id,
                'locale' => 'en',
                'name' => 'Ceypetco Filling Station Laggala',
                'slug' => 'ceypetco-laggala-fuel-station-en',
                'location_name' => 'Main Road, Pallegama, Laggala',
                'operating_hours' => '24 Hours Daily (24/7)',
                'key_facilities' => "Petrol (92 & 95 Octane)\nAuto Diesel & Super Diesel\nFree Tire Pressure Station\nLubricants & Engine Coolants",
                'short_description' => 'Official government Ceypetco station offering 24/7 fuel, diesel, and air top-up services.',
                'description' => '<p>The strategic refueling point for all travelers driving across Pallegama, Ilukkumbura, and Knuckles mountain roads.</p>',
            ]);

            // 5. EDUCATION: Laggala-Pallegama Maha Vidyalaya
            $e1 = ServicePlace::create([
                'section' => 'education',
                'sub_category' => 'school',
                'status' => 'published',
                'featured' => true,
                'is_24_hours' => false,
                'phone' => '066 227 5280',
                'email' => 'pallegama.mv@gmail.com',
                'latitude' => 7.5234000,
                'longitude' => 80.7412000,
                'sort_order' => 1,
            ]);

            ServicePlaceTranslation::create([
                'service_place_id' => $e1->id,
                'locale' => 'si',
                'name' => 'ලග්ගල - පල්ලේගම මහා විද්‍යාලය',
                'slug' => 'laggala-pallegama-maha-vidyalaya',
                'location_name' => 'විද්‍යාල මාවත, නව නගරය, පල්ලේගම, ලග්ගල',
                'operating_hours' => 'පාසල් වේලාවන්: පෙ.ව. 7:30 - ප.ව. 1:30',
                'key_facilities' => "1 වසරේ සිට 13 වසර දක්වා පන්ති\nවිද්‍යා, කලා, වාණිජ සහ තාක්ෂණවේදය (A/L)\nපරිගණක රසායනාගාරය (Smart Classroom)\nපුස්තකාලය සහ ක්‍රීඩා පිටිය",
                'short_description' => 'ලග්ගල කලාපයේ ප්‍රමුඛතම ද්විතීයික පාසල වන අතර උසස් පෙළ දක්වා අධ්‍යාපනය ලබාදෙයි.',
                'description' => '<p>ලග්ගල නව නගරයේ නවීන ගොඩනැගිලි සංකීර්ණයක ස්ථාපිත කර ඇති මෙම විද්‍යාලය ප්‍රදේශයේ දරුවන්ගේ අධ්‍යාපනික හා ක්‍රීඩා කුසලතා සංවර්ධනයට පුරෝගාමී වේ.</p>',
            ]);

            ServicePlaceTranslation::create([
                'service_place_id' => $e1->id,
                'locale' => 'en',
                'name' => 'Laggala-Pallegama Maha Vidyalaya',
                'slug' => 'laggala-pallegama-maha-vidyalaya-en',
                'location_name' => 'College Avenue, New Town, Pallegama, Laggala',
                'operating_hours' => 'School Hours: 7:30 AM - 1:30 PM',
                'key_facilities' => "Grades 1 through 13\nAdvanced Level (Science, Arts, Commerce, Tech)\nComputer Lab & Smart Classrooms\nLibrary & Athletic Ground",
                'short_description' => 'The premier secondary school in Laggala educational division with modern facilities.',
                'description' => '<p>Re-established in the newly planned Laggala town with dedicated laboratory and sporting infrastructure.</p>',
            ]);

        });
    }
}
