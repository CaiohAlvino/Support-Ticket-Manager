        </div>
        </main>

        <!-- Scripts -->
        <script src="../../libs/axios/script.js"></script>
        <!-- jQuery -->
        <script src="../../libs/jquery/script.js"></script>
        <!-- Bootstrap -->
        <script src="../../libs/bootstrap/script.js"></script>
        <!-- Select2 -->
        <script src="../../libs/select2/script.js"></script>
        <!-- Noty -->
        <script src="../../libs/noty/script.js"></script>
        <!-- Moment.js -->
        <script src="../../libs/moment/script.js"></script>
        <!-- Axios -->
        <script src="../../libs/axios/script.js"></script>

        <!-- Utils JS -->
        <script src="../../js/utils/Noty.js"></script>
        <script src="../../js/utils/Select2.js"></script>
        <script src="../../js/utils/Utilitarios.js"></script>
        <script src="../../js/utils/Validador.js"></script>

        <!-- Custom Scripts -->
        <script src="../../js/mask.js"></script>
        <script src="../../js/sidebar.js"></script>

        <!-- Script para puxar o nome do JS da página -->
        <?php
        // Inclui o script específico da pasta se existir
        $current_dir = basename(dirname($_SERVER['PHP_SELF']));
        $script_path = "../../js/pages/{$current_dir}.js";
        if (file_exists($script_path)) {
            echo "<script src=\"{$script_path}\"></script>";
        }
        ?>
        </body>

        </html>