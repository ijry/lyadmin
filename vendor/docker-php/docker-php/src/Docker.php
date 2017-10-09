<?php

namespace Docker;

use Docker\API\Normalizer\NormalizerFactory;
use Docker\Manager\ContainerManager;
use Docker\Manager\ExecManager;
use Docker\Manager\ImageManager;
use Docker\Manager\MiscManager;
use Docker\Manager\NetworkManager;
use Docker\Manager\VolumeManager;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Joli\Jane\Encoder\RawEncoder;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * Docker\Docker
 */
class Docker
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var ContainerManager
     */
    private $containerManager;

    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     * @var MiscManager
     */
    private $miscManager;

    /**
     * @var VolumeManager
     */
    private $volumeManager;

    /**
     * @var NetworkManager
     */
    private $networkManager;

    /**
     * @var ExecManager
     */
    private $execManager;

    /**
     * @param HttpClient|null     $httpClient     Http client to use with Docker
     * @param Serializer|null     $serializer     Deserialize docker response into php objects
     * @param MessageFactory|null $messageFactory How to create docker request (in PSR7)
     */
    public function __construct(HttpClient $httpClient = null, Serializer $serializer = null, MessageFactory $messageFactory = null)
    {
        $this->httpClient = $httpClient ?: DockerClient::createFromEnv();

        if ($serializer === null) {
            $serializer = new Serializer(
                NormalizerFactory::create(),
                [
                    new JsonEncoder(
                        new JsonEncode(),
                        new JsonDecode()
                    ),
                    new RawEncoder()
                ]
            );
        }

        if ($messageFactory === null) {
            $messageFactory = new MessageFactory\GuzzleMessageFactory();
        }

        $this->serializer = $serializer;
        $this->messageFactory = $messageFactory;
    }

    /**
     * @return ContainerManager
     */
    public function getContainerManager()
    {
        if (null === $this->containerManager) {
            $this->containerManager = new ContainerManager($this->httpClient, $this->messageFactory, $this->serializer);
        }

        return $this->containerManager;
    }

    /**
     * @return ImageManager
     */
    public function getImageManager()
    {
        if (null === $this->imageManager) {
            $this->imageManager = new ImageManager($this->httpClient, $this->messageFactory, $this->serializer);
        }

        return $this->imageManager;
    }

    /**
     * @return MiscManager
     */
    public function getMiscManager()
    {
        if (null === $this->miscManager) {
            $this->miscManager = new MiscManager($this->httpClient, $this->messageFactory, $this->serializer);
        }

        return $this->miscManager;
    }

    /**
     * @return ExecManager
     */
    public function getExecManager()
    {
        if (null === $this->execManager) {
            $this->execManager = new ExecManager($this->httpClient, $this->messageFactory, $this->serializer);
        }

        return $this->execManager;
    }

    /**
     * @return VolumeManager
     */
    public function getVolumeManager()
    {
        if (null === $this->volumeManager) {
            $this->volumeManager = new VolumeManager($this->httpClient, $this->messageFactory, $this->serializer);
        }

        return $this->volumeManager;
    }

    /**
     * @return NetworkManager
     */
    public function getNetworkManager()
    {
        if (null === $this->networkManager) {
            $this->networkManager = new NetworkManager($this->httpClient, $this->messageFactory, $this->serializer);
        }

        return $this->networkManager;
    }
}
