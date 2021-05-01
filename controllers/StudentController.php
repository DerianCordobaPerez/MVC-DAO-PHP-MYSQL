<?php
use JetBrains\PhpStorm\NoReturn;
class StudentController {

    /**
     * StudentController constructor.
     */
    public function __construct() {
        include_once 'models/Student.php';
        include_once 'dao/StudentDao.php';
        include_once 'components/Title.php';
        include_once 'helpers/redirect_page.php';
    }

    /**
     * @param Student $student
     */
    #[NoReturn] public function insert_student(Student $student): void {
        $student_dao = new StudentDao();
        if(!$student_dao->add($student))
            Title::title_void('h2', 'No se ha podido registrar el estudiante', 'text-center link-danger');
        else
            Title::title_void('h2', 'Se registro correctamente el estudiante', 'text-center');
        redirect();
    }

    /**
     * @param Student $student
     */
    #[NoReturn] public function delete_student(Student $student): void {
        $student_dao = new StudentDao();
        if(!$student_dao->delete($student))
            Title::title_void('h2', 'No se ha podido eliminar el estudiante', 'text-center link-danger');
        else
            Title::title_void('h2', 'Se elimino correctamente el estudiante', 'text-center');
        redirect();
    }

    /**
     * @param Student $student
     */
    #[NoReturn] public function update_student(Student $student): void {
        $student_dao = new StudentDao();
        if(!$student_dao->edit($student))
            Title::title_void('h2', 'No se ha podido actualizar el estudiante', 'text-center link-danger');
        else
            Title::title_void('h2', 'Se actualizo correctamente el estudiante', 'text-center');
        redirect();
    }

    public function get_all_students(): array {
        $student_dao = new StudentDao();
        return $student_dao->get_content();
    }

    /**
     * @param $id
     * @return Student|null
     */
    public function get_student($id): ?Student {
        $student_dao = new StudentDao();
        if(!($student = $student_dao->get_one($id)))
            Title::title_void('h2', 'No se ha encontrado el estudiante con el id '.$id, 'text-center link-danger');
        else
            Title::title_void('h2', 'Estudiante encontrado:  '.$student->get_name(), 'text-center');
        return $student;
    }

}