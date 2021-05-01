<?php
class IndexView {

    public function __construct() {
        include_once 'components/Form.php';
        include_once 'components/Divs.php';
        include_once 'components/Input.php';
        include_once 'components/Label.php';
        include_once 'components/Title.php';
        include_once 'components/Span.php';
        include_once 'components/Button.php';
        include_once 'components/Html.php';
        include_once 'components/Image.php';
        include_once 'components/P.php';
        include_once 'components/A.php';
        include_once 'components/Header.php';
        include_once 'components/Accordion.php';
        include_once 'components/TextArea.php';
        include_once 'controllers/StudentController.php';
    }

    public function show_index(string $title): void {
        $buttons = array('estudiante', 'auto');
        Html::open_html($title);
        $this->show_header(array('Practica #04', 'MVC - Acceso a base de datos'));
        Divs::open_div('container');
        Accordion::open_accordion('Formulario de registro', 'headingOne', 'collapseOne');
        Form::open_form('no-file', 'POST', 'no-enctype');
        Divs::open_div('row');
        foreach($buttons as $button) {
            Divs::open_div('col-md-6');
            Input::input('btn btn-warning button-width', '', 'submit', $button, '', $button, false, $button);
            Divs::close_div();
        }
        Divs::close_div();
        Form::close_form();
        foreach ($buttons as $button) if(array_key_exists($button, $_POST)) self::show_content(null, $button);
        Accordion::close_accordion();

        Accordion::open_accordion('Listado de entidades', 'headingTwo', 'collapseTwo');
        $student_controller = new StudentController();
        var_dump($student_controller->get_all_students());
        Accordion::close_accordion();
        Divs::close_div();
        Html::close_html();
    }

    /**
     * @param Student|Car|null $model
     * @param String $text_button
     */
    public static function show_content(Student|Car|null $model, String $text_button) {
        Title::title_void('h2', '<br>Formulario de '.($model ? 'edicion' : 'agregado').' para '.($text_button !== 'estudiante' ? ' auto' : ' estudiante'));
        Title::title_void('h4', ($model ? 'Edite' : 'Rellene').' los campos necesarios<br>');

        if($model) $information = self::get_array_strings($model, true);
        $labels = self::set_labels($text_button);
        $types = self::set_types($text_button);
        $icons = self::set_icons($text_button);
        $values = self::set_values($text_button);

        Form::open_form('helpers/process.php', 'POST');
        for($i = 0; $i < count($labels); ++$i) {
            Label::label_void(strtolower($labels[$i]), $labels[$i]);
            if($types[$i] === 'file') Input::input_hidden('MAX_FILE_SIZE', '1024000');
            if($types[$i] === 'textarea') {
                TextArea::text_area(strtolower($labels[$i]), 'textarea form-control', $model ? $information[$i] : '');
                continue;
            }
            Input::input('form-control', $icons[$i], $types[$i], strtolower($labels[$i]), $labels[$i], $model ? $information[$i] : '',
                true, $model ? $labels[$i] : "", $values[$i]);
        }
        Divs::open_div('d-grid gap-2');
        Input::input('btn btn-primary my-2 button-width', '', 'submit', 'send', '', 'Enviar', false);
        Divs::close_div();
        Form::close_form();
    }

    private static function get_array(array $labels): array {
        $array = array();
        foreach($labels as $label)
            array_push($array, $label);
        return $array;
    }

    private static function set_labels(string $text): array {
        if(strtolower($text) === 'estudiante') $array = self::get_array(array('Email', 'Name', 'License', 'Age', 'Course', 'Photo'));
        else $array = self::get_array(array('License', 'Model', 'Brand', 'Description', 'Photo'));
        return $array;
    }

    private static function set_types(string $text): array {
        if(strtolower($text) === 'estudiante') $array = self::get_array(array('email', 'text', 'text', 'number', 'number', 'file'));
        else $array = self::get_array(array('text', 'text', 'text', 'textarea', 'file'));
        return $array;
    }

    private static function set_icons(string $text): array {
        if(strtolower($text) === 'estudiante') $array = self::get_array(array('@', "<i class='far fa-user'></i>", "<i class='far fa-id-card'></i>", "18", "<i class='fas fa-graduation-cap'></i>", "<i class='fas fa-images'></i>"));
        else $array = self::get_array(array('<i class="fas fa-registered"></i>', '<i class="fas fa-car"></i>', '<i class="fas fa-copyright"></i>', '', "<i class='fas fa-images'></i>"));
        return $array;
    }

    private static function set_values(string $text): array {
        if(strtolower($text) === 'estudiante') $array = self::get_array(array("", "maxlength='200'", "maxlength='10'", "min='15' max='50'", "min='1' max='5'", ""));
        else $array = self::get_array(array("maxlength='10'", "maxlength='20'","maxlength='20'", '', ''));
        return $array;
    }

    /**
     * presentara la cabecera de la pagina
     * @param array $titles
     */
    private function show_header(array $titles): void {
        Header::header($titles, true);
    }

    /**
     * Obtenemos la informacion del estudiante o un array combinado de los encabezados y los atributos
     * @param mixed $model
     * @param int $flag
     * @return array
     */
    private static function get_array_strings(mixed $model, $flag = false): array {
        $information = array();
        $model_information = array();
        $titles = array();
        if($model instanceof Student) {
            $model_information = array($model->get_id(), $model->get_email(), $model->get_name(), $model->get_license(), $model->get_age(), $model->get_course(), $model->get_photo());
            $titles = array('Nombre', 'Email', 'Edad', 'Carnet', 'Curso');
        } else if($model instanceof Car) {
            $model_information = array($model->get_id(), $model->get_license(), $model->get_model(), $model->get_brand(), $model->get_description());
            $titles = array('Placa', 'Modelo', 'Marca', 'Descripcion');
        }

        if($flag) return $model_information;
        for($i = 0; $i < count($titles); ++$i)
            array_push($information, self::show_information_student($titles[$i], $model_information[$i]));
        return $information;
    }

    /**
     * Inserta los titulos con su informacion provenientes del estudiante
     * @param string $title
     * @param string $attribute
     * @return string
     */
    public static function show_information_student(string $title, string $attribute): string {
        return "<p class='text'><strong class='text-strong'>$title: </strong> $attribute</p>";
    }

}