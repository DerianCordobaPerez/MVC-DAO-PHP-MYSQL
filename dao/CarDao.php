<?php
require_once 'interfaces/IActions.php';
class CarDao implements IActions {

    public function __construct() {
        include_once 'models/Car.php';
        include_once 'models/Connection.php';
        include_once 'helpers/set_bind_value.php';
    }

    /**
     * @inheritDoc
     */
    public function add($model): bool {
        $correct = false;
        $query = 'INSERT INTO Car VALUES (NULL, :license, :model, :brand, :description)';
        try {
            $insert = Connection::connect_database()->prepare($query);
            set_bind_value(
                array($model->get_license(), $model->get_model(), $model->get_brand(), $model->get_description()),
                array('license', 'model', 'brand', 'description'),
                $insert
            );
            $insert->execute();
            $correct = true;
        } catch(PDOException $exception) {
            // Organizar los errores mediante una tabla en la base de datos, para presentarlos en el index, luego ser borrados
            die($exception->getMessage());
        }
        return $correct;
    }

    /**
     * @inheritDoc
     */
    public function edit($model): bool {
        $correct = false;
        $query = 'UPDATE Car SET license = :license, model = :model, brand = :brand, description = :description WHERE id = :id';
        try {
            $update = Connection::connect_database()->prepare($query);
            set_bind_value(
                array($model->get_id(), $model->get_license(), $model->get_model(), $model->get_brand(), $model->get_description()),
                array('id', 'license', 'model', 'brand', 'description'),
                $update
            );
            $update->execute();
            $correct = true;
        } catch (PDOException $exception) {}
        return $correct;
    }

    /**
     * @inheritDoc
     */
    public function delete($model): bool {
        $correct = false;
        $query = 'DELETE FROM Car WHERE id = :id';
        try {
            $delete = Connection::connect_database()->prepare($query);
            set_bind_value($model->get_id(), 'id', $delete);
            $delete->execute();
            $correct = true;
        } catch (PDOException $exception) {}
        return $correct;
    }

    /**
     * @inheritDoc
     */
    public function get_content(): array {
        $query = 'SELECT * FROM Car';
        $cars = array();
        try {
            foreach(Connection::connect_database()->query($query)->fetchAll() as $item) {
                $car = new Car();
                Car::set_car($car, $item);
                array_push($cars, $car);
            }
        } catch (Exception $exception) {}
        return $cars;
    }

    /**
     * @inheritDoc
     */
    public function get_one($model): Car {
        $query = 'SELECT * FROM Car WHERE id = :id';
        $car = null;
        try {
            $select = Connection::connect_database()->prepare($query);
            set_bind_value($model->get_id(), 'id', $select);
            $car = new Car();
            Car::set_car($car, $select->fetch());
        } catch (Exception $exception) {}
        return $car;
    }
}