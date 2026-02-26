<?php
// footer.php
?>

<footer class="bg-white border-top mt-5">
    <div class="container py-3">
        <div class="row align-items-center">

            <!-- Sol -->
            <div class="col-md-6 text-center text-md-start text-muted small">
                © <?= date('Y') ?> Diyarbakır Çocuk Hastalıkları Hastanesi
                • <?= __("all_rights_reserved") ?>
            </div>

            <!-- Sağ -->
            <div class="col-md-6 text-center text-md-end">
                <span class="text-muted small">
                    <?= __("logged_in_as") ?>:
                </span>
                <strong>
                    <?= htmlspecialchars($_SESSION['username'] ?? '') ?>
                </strong>
            </div>

        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>