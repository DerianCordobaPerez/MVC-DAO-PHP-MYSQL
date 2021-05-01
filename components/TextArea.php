<?php
class TextArea {
    private function __construct() {}

    public static function text_area(string $name, string $class, string $content, string $rows = "4", string $cols = "50", string $id = ""): void {
        echo "<textarea name='$name' class='$class' rows='$rows' cols='$cols'";
        if($id !== "") echo " id='$id' ";
        echo "required>$content</textarea>";
    }
}