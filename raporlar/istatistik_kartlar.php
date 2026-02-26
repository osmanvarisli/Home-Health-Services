


    <!-- Tablolar -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <h5>Ulaşılan Hasta Sayısı (Cinsiyet)</h5>
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr><th>Cinsiyet</th><th>Sayı</th></tr>
                </thead>
                <tbody>
                    <?php foreach($ulasilan as $u): ?>
                        <tr><td><?= htmlspecialchars($u['cinsiyet']) ?></td><td><?= $u['toplam'] ?></td></tr>
                    <?php endforeach; ?>
                    <tr class="table-primary"><td>Toplam</td><td><?= $ulasilan_toplam ?></td></tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6 mb-4">
            <h5>Toplam Hizmet Sayıları</h5>
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr><th>Hizmet Durumu</th><th>Sayı</th></tr>
                </thead>
                <tbody>
                    <?php foreach($hizmetler as $h): ?>
                        <tr><td><?= htmlspecialchars($h['hizmet_durum']) ?></td><td><?= $h['toplam'] ?></td></tr>
                    <?php endforeach; ?>
                    <tr class="table-primary"><td>Toplam</td><td><?= $hizmetler_toplam ?></td></tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6 mb-4">
            <h5>Hizmet Sonlandırma Nedenleri</h5>
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr><th>Neden</th><th>Sayı</th></tr>
                </thead>
                <tbody>
                    <?php foreach($sonNeden as $s): ?>
                        <tr><td><?= htmlspecialchars($s['sonuc_durum']) ?></td><td><?= $s['toplam'] ?></td></tr>
                    <?php endforeach; ?>
                    <tr class="table-primary"><td>Toplam</td><td><?= $sonNeden_toplam ?></td></tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-6 mb-4">
            <h5>Hastalık Grup (Aktif Hastalar)</h5>
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr><th>Grup</th><th>Hastalık</th><th>Sayı</th></tr>
                </thead>
                <tbody>
                    <?php foreach($hastaliklar as $h): ?>
                        <tr>
                            <td><?= htmlspecialchars($h['hastalik_grubu']) ?></td>
                            <td><?= htmlspecialchars($h['hastalik_adi']) ?></td>
                            <td><?= $h['toplam'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>