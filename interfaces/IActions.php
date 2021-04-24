<?php
interface IActions {
    /**
     * @param $model
     * @return mixed
     */
    public function add($model): mixed;

    /**
     * @param $model
     * @return mixed
     */
    public function edit($model): mixed;

    /**
     * @param $model
     * @return mixed
     */
    public function delete($model): mixed;

    /**
     * @return mixed
     */
    public function get_content(): mixed;

    /**
     * @param $model
     * @return mixed
     */
    public function get_one($model): mixed;

}