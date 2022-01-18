<?php
use JetBrains\PhpStorm\NoReturn;
include_once 'models/Student.php';
include_once 'dao/StudentDao.php';
include_once 'components/Alerts.php';
include_once 'components/Divs.php';
include_once 'views/layouts/Back.php';
include_once 'helpers/upload_image.php';
class StudentController {

    /**
     * StudentController constructor.
     */
    public function __construct() {}
    private static bool $delete = false;

    #[NoReturn] public static function init(): void {
        $action = "";
        if(isset($_REQUEST['action'])) $action = $_REQUEST['action'];
        if($action === 'add') self::insert_student();
        else if($action === 'edit') self::render_edit_form_student();
        else if($action === 'update') self::update_student();
        else if($action === 'delete') self::render_delete_message();
        else if($action === 'put') self::delete_student();

        if(!($action === 'edit') && !($action === 'delete')) echo "<script>window.location.href = 'index.php'</script>";
    }

    /**
     *
     */
    #[NoReturn] private static function insert_student(): void {
        $upload = validation_image();
        if(!$upload)
            echo 'ERROR / Ocurrio un problema al subir la imagen';
        else if(move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/students/'.basename($_FILES['photo']['name']))) {
            $student = new Student(
                (int)$_POST['id'],
                $_POST['email'],
                $_POST['name'],
                $_POST['license'],
                (int)$_POST['age'],
                $_POST['course'],
                htmlspecialchars(basename($_FILES['photo']['name']))
            );

            if(!(new StudentDao())->add($student))
                Alerts::alert('danger', 'No se ha podido registrar el estudiante');
            else
                Alerts::alert('success', 'Se registro correctamente el estudiante');
        }
    }
    private static function render_delete_message(): void {
        (new IndexView())->show_delete_message(self::get_student((int)$_GET['id']));
    }

    private static function delete_student(): void {
        if(!(new StudentDao())->delete($_GET['id'])){
            Alerts::alert('danger', 'No se ha podido eliminar el estudiante');
            self::$delete = true;
        } else
            Alerts::alert('success', 'Se elimino correctamente el estudiante');
    }

    private static function render_edit_form_student(): void {
        Divs::open_div('container bg-white');
        (new IndexView())->show_content(self::get_student((int)$_GET['id']), 'student');
        Divs::close_div();
    }

    #[NoReturn] public static function update_student(): void {
        $upload = validation_image();
        if(!$upload)
            echo 'ERROR / Ocurrio un problema al subir la imagen';
        else if(move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/students/'.basename($_FILES['photo']['name']))) {
            $student = new Student(
                (int)$_POST['id'],
                $_POST['email'],
                $_POST['name'],
                $_POST['license'],
                (int)$_POST['age'],
                $_POST['course'],
                htmlspecialchars(basename($_FILES['photo']['name']))
            );
            if (!(new StudentDao())->edit($student))
                Alerts::alert('danger', 'No se ha podido actualizar el estudiante');
            else
                Alerts::alert('success', 'Se actualizo correctamente el estudiante');
        }
    }

    /**
     * @return array
     */
    public function get_all_students(): array {
        return (new StudentDao())->get_content();
    }

    /**
     * @param int $id
     * @return Student|null
     */
    private static function get_student(int $id): ?Student {
        if(!($student = (new StudentDao())->get_one($id)))
            Alerts::alert('success', 'No se encontro el estudiante');
        else
            Alerts::alert('success', 'Estudiante encontrado: '.$student->get_name());
        return $student;
    }

    /**
     * @return int
     */
    public function get_total_student(): int {
        return (new StudentDao())->get_total() + (self::$delete ? 2 : 1);
    }

}