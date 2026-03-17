<?php

return [
    // Ana sayfa
    'home' => ['path' => 'home.php', 'login_required' => true],

    // Hasta Modülü
    'hasta_listesi' => ['path' => 'hasta_modul/hasta_liste.php', 'login_required' => true],
    'hasta_ekle' => ['path' => 'hasta_modul/hasta_ekle.php', 'login_required' => true],
    'hasta_duzenle' => ['path' => 'hasta_modul/hasta_duzenle.php', 'login_required' => true, 'params' => ['id']],
    'hasta_goruntule' => ['path' => 'hasta_modul/hasta_goruntule.php', 'login_required' => true, 'params' => ['id']],
    'hasta_sil' => ['path' => 'hasta_modul/hasta_sil.php', 'login_required' => true, 'params' => ['id']],

    'hasta_listesi_excel' => ['path' => 'hasta_modul/hasta_liste_excel.php','login_required' => true],
    
    // Cihazlar
    'cihaz_liste' => ['path' => 'cihazlar/cihaz_liste.php', 'login_required' => true],
    'cihaz_crud' => ['path' => 'cihazlar/cihaz_crud.php', 'login_required' => true],

    // Hastalıklar
    'hastalik_liste' => ['path' => 'hastaliklar/hastalik_liste.php', 'login_required' => true],
    'hastalik_crud' => ['path' => 'hastaliklar/hastalik_crud.php', 'login_required' => true],
    

    // Kullanıcı Modülü
    //'kullanici_ekle' => ['path' => 'auth/register.php', 'login_required' => true],
    'kullanici_listesi' => ['path' => 'users/kullanici_listesi.php', 'login_required' => true],
    'kullanici_ekle' => ['path' => 'users/user_actions.php', 'login_required' => true],
    'sifre_degistir' => ['path' => 'users/user_actions.php', 'login_required' => true],
    'kullanici_sil' => ['path' => 'users/user_actions.php', 'login_required' => true, 'params' => ['user_id']],

    // Raporlar
    'hasta_ziyaret_rapor' => ['path' => 'hasta_ziyaret/ziyaret_rapor.php', 'login_required' => true],
    'istatistikler' => ['path' => 'raporlar/istatistikler.php', 'login_required' => true],
    'istatistik_pdf' => ['path' => 'raporlar/istatistik_pdf.php', 'login_required' => true],

    // Auth
    'login' => ['path' => 'auth/login.php', 'login_required' => false],
    'logout' => ['path' => 'auth/logout.php', 'login_required' => false],
    'login_check' => ['path' => 'auth/login_check.php', 'login_required' => false],

    //Diger







    // DB
    'db_yedekle' => ['path' => 'db_yedekle.php', 'login_required' => true],
];
