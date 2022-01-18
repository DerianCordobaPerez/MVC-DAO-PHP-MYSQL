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
        include_once 'controllers/CarController.php';
        include_once 'InformationView.php';
    }

    /**
     * Inserta el contenido completo para un estudiante, en el apartado de listado de estudiante
     * @param mixed $model
     * @return void
     */
    public static function show_full_content(mixed $model): void {
        // Contenedor de las partes correspondientes para el contenido del html
        $content = array(
            self::show_photo_student($model->get_photo()),
            self::get_array_strings($model),
            array(
                Button::button("btn btn-warning d-block mx-auto", "editButton", "<a class='link nav-link' href='edit_student.php?license=$model->get_license'>Editar</a>"),
                Button::button("btn btn-danger d-block mx-auto my-4", "deleteButton", "<a class='link nav-link' href='delete_student.php?license=$model->get_license'>Borrar</a>")
            ),
        );
        // Maquetacion del html mediante funciones
        Divs::open_div('row');
        foreach($content as $item) {
            Divs::open_div('col-md-4');
            if(is_array($item)) {
                foreach($item as $string)
                    echo $string;
            } else  {
                Divs::open_div('d-grid gap-2');
                echo $item;
                Divs::close_div();
            }
            Divs::close_div();
        }
        Divs::close_div();
        echo "<hr />";
    }

    public function show_index(string $title): void {
        $buttons = array('student', 'car');
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
        (new InformationView())->show_content();
        Accordion::close_accordion();
        Divs::close_div();
        Html::close_html();
    }

    /**
     * @param Student|Car|null $model
     * @param String $text_button
     */
    public function show_content(Student|Car|null $model, String $text_button) {
        Title::title_void('h2', '<br>Formulario de '.($model ? 'edicion' : 'agregado').' para '.($text_button !== 'student' ? ' auto' : ' estudiante'));
        Title::title_void('h4', ($model ? 'Edite' : 'Rellene').' los campos necesarios<br>');
        $action = $model ? 'update' : 'add';
        $route = "?module=$text_button&action=$action";

        $information = array();
        if($model) $information = self::get_array_strings($model, true);
        $labels = self::set_labels($text_button);
        $types = self::set_types($text_button);
        $icons = self::set_icons($text_button);
        $values = self::set_values($text_button);

        Form::open_form($route, 'POST');
        Input::input_hidden('module', $text_button === 'student' ? 'student' : 'car');
        Input::input_hidden('action', $action);
        for($i = 0; $i < count($labels); ++$i) {
            Label::label_void(strtolower($labels[$i]), $labels[$i]);
            if($labels[$i] === 'id') {
                if(!$model)
                    Input::input('form-control', 'ID', 'number', 'id', 'id', $text_button === 'student' ? (new StudentController())->get_total_student() : (new CarController())->get_total_cars(), true, true);
                else
                    Input::input('form-control', 'ID', 'number', 'id', 'id', $information[$i], true, true);
                continue;
            }
            if($types[$i] === 'file') Input::input_hidden('MAX_FILE_SIZE', '1024000');
            if($types[$i] === 'textarea') {
                TextArea::text_area(strtolower($labels[$i]), 'textarea form-control', $model ? $information[$i] : '');
                continue;
            }
            Input::input('form-control', $icons[$i], $types[$i], strtolower($labels[$i]), $labels[$i], $model ? $information[$i] : '',
                true, false, $model ? $labels[$i] : "", $values[$i]);
        }
        Divs::open_div('d-grid gap-2');
        Input::input('btn btn-primary my-4 button-width', '', 'submit', 'send', '', 'Enviar', false);
        Divs::close_div();
        Form::close_form();
    }

    public function show_delete_message($model): void {
        $titles = self::set_labels($model instanceof Student ? 'student' : 'car');
        $array_information = self::get_array_strings($model, true);
        self::show_delete_student($model, $titles, $array_information);
    }

    public static function show_delete_student(mixed $model, array $titles, array $array_information): void {
        $string_model = $model instanceof Student ? 'student' : 'car';
        Html::open_html('Delete '.($model instanceof Student ? $model->get_name() : $model->get_license()));
        Form::open_form("?module=$string_model&action=put&id=".$model->get_id(), 'post');
        Input::input_hidden('id', $model->get_id());
        Divs::open_div('container bg-light my-4 p-4');
        Divs::open_div_role('alert alert-warning', 'alert');
        Title::title_with_strong_void('h3', 'Estas seguro que desea eliminar a '.($model instanceof Student ? $model->get_name() : $model->get_license()).'?', 'text-center');
        Divs::close_div();

        Divs::open_div('row my-4');
        Divs::open_div('col-md-2');
        Divs::close_div();

        Divs::open_div('col-md-4');
        echo Title::title_with_strong('h3', 'Informacion', '');
        for($i = 0; $i < count($titles); ++$i)
            echo self::show_information_student($titles[$i], $array_information[$i]);
        Divs::close_div();

        Divs::open_div('col-md-6');
        echo Title::title_with_strong('h3', 'Imagen', 'text-center');
        Divs::open_div('d-grid gap-2');

        Image::image('uploads/'.($model instanceof Student ? 'students/' : 'cars/').$array_information[count($array_information) - 1], 'image-shadow image mx-auto d-block');
        Divs::close_div();
        Divs::close_div();
        Divs::close_div();

        Divs::open_div('row my-4');
        Divs::open_div('col'); Divs::close_div();
        Divs::open_div('col-md-6');
        echo Input::input_string('btn btn-danger button-width', 'submit', 'delete', 'Si');
        Divs::close_div();
        Divs::open_div('col'); Divs::close_div();
        Divs::close_div();
        A::a('text-center nav-link', 'index.php', 'No');
        Divs::close_div();
        Form::close_form();
        Html::close_html();
    }

    private static function get_array(array $labels): array {
        $array = array();
        foreach($labels as $label)
            array_push($array, $label);
        return $array;
    }

    private static function set_labels(string $text): array {
        if(strtolower($text) === 'student') $array = self::get_array(array('id', 'Email', 'Name', 'License', 'Age', 'Course', 'Photo'));
        else $array = self::get_array(array('id', 'License', 'Model', 'Brand', 'Description', 'Photo'));
        return $array;
    }

    private static function set_types(string $text): array {
        if(strtolower($text) === 'student') $array = self::get_array(array('number', 'email', 'text', 'text', 'number', 'number', 'file'));
        else $array = self::get_array(array('number', 'text', 'text', 'text', 'textarea', 'file'));
        return $array;
    }

    private static function set_icons(string $text): array {
        if(strtolower($text) === 'student') $array = self::get_array(array('ID', '@', "<i class='far fa-user'></i>", "<i class='far fa-id-card'></i>", "18", "<i class='fas fa-graduation-cap'></i>", "<i class='fas fa-images'></i>"));
        else $array = self::get_array(array('ID', '<i class="fas fa-registered"></i>', '<i class="fas fa-car"></i>', '<i class="fas fa-copyright"></i>', '', "<i class='fas fa-images'></i>"));
        return $array;
    }

    private static function set_values(string $text): array {
        if(strtolower($text) === 'student') $array = self::get_array(array('', "", "maxlength='200'", "maxlength='10'", "min='15' max='50'", "min='1' max='5'", ""));
        else $array = self::get_array(array('', "maxlength='10'", "maxlength='20'","maxlength='20'", '', ''));
        return $array;
    }

    /**
     * presentara la cabecera de la pagina
     * @param mixed $titles
     */
    private function show_header(mixed $titles): void {
        Header::header($titles, true);
    }

    /**
     * Obtenemos la informacion del estudiante o un array combinado de los encabezados y los atributos
     * @param mixed $model
     * @param int $flag
     * @return array
     */
    public static function get_array_strings(mixed $model, $flag = false): array {
        $information = array();
        $model_information = array();
        $titles = array();
        if($model instanceof Student) {
            $model_information = array($model->get_id(), $model->get_email(), $model->get_name(), $model->get_license(), $model->get_age(), $model->get_course(), $model->get_photo());
            $titles = array('id', 'Email', 'Nombre', 'Carnet', 'Edad', 'Curso');
        } else if($model instanceof Car) {
            $model_information = array($model->get_id(), $model->get_license(), $model->get_model(), $model->get_brand(), $model->get_description(), $model->get_photo());
            $titles = array('id', 'Placa', 'Modelo', 'Marca', 'Descripcion');
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