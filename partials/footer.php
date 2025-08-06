</div> </div> <footer class="footer">
        <div class="container-fluid d-flex justify-content-between">
            <div class="copyright">
                2024, made with <i class="fa fa-heart heart text-danger"></i> by <a href="http://www.themekita.com">ThemeKita</a>
            </div>
            <div>
                Distributed by <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
            </div>
        </div>
    </footer>
</div> </div> <script src="/assets/js/core/jquery-3.7.1.min.js"></script>
<script src="/assets/js/core/popper.min.js"></script>
<script src="/assets/js/core/bootstrap.min.js"></script>

<script src="/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

<script src="/assets/js/plugin/chart.js/chart.min.js"></script>

<script src="/assets/js/plugin/datatables/datatables.min.js"></script>

<script src="/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

<script src="/assets/js/kaiadmin.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Initialize DataTables on tables with ID 'add-row'
    $(document).ready(function () {
        $("#add-row").DataTable({
            pageLength: 5,
        });
    });
</script>

<?php if(isset($pageJS)) echo '<script>' . $pageJS . '</script>'; ?>
</body>
</html>