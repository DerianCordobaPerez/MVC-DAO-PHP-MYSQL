<?php
interface IActions {
    /**
     * @param mixed $model
     * @return mixed
     */
    public function add(mixed $model): mixed;

    /**
     * @param mixed $model
     * @return mixed
     */
    public function edit(mixed $model): mixed;

    /**
     * @param mixed $model
     * @return mixed
     */
    public function delete(int $id): mixed;

    /**
     * @return mixed
     */
    public function get_content(): mixed;

    /**
     * @param mixed $id
     * @return mixed
     */
    public function get_one(int $id): mixed;

}