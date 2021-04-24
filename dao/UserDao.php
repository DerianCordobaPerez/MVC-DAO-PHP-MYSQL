<?php
class UserDao implements IActions {

    public function __construct() {
        include_once 'models/Student.php';
        include_once 'models/Connection.php';
        include_once 'helpers/set_bind_value.php';
    }

    /**
     * @param $model
     * @return bool
     * @inheritDoc
     */
    public function add($model): bool {
        $correct = false;
        $query = 'INSERT INTO Users VALUES (NULL, :email, :name, :license, :age, :photo)';
        try {
            $insert = Connection::connect_database()->prepare($query);
            set_bind_value(
                array($model->email, $model->name, $model->license, $model->age, $model->course, $model->photo),
                array('email', 'name', 'license', 'age', 'course', 'photo'),
                $insert
            );
            $insert->execute();
            $correct = true;
        } catch(PDOException $exception) {
            // Organizar los errores mediante una tabla en la base de datos, para presentarlos en el index, luego ser borrados
        }
        return $correct;
    }

    /**
     * @inheritDoc
     */
    public function edit($model): bool {
        $correct = false;
        $query = 'UPDATE Users SET email = :email, name = :name, license = :license, age = :age, photo = :photo WHERE id = :id';
        try {
            $update = Connection::connect_database()->prepare($query);
            set_bind_value(
                array($model->id, $model->email, $model->name, $model->license, $model->age, $model->course, $model->photo),
                array('id', 'email', 'name', 'license', 'age', 'course', 'photo'),
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
        $query = 'DELETE FROM Users WHERE id = :id';
        try {
            $delete = Connection::connect_database()->prepare($query);
            set_bind_value($model->id, 'id', $delete);
            $delete->execute();
            $correct = true;
        } catch (PDOException $exception) {}
        return $correct;
    }

    /**
     * @inheritDoc
     */
    public function get_content(): array {
        $query = 'SELECT * FROM Users';
        $students = array();
        try {
            $select = Connection::connect_database()->query($query);

            foreach($select->fetchAll() as $item) {
                $student = new Student();
                Student::set_student($student, $item);
                array_push($students, $student);
            }
        } catch (Exception $exception) {}
        return $students;
    }

    public function get_one($model): ?Student {
        $query = 'SELECT * FROM Users WHERE id = :id';
        $student = null;
        try {
            $select = Connection::connect_database()->prepare($query);
            set_bind_value($model->id, 'id', $select);
            
            $student = new Student();
            Student::set_student($student, $select->fetch());
        } catch (Exception $exception) {}
        return $student;
    }
}