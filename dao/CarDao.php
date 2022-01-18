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
        $query = 'INSERT INTO Car (id, license, model, brand, description, photo) VALUES (:id, :license, :model, :brand, :description, :photo)';
        try {
            $insert = Connection::connect_database()->prepare($query);
            set_bind_value(
                array($model->get_id(), $model->get_license(), $model->get_model(), $model->get_brand(), $model->get_description(), $model->get_photo()),
                array('id', 'license', 'model', 'brand', 'description', 'photo'),
                $insert
            );
            $insert->execute();
            $correct = true;
        } catch(PDOException $exception) {
            // Organizar los errores mediante una tabla en la base de datos, para presentarlos en el index, luego ser borrados
            Title::title_void('h2', $exception->getMessage(), 'text-center link-danger');
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
    public function delete($id): bool {
        $correct = false;
        $query = 'DELETE FROM Car WHERE id = :id';
        try {
            $delete = Connection::connect_database()->prepare($query);
            set_bind_value($id, 'id', $delete);
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
                $this->set_car($car, $item);
                array_push($cars, $car);
            }
        } catch (Exception $exception) {}
        return $cars;
    }

    /**
     * @inheritDoc
     */
    public function get_one($id): ?Car {
        $query = 'SELECT * FROM Car WHERE id = :id';
        $car = null;
        try {
            $select = Connection::connect_database()->prepare($query);
            set_bind_value($id, 'id', $select);
            $select->execute();
            $car = new Car();
            $this->set_car($car, $select->fetch());
        } catch (Exception $exception) {}
        return $car;
    }

    public function get_total(): int {
        $query = 'SELECT COUNT(*) FROM Car';
        try {
            return Connection::connect_database()->query($query)->fetchColumn();
        } catch (Exception $exception) {
            return 0;
        }
    }

    /**
     * @param Car $car
     * @param mixed $item
     */
    private function set_car(Car $car, mixed $item): void {
        $car->set_id((int)$item['id']);
        $car->set_license($item['license']);
        $car->set_model($item['model']);
        $car->set_brand($item['brand']);
        $car->set_description($item['description']);
        $car->set_photo($item['photo']);
    }

}