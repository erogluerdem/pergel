<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContractTemplate;
use App\Models\ContractClause;
use Illuminate\Support\Str;

class ContractTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Örnek inşaat proje şablonları (Türkiye odaklı, temel maddeler)
        $templates = [
            [
                'title' => 'Genel Müteahhitlik Sözleşmesi (Kamu İşi)',
                'body' => "1. Taraflar ve Tanımlar\n1.1. İdare: [İdare unvanı, adres]\n1.2. Müteahhit: [Firma unvanı, adres]\n\n2. Sözleşme Konusu ve Kapsam\n2.1. Müteahhit, ihale dokümanında tanımlı işleri, teknik şartname ve keşif ile uyumlu şekilde gerçekleştirecektir.\n\n3. Sözleşme Bedeli ve Fiyatlandırma\n3.1. Sözleşme bedeli, sözleşme eklerinde belirtildiği gibidir. Fiyat değişiklikleri yalnızca yazılı ek sözleşme ile mümkündür.\n\n4. Ödeme ve Hakedişler\n4.1. Hakedişler aylık olarak düzenlenecek, hakediş ödemeleri için keşif raporu, fatura ve diğer belgeler sunulacaktır.\n\n5. Süre ve Program\n5.1. İşin başlangıç ve bitiş tarihleri iş programında belirtilmiştir. Süre uzatımı talepleri yazılı olarak yapılacaktır.\n\n6. Teminatlar ve Sigortalar\n6.1. Geçici ve kesin teminatlar banka teminat mektubu olarak alınacaktır. Müteahhit, projeye ilişkin gerekli sigortaları yaptıracaktır.\n\n7. Kabul ve Teslim\n7.1. Geçici kabul ve kesin kabul prosedürleri sözleşme eklerindeki hükümlere göre yapılacaktır.\n\n8. Fesih ve Cezai Şartlar\n8.1. Sözleşme şartlarının ihlali halinde taraflara fesih ve cezai şart uygulama hakkı tanınır.\n\n9. Mücbir Sebepler\n9.1. Deprem, sel, savaş, salgın gibi olaylar mücbir sebep sayılır; taraflar süre uzatımı talep edebilir.\n\n10. Uyuşmazlıkların Çözümü\n10.1. Taraflar uyuşmazlıkları öncelikle müzakere ile çözmeyi deneyip başarılamazsa yetkili mahkemelere başvuracaktır.",
                'created_by' => null,
                'status' => 'active'
            ],
            [
                'title' => 'Alt Yüklenici Hizmet Sözleşmesi',
                'body' => "1. Taraflar ve Tanımlar\n1.1. İşveren: [İşveren unvanı]\n1.2. Yüklenici (Ana Müteahhit): [Yüklenici]\n1.3. Alt Yüklenici: [Alt Yüklenici]\n\n2. İşin Kapsamı ve Teslimat\n2.1. Alt Yüklenici, Ek-1'de tanımlı iş kalemlerini teknik şartnameye uygun olarak gerçekleştirecektir.\n\n3. Süre ve İş Programı\n3.1. İşin başlama ve bitiş tarihleri iş programında yer alır. Gecikmeler için gecikme cezaları uygulanır.\n\n4. Kalite ve Standartlar\n4.1. Kullanılan malzemeler üretici belgelerine ve TS EN/ISO standartlarına uygun olmalıdır.\n\n5. İş Güvenliği\n5.1. Alt Yüklenici İSG mevzuatına uymak ve PPE temin etmekle yükümlüdür.\n\n6. Ödeme ve Hakediş\n6.1. Hakedişler aylık olarak düzenlenecek; belgeler sunulmadan ödeme yapılmayacaktır.\n\n7. Garanti\n7.1. Alt Yüklenici, yapılan iş için 24 ay garanti verir.\n\n8. Fesih ve Cezai Şartlar\n8.1. Yükümlülük ihlallerinde Ana Müteahhit fesih ve tazminat talep edebilir.",
                'created_by' => null,
                'status' => 'active'
            ],
            [
                'title' => 'Mimari Proje Tasarım Sözleşmesi',
                'body' => "1. Taraflar ve Tanımlar\n1.1. İşveren: [Unvan]\n1.2. Tasarımcı: [Mimar/Ofis]\n\n2. Hizmet Kapsamı\n2.1. Ön fizibilite, avan proje, uygulama projesi, detay projeler ve uygulama sürecinde danışmanlık hizmetleri sunulacaktır.\n\n3. Teslim Süreleri ve Revizyonlar\n3.1. Her aşama için teslim tarihleri ve revizyon limitleri sözleşmede belirtilecektir. Ek revizyonlar için ek ücret uygulanır.\n\n4. Telif Hakları\n4.1. Tasarımcı telif hakkına sahiptir; İşverene proje amaçlı kullanım lisansı verilir.\n\n5. Ücretlendirme\n5.1. Ücret sabit veya proje maliyetine bağlı yüzde bazlı olabilir; ödeme takvimi ile ödenecektir.\n\n6. Sorumluluk ve Garanti\n6.1. Tasarımcı mesleki özeni gösterecek; hatalar tespit edilirse düzeltme yapılacaktır.",
                'created_by' => null,
                'status' => 'active'
            ],
        ];

        foreach ($templates as $t) {
            ContractTemplate::updateOrCreate(
                ['title' => $t['title']],
                $t
            );
        }

        // Yaygın kullanılan madde/klausüller
        $clauses = [
            ['title' => 'Fesih Hakkı', 'content' => "Taraflardan herhangi biri, diğer tarafın sözleşme kapsamındaki yükümlülüklerini yerine getirmemesi hâlinde yazılı bildirimle 30 (otuz) gün süre vererek eksikliklerin giderilmesini talep eder. Belirtilen süre içinde eksiklik giderilmezse bildirimde bulunan taraf sözleşmeyi tek taraflı olarak feshedebilir. Fesih halinde tarafların hak ve yükümlülükleri, yapılmış işler ve varsa tazminat talepleri sözleşme hükümlerine göre hesaplanır. Fesih bildirimleri iadeli taahhütlü posta veya resmi/kurumsal e-posta ile yapılacaktır.", 'category' => 'Fesih', 'created_by' => null, 'status' => 'active'],
            ['title' => 'Garanti Süresi', 'content' => "Yüklenici, teslim edilen iş ve kullanılan malzemeler için teslim tarihinden itibaren 24 (yirmi dört) ay süreyle garanti verir. Garanti kapsamında ortaya çıkan kusurlar işveren tarafından yazılı olarak bildirildiğinde, yüklenici 15 (onbeş) iş günü içerisinde ücretsiz olarak onarım veya değişim yapacaktır. Onarım sonrasında garanti süresi onarım tarihinden itibaren yeniden başlayabilir veya taraflarca aksi kararlaştırılabilir.", 'category' => 'Garanti', 'created_by' => null, 'status' => 'active'],
            ['title' => 'İş Güvenliği ve Sahada Kurallar', 'content' => "Yüklenici ve alt yüklenicileri, 6331 sayılı İş Sağlığı ve Güvenliği Kanunu ve ilgili mevzuata uymakla yükümlüdür. Tüm personel için uygun kişisel koruyucu donanım (PPE) sağlanacak ve eğitim kayıtları tutulacaktır. Sahada meydana gelen iş kazaları derhal işverene ve yetkili makamlara bildirilecektir. Denetimde tespit edilen eksiklikler 7 (yedi) iş günü içinde giderilecektir.", 'category' => 'İSG', 'created_by' => null, 'status' => 'active'],
            ['title' => 'İşin Teslim Süresi', 'content' => "Taraflar işin tamamlanma tarihini [tarih veya süre] olarak kabul eder. Yüklenici, iş programına uygun şekilde çalışmakla yükümlüdür. Gecikme hâlinde her geciken gün için sözleşme bedelinin %0,05 oranında gecikme cezası uygulanacak olup, toplam cezai kesinti sözleşme bedelinin %10'unu aşamaz. Yükleniciden kaynaklanmayan gecikmeler (işveren sebepleri, mücbir sebepler vb.) halinde yüklenici süre uzatımı talep edebilir.", 'category' => 'Süre', 'created_by' => null, 'status' => 'active'],
            ['title' => 'Mücbir Sebepler', 'content' => "Deprem, sel, savaş, terör eylemi, pandemi/salgın ilanı, resmi yasaklar veya tarafların kontrolü dışında gerçekleşen diğer sebepler mücbir sebep sayılır. Mücbir sebep durumunda etkilenen taraf 7 (yedi) iş günü içinde diğer tarafı yazılı olarak bilgilendirir ve belgeleyici bilgileri sunar. Taraflar mücbir sebebin etkilerini değerlendirmek ve uygun süre uzatımını kararlaştırmak için işbirliği yapacaktır. Mücbir sebebin 120 günden fazla sürmesi durumunda taraflar sözleşmeyi feshetme veya yeniden müzakere etme hakkına sahiptir.", 'category' => 'Genel', 'created_by' => null, 'status' => 'active'],
            ['title' => 'Müteahhitin Sigorta Yükümlülüğü', 'content' => "Müteahhit, proje süresince aşağıdaki sigortaları yaptırıp yürürlükte tutacaktır: (a) Yapı All Risks (inşaat sigortası); (b) Üçüncü Şahıs Mali Sorumluluk Sigortası; (c) Personel/İş Kazası Sigortası. Poliçe limitleri sözleşmede belirtilen tutarları karşılayacak şekilde olacaktır. Poliçeler işverene ibraz edilecektir.", 'category' => 'Sigorta', 'created_by' => null, 'status' => 'active'],
            ['title' => 'Ödeme Planı ve Hakedişler', 'content' => "Sözleşme bedelinin %10'u avans olarak ödenebilir (bankaca teminat karşılığı). Kalan bedel hakedişlere göre aylık olarak veya iş programına göre ödenir. Hakediş talepleri için gerekli belgeler: keşif raporu, fatura, SGK dökümü ve kalite belgeleridir. Hakediş ödemesi onay tarihinden itibaren 30 gün içinde yapılacaktır. Hakedişlerden %5 kesinti yapılır ve kesin kabul sonrası iade edilir veya teminatla değiştirilir.", 'category' => 'Ödeme', 'created_by' => null, 'status' => 'active'],
        ];

        foreach ($clauses as $c) {
            ContractClause::updateOrCreate(['title' => $c['title']], $c);
        }
    }
}
