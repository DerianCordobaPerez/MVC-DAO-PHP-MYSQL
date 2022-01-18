<?php

use JetBrains\PhpStorm\NoReturn;

include_once 'models/Car.php';
include_once 'dao/CarDao.php';
include_once 'components/Title.php';
include_once 'views/layouts/Back.php';
include_once 'helpers/redirect_page.php';
include_once 'helpers/upload_image.php';
class CarController {

    public function __construct() {}
    private static bool $delete = false;

    #[NoReturn] public static function init(): void {
        $action = "";
        if(isset($_REQUEST['action'])) $action = $_REQUEST['action'];

        if($action === 'add') self::insert_car();
        else if($action === 'edit') self::render_edit_form_student();
        else if($action === 'update') self::update_car();
        else if($action === 'delete') self::render_delete_message();
        else if($action === 'put') self::delete_car();

        if(!($action === 'edit') && !($action === 'delete')) echo "<script>window.location.href = 'index.php'</script>";
    }

    /**
     *
     */
    #[NoReturn] private static function insert_car(): void {
        $upload = validation_image();
        if(!$upload)
            echo 'ERROR / Ocurrio un problema al subir la imagen';
        else if(move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/cars/'.basename($_FILES['photo']['name']))) {
            $car = new Car(
                (int)$_POST['id'],
                $_POST['license'],
                $_POST['model'],
                $_POST['brand'],
                $_POST['description'],
                htmlspecialchars(basename($_FILES['photo']['name']))
            );

            if(!(new CarDao())->add($car))
                Title::title_void('h2', 'No se ha podido registrar el auto', 'text-center link-danger');
            else
                Title::title_void('h2', 'Se registro correctamente el auto', 'text-center');
        }
    }

    private static function render_edit_form_student(): void {
        Divs::open_div('container bg-white');
        (new IndexView())->show_content(self::get_car((int)$_GET['id']), 'car');
        Divs::close_div();
    }

    private static function render_delete_message(): void {
        (new IndexView())->show_delete_message(self::get_car((int)$_GET['id']));
    }

    private static function delete_car(): void {
        if(!(new CarDao)->delete($_GET['id']))
            Title::title_void('h2', 'No se ha podido eliminar el auto', 'text-center link-danger');
        else
            Title::title_void('h2', 'Se elimino correctamente el auto', 'text-center');
    }

    private static function update_car(): void {
        $upload = validation_image();
        if(!$upload)
            echo 'ERROR / Ocurrio un problema al subir la imagen';
        else if(move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/students/'.basename($_FILES['photo']['name']))) {
            $car = new Car(
                (int)$_POST['id'],
                $_POST['license'],
                $_POST['model'],
                $_POST['brand'],
                $_POST['description'],
                htmlspecialchars(basename($_FILES['photo']['name']))
            );
            if(!(new CarDao())->edit($car))
                Title::title_void('h2', 'No se ha podido actualizar el auto', 'text-center link-danger');
            else
                Title::title_void('h2', 'Se actualizo correctamente el auto', 'text-center');
        }
    }

    /**
     * @return array
     */
    public function get_all_cars(): array {
        return (new CarDao)->get_content();
    }

    /**
     * @param $id
     * @return Car|null
     */
    private static function get_car($id): ?Car {
        if(!($car = (new CarDao)->get_one($id)))
            Title::title_void('h2', 'No se ha encontrado el auto con el id '.$id, 'text-center link-danger');
        else
            Title::title_void('h2', 'Auto encontrado:  '.$car->get_license(), 'text-center');
        return $car;
    }

    /**
     * @return int
     */
    public function get_total_cars(): int {
        return (new CarDao)->get_total() + + (self::$delete ? 2 : 1);
    }
}