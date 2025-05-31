</main>
<!-- Scripts principais -->
<script src="../../libs/jquery/script.js"></script>
<script src="../../libs/bootstrap/script.js"></script>
<script src="../../libs/select2/script.js"></script>
<script src="../../libs/noty/script.js"></script>
<script src="../../libs/moment/script.js"></script>
<script src="../../libs/axios/script.js"></script>
<!-- Utils JS -->
<script src="../../js/utils/Noty.js"></script>
<script src="../../js/utils/Select2.js"></script>
<script src="../../js/utils/Utilitarios.js"></script>
<script src="../../js/utils/Validador.js"></script>
<!-- Custom Scripts -->
<script src="../../js/mask.js"></script>
<script src="../../js/sidebar.js"></script>
<!-- Script específico da página -->
<?php
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
$script_path = "../../js/pages/{$current_dir}.js";
if (file_exists($script_path)) {
    echo "<script src=\"{$script_path}\"></script>";
}
?>
</body>

</html>
