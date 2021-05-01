<?php
include_once 'interfaces/IActions.php';
class StudentDao implements IActions {

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
                array($model->get_email(), $model->get_name(), $model->get_license(), $model->get_age(), $model->get_course(), $model->get_photo()),
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
        $query = 'UPDATE Users SET email = :email, name = :name, license = :license, course = :course, age = :age, photo = :photo WHERE id = :id';
        try {
            $update = Connection::connect_database()->prepare($query);
            set_bind_value(
                array($model->get_id(), $model->get_email(), $model->get_name(), $model->get_license(), $model->get_age(), $model->get_course(), $model->get_photo()),
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
        $query = 'SELECT * FROM User';
        $students = array();
        try {
            foreach(Connection::connect_database()->query($query)->fetchAll() as $item) {
                $student = new Student();
                $this->set_student($student, $item);
                array_push($students, $student);
            }
        } catch (Exception $exception) {
            die($exception->getMessage());
        }
        return $students;
    }

    public function get_one(mixed $id): ?Student {
        $query = 'SELECT * FROM Users WHERE id = :id';
        $student = null;
        try {
            $select = Connection::connect_database()->prepare($query);
            set_bind_value($id, 'id', $select);
            $select->execute();
            $student = new Student();
            $this->set_student($student, $select->fetch());
        } catch (Exception $exception) {}
        return $student;
    }

    /**
     * @param Student $student
     * @param mixed $item
     */
    private function set_student(Student $student, mixed $item): void {
        $student->set_id((int)$item['id']);
        $student->set_email($item['email']);
        $student->set_name($item['name']);
        $student->set_license($item['license']);
        $student->set_age($item['age']);
        $student->set_course($item['course']);
        $student->set_photo($item['photo']);
    }
}