<?php

namespace Docker\API\Model;

class ContainerTop
{
    /**
     * @var string[]|null
     */
    protected $titles;
    /**
     * @var string[][]|null[]|null
     */
    protected $processes;

    /**
     * @return string[]|null
     */
    public function getTitles()
    {
        return $this->titles;
    }

    /**
     * @param string[]|null $titles
     *
     * @return self
     */
    public function setTitles($titles = null)
    {
        $this->titles = $titles;

        return $this;
    }

    /**
     * @return string[][]|null[]|null
     */
    public function getProcesses()
    {
        return $this->processes;
    }

    /**
     * @param string[][]|null[]|null $processes
     *
     * @return self
     */
    public function setProcesses($processes = null)
    {
        $this->processes = $processes;

        return $this;
    }
}
