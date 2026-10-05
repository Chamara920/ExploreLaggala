<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\InstitutionCustomSection;
use App\Models\InstitutionCustomSectionTranslation;
use App\Models\InstitutionReview;
use App\Models\InstitutionService;
use App\Models\InstitutionServiceTranslation;
use App\Models\InstitutionTranslation;
use App\Models\InstitutionUnit;
use App\Models\InstitutionUnitTranslation;
use App\Models\Officer;
use App\Models\OfficerTranslation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GovernmentInstitutionSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // Ensure we have an admin/user for reviews
            $user = User::first() ?? User::factory()->create([
                'name' => 'Citizen Reviewer',
                'email' => 'citizen@laggala.lk',
            ]);

            // ========================================================
            // 1. LAGGALA DIVISIONAL SECRETARIAT (ප්‍රාදේශීය ලේකම් කාර්යාලය)
            // ========================================================
            $ds = Institution::create([
                'type' => 'divisional_secretariat',
                'status' => 'published',
                'featured' => true,
                'image_path' => null,
                'phone' => '066 227 5200',
                'email' => 'info@laggala.ds.gov.lk',
                'website' => 'https://laggala.ds.gov.lk',
                'fax' => '066 227 5201',
                'latitude' => 7.5256000,
                'longitude' => 80.7423000,
                'sort_order' => 1,
            ]);

            // Translations
            InstitutionTranslation::create([
                'institution_id' => $ds->id,
                'locale' => 'si',
                'name' => 'ලග්ගල - පල්ලේගම ප්‍රාදේශීය ලේකම් කාර්යාලය',
                'slug' => 'laggala-pallegama-divisional-secretariat',
                'location_name' => 'නව නගරය, පල්ලේගම, ලග්ගල',
                'office_hours' => 'සඳුදා - සිකුරාදා: පෙ.ව. 8:30 - ප.ව. 4:15 (මහජන දිනය: බදාදා)',
                'short_description' => 'ලග්ගල ප්‍රාදේශීය ලේකම් කොට්ඨාසයේ ජනතාව වෙත රාජ්‍ය ප්‍රතිපත්ති ක්‍රියාත්මක කිරීම සහ කඩිනම් මහජන සේවාවන් සැපයීම.',
                'description' => '<p>ලග්ගල ප්‍රාදේශීය ලේකම් කොට්ඨාසය මාතලේ දිස්ත්‍රික්කයේ පිහිටි අතිශය සුන්දර හා සංස්කෘතික වටිනාකමකින් පිරි ප්‍රදේශයකි. නව ලග්ගල හරිත නගරය කේන්ද්‍ර කරගනිමින් කාර්යක්ෂම, විනිවිදභාවයෙන් යුතු සහ මහජන හිතෛෂී රාජ්‍ය සේවාවක් සැලසීම අපගේ මූලික අරමුණයි.</p>',
            ]);

            InstitutionTranslation::create([
                'institution_id' => $ds->id,
                'locale' => 'en',
                'name' => 'Divisional Secretariat Laggala-Pallegama',
                'slug' => 'laggala-pallegama-divisional-secretariat-en',
                'location_name' => 'New Town, Pallegama, Laggala',
                'office_hours' => 'Monday - Friday: 8:30 AM - 4:15 PM (Public Day: Wednesday)',
                'short_description' => 'Delivering efficient public administration, social welfare, citizen services, and regional development across the Laggala division.',
                'description' => '<p>The Divisional Secretariat of Laggala-Pallegama oversees public administration and policy execution in the Knuckles mountain range buffer zone. We are committed to citizen-centric, transparent, and prompt public service delivery in the newly developed Green Town of Laggala.</p>',
            ]);

            InstitutionTranslation::create([
                'institution_id' => $ds->id,
                'locale' => 'ta',
                'name' => 'இலக்கல - பல்லேகம பிரதேச செயலகம்',
                'slug' => 'laggala-pallegama-divisional-secretariat-ta',
                'location_name' => 'புதிய நகரம், பல்லேகம, இலக்கல',
                'office_hours' => 'திங்கள் - வெள்ளி: மு.ப 8:30 - பி.ப 4:15 (பொதுமக்கள் தினம்: புதன்)',
                'short_description' => 'இலக்கல பிரதேசத்தில் பொது நிர்வாகம் மற்றும் மக்கள் நல சேவைகளை வழங்குதல்.',
                'description' => '<p>இலக்கல பல்லேகம பிரதேச செயலகம் திறமையான பொதுச் சேவைகளையும் சமூக நலத் திட்டங்களையும் வழங்கி வருகின்றது.</p>',
            ]);

            // Units / Departments
            $unitAdmin = InstitutionUnit::create([
                'institution_id' => $ds->id,
                'slug' => 'administration',
                'sort_order' => 1,
            ]);
            InstitutionUnitTranslation::create([
                'institution_unit_id' => $unitAdmin->id,
                'locale' => 'si',
                'name' => 'පරිපාලන අංශය',
                'description' => 'ආයතනික පාලනය, මානව සම්පත් කළමනාකරණය සහ සාමාන්‍ය මහජන සම්බන්ධීකරණය.',
            ]);
            InstitutionUnitTranslation::create([
                'institution_unit_id' => $unitAdmin->id,
                'locale' => 'en',
                'name' => 'Administration Division',
                'description' => 'General administration, institutional governance, human resources, and public coordination.',
            ]);

            $unitLand = InstitutionUnit::create([
                'institution_id' => $ds->id,
                'slug' => 'land',
                'sort_order' => 2,
            ]);
            InstitutionUnitTranslation::create([
                'institution_unit_id' => $unitLand->id,
                'locale' => 'si',
                'name' => 'ඉඩම් අංශය',
                'description' => 'රජයේ ඉඩම් බදුදීම්, බලපත්‍ර ලබාදීම්, ඔප්පු නිකුත් කිරීම සහ ඉඩම් ආරවුල් නිරාකරණය.',
            ]);
            InstitutionUnitTranslation::create([
                'institution_unit_id' => $unitLand->id,
                'locale' => 'en',
                'name' => 'Land Branch',
                'description' => 'State land alienation, permits, land titles, leases, and dispute settlement.',
            ]);

            $unitSocial = InstitutionUnit::create([
                'institution_id' => $ds->id,
                'slug' => 'social-services-samurdhi',
                'sort_order' => 3,
            ]);
            InstitutionUnitTranslation::create([
                'institution_unit_id' => $unitSocial->id,
                'locale' => 'si',
                'name' => 'සමාජ සේවා සහ අස්වැසුම / සමෘද්ධි ඒකකය',
                'description' => 'වැඩිහිටි, ආබාධිත සහ අඩු ආදායම්ලාභී පවුල් සඳහා සමාජ සුබසාධන ප්‍රතිලාභ කළමනාකරණය.',
            ]);
            InstitutionUnitTranslation::create([
                'institution_unit_id' => $unitSocial->id,
                'locale' => 'en',
                'name' => 'Social Welfare & Aswesuma / Samurdhi',
                'description' => 'Social security benefits, elderly assistance, disability support, and welfare empowerment.',
            ]);

            $unitField = InstitutionUnit::create([
                'institution_id' => $ds->id,
                'slug' => 'field-officers',
                'sort_order' => 4,
            ]);
            InstitutionUnitTranslation::create([
                'institution_unit_id' => $unitField->id,
                'locale' => 'si',
                'name' => 'ක්ෂේත්‍ර නිලධාරී සේවාවන් (ග්‍රාම නිලධාරී)',
                'description' => 'වසම් 25 ක් පුරා විසිරී සිටින ග්‍රාම නිලධාරී සහ සංවර්ධන නිලධාරී සේවා ජාලය.',
            ]);
            InstitutionUnitTranslation::create([
                'institution_unit_id' => $unitField->id,
                'locale' => 'en',
                'name' => 'Field Officer Network (Grama Niladhari)',
                'description' => 'Field administration covering 25 Grama Niladhari divisions and community officers.',
            ]);

            // Services
            $srv1 = InstitutionService::create([
                'institution_id' => $ds->id,
                'unit_id' => $unitAdmin->id,
                'fee' => 'නොමිලේ (Free)',
                'processing_time' => 'පැය 2 ක් ඇතුළත (Within 2 Hours)',
                'sort_order' => 1,
            ]);
            InstitutionServiceTranslation::create([
                'institution_service_id' => $srv1->id,
                'locale' => 'si',
                'title' => 'ජාතික හැඳුනුම්පත් (NIC) සඳහා අයදුම්පත් සහතික කිරීම',
                'requirements' => "1. උප්පැන්න සහතිකයේ මුල් පිටපත\n2. ICAO ප්‍රමිතියෙන් යුත් ඡායාරූප කුවිතාන්සිය\n3. ග්‍රාම නිලධාරී විසින් සහතික කළ අයදුම්පත",
                'description' => 'පළමු වරට හෝ නැතිවූ ජාතික හැඳුනුම්පත් සඳහා අයදුම්පත් පරීක්ෂා කර පුද්ගලයින් ලියාපදිංචි කිරීමේ දෙපාර්තමේන්තුවට යොමු කිරීම.',
            ]);
            InstitutionServiceTranslation::create([
                'institution_service_id' => $srv1->id,
                'locale' => 'en',
                'title' => 'National Identity Card (NIC) Application Processing',
                'requirements' => "1. Original Birth Certificate\n2. ICAO complaint photo receipt\n3. Completed form verified by Grama Niladhari",
                'description' => 'Verification and transmission of identity card applications to the Department for Registration of Persons.',
            ]);

            $srv2 = InstitutionService::create([
                'institution_id' => $ds->id,
                'unit_id' => $unitLand->id,
                'fee' => 'මුද්දර ගාස්තු පමණි (Stamp duty only)',
                'processing_time' => 'දින 14 ක් ඇතුළත (14 Working Days)',
                'sort_order' => 2,
            ]);
            InstitutionServiceTranslation::create([
                'institution_service_id' => $srv2->id,
                'locale' => 'si',
                'title' => 'ඉඩම් බලපත්‍ර සහ දීමනා පත්‍ර නිකුත් කිරීම',
                'requirements' => "1. ඉඩම් කච්චේරි තේරීම් ලිපිය\n2. මිනින්දෝරු පිඹුර\n3. පදිංචිය තහවුරු කිරීමේ ලියකියවිලි",
                'description' => 'රජයේ ඉඩම් ආඥාපනත යටතේ ජනතාවට ඉඩම් හිමිකම් ලබාදීම.',
            ]);
            InstitutionServiceTranslation::create([
                'institution_service_id' => $srv2->id,
                'locale' => 'en',
                'title' => 'State Land Alienation & Grant Permits',
                'requirements' => "1. Land Kachcheri selection letter\n2. Survey plan\n3. Proof of residency",
                'description' => 'Issuance of statutory land permits and grants under the Land Development Ordinance.',
            ]);

            // Officers
            $off1 = Officer::create([
                'institution_id' => $ds->id,
                'unit_id' => $unitAdmin->id,
                'phone' => '066 227 5202',
                'email' => 'ds@laggala.ds.gov.lk',
                'extension' => '101',
                'working_hours' => 'බදාදා: පෙ.ව. 9:00 - ප.ව. 3:00 (Public Day)',
                'sort_order' => 1,
            ]);
            OfficerTranslation::create([
                'officer_id' => $off1->id,
                'locale' => 'si',
                'name' => 'කේ. එම්. බණ්ඩාරනායක මයා',
                'designation' => 'ප්‍රාදේශීය ලේකම්',
                'responsibilities' => 'සමස්ත කොට්ඨාස පරිපාලනය, සංවර්ධන කටයුතු සම්බන්ධීකරණය සහ මුදල් පාලනය.',
            ]);
            OfficerTranslation::create([
                'officer_id' => $off1->id,
                'locale' => 'en',
                'name' => 'Mr. K. M. Bandaranayake',
                'designation' => 'Divisional Secretary',
                'responsibilities' => 'Overall divisional administration, judicial functions, and development project coordination.',
            ]);

            $off2 = Officer::create([
                'institution_id' => $ds->id,
                'unit_id' => $unitField->id,
                'phone' => '071 884 1234',
                'email' => 'gn.pallegama@laggala.ds.gov.lk',
                'extension' => '205',
                'working_hours' => 'අඟහරුවාදා සහ බ්‍රහස්පතින්දා (කාර්යාල දිනයන්)',
                'sort_order' => 2,
            ]);
            OfficerTranslation::create([
                'officer_id' => $off2->id,
                'locale' => 'si',
                'name' => 'එච්. ජී. සිරිවර්ධන මයා',
                'designation' => 'ප්‍රධාන ග්‍රාම නිලධාරී (පල්ලේගම වසම)',
                'responsibilities' => 'පල්ලේගම වසමේ මහජන සහතික නිකුත් කිරීම, ඡන්ද නාමලේඛන සහ සුබසාධන පරීක්ෂණ.',
            ]);
            OfficerTranslation::create([
                'officer_id' => $off2->id,
                'locale' => 'en',
                'name' => 'Mr. H. G. Siriwardena',
                'designation' => 'Chief Grama Niladhari (Pallegama Division)',
                'responsibilities' => 'Issuance of residency certificates, electoral registers, and field assessments.',
            ]);

            // Custom Content Block: Citizen Charter Table
            $charter = InstitutionCustomSection::create([
                'institution_id' => $ds->id,
                'type' => 'table',
                'data' => [
                    'headers' => ['සේවාව (Service)', 'අදාළ අංශය (Unit)', 'ගතවන කාලය (Time)', 'ගාස්තුව (Fee)'],
                    'rows' => [
                        ['උප්පැන්න / විවාහ පිටපත්', 'ලේඛකාධිකාරී අංශය', 'එදිනම (Same Day)', 'රු. 100/-'],
                        ['ආදායම් සහතික නිකුත් කිරීම', 'ග්‍රාම නිලධාරී අංශය', 'දින 02 ක්', 'නොමිලේ'],
                        ['ව්‍යාපාර ලියාපදිංචිය', 'පරිපාලන අංශය', 'දින 03 ක්', 'රු. 500/-'],
                        ['ගස් කැපීමේ බලපත්‍ර', 'පරිසර හා පරිපාලන', 'දින 05 ක්', 'මුද්දර ගාස්තු'],
                    ],
                ],
                'sort_order' => 1,
            ]);
            InstitutionCustomSectionTranslation::create([
                'institution_custom_section_id' => $charter->id,
                'locale' => 'si',
                'title' => 'ප්‍රධාන මහජන සේවා ප්‍රඥප්තිය (Citizen Charter)',
                'content' => '<p>මහජනතාවට කඩිනම් සේවාවක් සැපයීම සඳහා වූ සේවා ප්‍රඥප්තිය සහ සම්මත කාල සීමාවන්.</p>',
            ]);
            InstitutionCustomSectionTranslation::create([
                'institution_custom_section_id' => $charter->id,
                'locale' => 'en',
                'title' => 'Public Citizen Charter',
                'content' => '<p>Standard processing guidelines, timeline, and fee schedule under the Citizen Charter.</p>',
            ]);

            // Review
            InstitutionReview::create([
                'institution_id' => $ds->id,
                'user_id' => $user->id,
                'rating' => 5,
                'comment' => 'නව නගරයේ කාර්යාලය ඉතා ඉඩකඩ සහිතයි. නිලධාරීන් ඉතා සුහදශීලීව සේවය සපයනවා.',
                'status' => 'approved',
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
            ]);

            // ========================================================
            // 2. LAGGALA-PALLEGAMA PRADESHIYA SABHA (ප්‍රාදේශීය සභාව)
            // ========================================================
            $ps = Institution::create([
                'type' => 'local_authority',
                'status' => 'published',
                'featured' => false,
                'image_path' => null,
                'phone' => '066 227 5310',
                'email' => 'pslaggala@gmail.com',
                'website' => null,
                'fax' => null,
                'latitude' => 7.5271000,
                'longitude' => 80.7445000,
                'sort_order' => 2,
            ]);

            InstitutionTranslation::create([
                'institution_id' => $ps->id,
                'locale' => 'si',
                'name' => 'ලග්ගල - පල්ලේගම ප්‍රාදේශීය සභාව',
                'slug' => 'laggala-pallegama-pradeshiya-sabha',
                'location_name' => 'ලග්ගල - පල්ලේගම',
                'office_hours' => 'සඳුදා - සිකුරාදා: පෙ.ව. 8:30 - ප.ව. 4:15',
                'short_description' => 'ප්‍රදේශයේ මාර්ග, සෞඛ්‍ය, කසළ කළමනාකරණය, වීදි ලාම්පු සහ ප්‍රජා පහසුකම් සලසන පළාත් පාලන ආයතනය.',
                'description' => '<p>ලග්ගල පල්ලේගම ප්‍රාදේශීය සභාව පළාත් පාලන ආයතනයක් ලෙස ලග්ගල කොට්ඨාසයේ පොදු සනීපාරක්ෂාව, මහජන වෙළඳපොළ, මාර්ග නඩත්තුව සහ ගොඩනැගිලි සැලසුම් අනුමත කිරීම සිදුකරයි.</p>',
            ]);

            InstitutionTranslation::create([
                'institution_id' => $ps->id,
                'locale' => 'en',
                'name' => 'Laggala-Pallegama Pradeshiya Sabha',
                'slug' => 'laggala-pallegama-pradeshiya-sabha-en',
                'location_name' => 'Laggala - Pallegama',
                'office_hours' => 'Monday - Friday: 8:30 AM - 4:15 PM',
                'short_description' => 'Local Government Authority responsible for public health, municipal sanitation, rural roads, and local civic amenities.',
                'description' => '<p>Laggala-Pallegama Pradeshiya Sabha is the statutory local authority in charge of road maintenance, public market infrastructure, waste management, and building approvals.</p>',
            ]);

            // ========================================================
            // 3. MAHAWELI AUTHORITY LAGGALA RESIDENTIAL PROJECT OFFICE
            // ========================================================
            $mahaweli = Institution::create([
                'type' => 'mahaweli_institution',
                'status' => 'published',
                'featured' => false,
                'image_path' => null,
                'phone' => '066 227 5440',
                'email' => 'mahaweli.laggala@mahaweli.gov.lk',
                'website' => 'https://mahaweli.gov.lk',
                'fax' => null,
                'latitude' => 7.5302000,
                'longitude' => 80.7410000,
                'sort_order' => 3,
            ]);

            InstitutionTranslation::create([
                'institution_id' => $mahaweli->id,
                'locale' => 'si',
                'name' => 'ශ්‍රී ලංකා මහවැලි අධිකාරිය - ලග්ගල නේවාසික ව්‍යාපෘති කාර්යාලය',
                'slug' => 'mahaweli-authority-laggala-project',
                'location_name' => 'කළුවඟඟ ජලාශ ව්‍යාපෘති පරිශ්‍රය, ලග්ගල',
                'office_hours' => 'සඳුදා - සිකුරාදා: පෙ.ව. 8:30 - ප.ව. 4:15',
                'short_description' => 'කළුවඟඟ සහ මොරගහකන්ද ව්‍යාපෘතිය මඟින් ප්‍රතිස්ථාපනය වූ ජනතාවගේ යටිතල පහසුකම්, වාරිමාර්ග සහ කෘෂිකාර්මික සංවර්ධනය.',
                'description' => '<p>මොරගහකන්ද - කළුගඟ මහා පරිමාණ සංවර්ධන ව්‍යාපෘතිය යටතේ නිර්මාණය කරන ලද නව ලග්ගල නගරය සහ ප්‍රතිස්ථාපිත පවුල් සඳහා වාරි ජලය, කෘෂි සහන සහ යටිතල පහසුකම් කළමනාකරණය කරනු ලබයි.</p>',
            ]);

            InstitutionTranslation::create([
                'institution_id' => $mahaweli->id,
                'locale' => 'en',
                'name' => 'Sri Lanka Mahaweli Authority - Laggala Resident Project Office',
                'slug' => 'mahaweli-authority-laggala-project-en',
                'location_name' => 'Kalu Ganga Reservoir Project Complex, Laggala',
                'office_hours' => 'Monday - Friday: 8:30 AM - 4:15 PM',
                'short_description' => 'Agricultural development, irrigation distribution, and community rehabilitation under the Moragahakanda-Kaluganga project.',
                'description' => '<p>Responsible for township management, canal water management, agricultural guidance, and rehabilitation of families relocated during the Kaluganga reservoir construction.</p>',
            ]);

        });
    }
}
