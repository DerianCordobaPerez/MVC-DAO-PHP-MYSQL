<?php
    use JetBrains\PhpStorm\NoReturn;
    /**
     * Redireccion al inicio
     * @param null
     * @return void
     */
    #[NoReturn] function redirect(): void {
        header("Location: index.php");
        exit;
    }
