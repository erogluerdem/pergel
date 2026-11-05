İSG Modülü - Hızlı Başlangıç

- Yeni tablolar: `incident_reports`, `permits`, `toolbox_talks`.
- Rotalar: `field/qhse/safety` ana pano; incident/permits/toolbox için create/store ve index rotaları.
- Fotoğrafları depolamak için storage klasörüne simlink oluşturun:

    php artisan storage:link

- Migration'ları çalıştırmak için:

    php artisan migrate

- Mobil kullanım notları:
  - Olay bildirim formu fotoğraf çekmeyi zorunlu kılar.
  - GPS koordinatları tarayıcıdan otomatik doldurulur; mobil tarayıcı izin isteyecektir.

Geliştirme için bir sonraki adım: imza onay akışı, DÖF görev entegrasyonu ve API uçları.
