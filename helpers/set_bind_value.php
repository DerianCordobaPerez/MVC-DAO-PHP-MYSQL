<?php

    /**
     * @param $content
     * @param $attr
     * @param $action
     */
    function set_bind_value($content, $attr, $action): void {
        if(is_array($content) && is_array($attr)) {
            for($i = 0; $i < count($content); ++$i)
                $action->bindValue($attr[$i], $content[$i]);
        } else
            $action->bindValue($attr, $content);
    }
