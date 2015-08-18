<?php

namespace IyvZF2PommProfiler\ServiceManager;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZendDeveloperTools\Collector\CollectorInterface;
use Zend\Mvc\MvcEvent;
use Symfony\Component\Stopwatch\Stopwatch;
use PommProject\ModelManager\Session;

class PommProfiler implements FactoryInterface, CollectorInterface
{
    /**
     * Collector priority
     */
    const PRIORITY = 10;

    public function getName()
    {
        return 'pomm_profiler';
    }  

    public function getPriority()
    {
        return static::PRIORITY;
    }

    public function collect(MvcEvent $mvcEvent)
    {
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $pomm = $serviceLocator->get('pomm');
        $this->stopwatch = new Stopwatch;

        $this->data = [
            'time' => 0,
            'queries' => [],
            'exception' => null,
        ];

        $callable = [$this, 'execute'];


        foreach ($pomm->getSessionBuilders() as $name => $builder) {
            $pomm->addPostConfiguration($name, function($session) use ($callable) {
                $session
                    ->getClientUsingPooler('listener', 'query')
                    ->attachAction($callable)
                    ;
            });
        }

        return $this;
    }

    /**
     * @param string $name
     * @param array $data
     * @param $session
     *
     * @return null
     */
    public function execute($name, $data, Session $session)
    {
        switch ($name) {
            case 'query:post':
                $this->data['time'] += $data['time_ms'];
                $data += array_pop($this->data['queries']);
            case 'query:pre':
                $this->data['queries'][] = $data;
                break;
        }

        $this->watch($name);
    }

    private function watch($name)
    {
        if ($this->stopwatch !== null) {
            switch ($name) {
                case 'query:pre':
                    $this->stopwatch->start('query.pomm', 'pomm');
                    break;
                case 'query:post':
                    $this->stopwatch->stop('query.pomm');
                    break;
            }
        }
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * Return the list of queries sent.
     *
     * @return array
     */
    public function getQueries()
    {
        return $this->data['queries'];
    }

    /**
     * Return the number of queries sent.
     *
     * @return integer
     */
    public function getQuerycount()
    {
        return count($this->data['queries']);
    }

    /**
     * Return queries total time.
     *
     * @return float
     */
    public function getTime()
    {
        return $this->data['time'];
    }

    /**
     * Return sql exception.
     *
     * @return PommProject\Foundation\Exception\SqlException|null
     */
    public function getException()
    {
        return $this->data['exception'];
    }
}
