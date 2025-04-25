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
        <!-- Custom Scripts -->
        <script src="../../js/mask.js"></script>
        <script src="../../js/sidebar.js"></script>

        <?php
        // Inclui o script específico da página se existir
        $current_page = basename($_SERVER['PHP_SELF'], '.php');
        $script_path = "../../js/pages/{$current_page}.js";
        if (file_exists($script_path)) {
            echo "<script src=\"{$script_path}\"></script>";
        }
        ?>

        </body>

        </html>