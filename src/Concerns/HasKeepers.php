<?php

namespace EduLazaro\Larakeep\Concerns;

use ReflectionClass;

use EduLazaro\Larakeep\Attributes\KeptBy;

trait HasKeepers
{
    /** @var array Store the assignments of keepers to classes */
    private static $keeperClasses = [];

    
    /** @var array Keepers */
    private $keepers = [];

    /**
     * Automatically discover keepers from attributes
     */
    public static function bootKeepers(): void
    {
        $reflection = new ReflectionClass(static::class);
        $attributes = $reflection->getAttributes(KeptBy::class);
    
        foreach ($attributes as $attribute) {
            $instance = $attribute->newInstance();

            if (!in_array($instance->keeperClass, self::$keeperClasses)) {
                self::keep($instance->keeperClass);
            }
        }
    }
    
    /**
     * Assign a Keper to a model
     * 
     * @param string $keeperClass
     * @return void
     */
    public static function keep($keeperClass)
    {
        self::$keeperClasses[] = $keeperClass;
    }

    /**
     * Process the GET task for the specified attributes
     * 
     * @param array $fields
     * @return $this
     */
    public function process($fields)
    {
        return $this->processMaintenanceTask('get', $fields);
    }

    /**
     * Process the specified tasks for the specified attributes
     * 
     * @param string $task
     * @param array $fields
     * @return $this
     */
    public function processTask($task, $fields)
    {
        return $this->processMaintenanceTask($task, $fields);
    }

    /**
     * Process the specified tasks for the specified attributes
     * 
     * @param array $fields
     * @param array $params
     * @return $this
     */
    public function processWith($fields, $params)
    {
        return $this->processMaintenanceTask('get', $fields, $params);
    }

    /**
     * Process the specified tasks for the specified attributes
     * 
     * @param string $task
     * @param array $fields
     * @param array $params
     * @return $this
     */
    public function processTaskWith($task, $fields, $params)
    {
        return $this->processMaintenanceTask($task, $fields, $params);
    }

    /**
     * Process the specified tasks for the specified attributes
     * 
     * @param string $task
     * @param array $fields
     * @param array|null $params
     * @return $this
     */
    public function processMaintenanceTask($task, $fields, $params = null)
    {
        if (!is_array($fields)) $fields = [$fields];

        foreach ($fields as $field) {

            $fieldWords = explode('_', $field);
            foreach($fieldWords as $key => $fieldWord) $fieldWords[$key] = ucfirst($fieldWord);
            
            $methodName = $task . implode($fieldWords);
            if ($params !== null) $methodName .= 'With';

            foreach (self::$keeperClasses as $keeperClass) {

                if (!method_exists($keeperClass, $methodName)) continue;

                if (empty($this->keepers[$keeperClass])) $this->keepers[$keeperClass] = new $keeperClass($this);

                if ($params == null) {
                    $fieldValue = $this->keepers[$keeperClass]->$methodName();
                } else {
                    $fieldValue = call_user_func_array([$this->keepers[$keeperClass], $methodName], $params);
                }

                $this->$field = $fieldValue;
            }
        }

        return $this;
    }
}
