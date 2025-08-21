        </div> <!-- end .page-inner -->
    </div> <!-- end .container -->
</div> <!-- end .main-panel -->

<footer class="footer">
    <div class="container-fluid d-flex justify-content-between">
        <div class="copyright">
            &copy; <?= date('Y') ?> SIBUMDES
        </div>
        <div>
            Developed by <a target="_blank" href="#">Vuriko Studio</a>.
        </div>
    </div>
</footer>

</div> <!-- end .wrapper -->

<!-- Core JS -->
<script src="/assets/js/core/jquery-3.7.1.min.js"></script>
<script src="/assets/js/core/popper.min.js"></script>
<script src="/assets/js/core/bootstrap.min.js"></script>

<!-- Plugins -->
<script src="/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="/assets/js/plugin/chart.js/chart.min.js"></script>
<script src="/assets/js/plugin/datatables/datatables.min.js"></script>
<script src="/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

<!-- Kaiadmin -->
<script src="/assets/js/kaiadmin.min.js"></script>

<!-- Jika ada tambahan JS -->
<?php if (isset($pageJS)) : ?>
<script><?= $pageJS ?></script>
<?php endif; ?>

</body>
</html>
