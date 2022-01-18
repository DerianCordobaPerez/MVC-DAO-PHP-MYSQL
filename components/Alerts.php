<?php
class Alerts {
    private function __construct() {}

    /**
     * @param string $type
     * @param string $content
     */
    public static function alert(string $type, string $content): void {
        echo "
            <div class='container my-4'>
                <div class='alert alert-$type' role='alert'>$content</div>
            </div>
        ";
    }
}