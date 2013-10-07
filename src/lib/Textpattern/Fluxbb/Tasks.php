<?php

namespace Textpattern\Fluxbb;

/**
 * Runs maintenance tasks when requested.
 *
 * @example
 * use Textpattern\Fluxbb\Tasks;
 * new Tasks('aZdbY!daIoB');
 */

class Tasks
{
    /**
     * Listened HTTP GET parameter.
     *
     * @var string
     */

    protected $parameter = 'textpattern_fluxbb_tasks';

    /**
     * Constructor.
     *
     * @param string $key The key
     */

    public function __construct($key)
    {
        if (isset($_GET[$this->parameter]) && $_GET[$this->parameter] === $key)
        {
            foreach (get_class_methods($this) as $method)
            {
                if (strpos($method, 'task') === 0)
                {
                    $this->$method();
                }
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('success' => true));
            die();
        }
    }

    /**
     * Removes users older than a month and have never logged in.
     */

    public function taskCleanUserDatabase()
    {
        $time = strtotime('-1 month');
        Db::pdo()->exec("DELETE FROM users WHERE group_id = 0 and registered < {$time}");
    }
}