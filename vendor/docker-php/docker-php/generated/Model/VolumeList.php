<?php

namespace Docker\API\Model;

class VolumeList
{
    /**
     * @var Volume[]|null
     */
    protected $volumes;

    /**
     * @return Volume[]|null
     */
    public function getVolumes()
    {
        return $this->volumes;
    }

    /**
     * @param Volume[]|null $volumes
     *
     * @return self
     */
    public function setVolumes($volumes = null)
    {
        $this->volumes = $volumes;

        return $this;
    }
}
