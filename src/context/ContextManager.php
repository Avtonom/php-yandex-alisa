<?php

namespace yandex\alisa\context;

use yandex\alisa\core\StringHelper;

class ContextManager
{
    /** @var ContextRepository  */
    protected $contextRepository;

    /** @var string */
    protected $userId;

    /** @var string */
    protected $sessionId;

    public function __construct($userId = null, $sessionId = null)
    {
        $this->userId = $userId;
        $this->sessionId = $sessionId;
        $this->contextRepository = new ContextRepository();
    }

    /**
     * @param string|null $userId
     * @param string|null $sessionId
     *
     * @return bool
     */
    public function exists($userId = null, $sessionId = null)
    {
        $userId = ($userId) ? $userId : $this->userId;
        $sessionId = ($sessionId) ? $sessionId : $this->sessionId;
        return $this->contextRepository->exists($userId, $sessionId);
    }

    /**
     * @param string|null $userId
     * @param string|null $sessionId
     *
     * @return Lazer\Classes\Database|bool
     */
    public function getItem($userId = null, $sessionId = null)
    {
        $userId = ($userId) ? $userId : $this->userId;
        $sessionId = ($sessionId) ? $sessionId : $this->sessionId;
        return $this->contextRepository->get($userId, $sessionId);
    }

    /**
     * @param string|null $userId
     * @param string|null $sessionId
     *
     * @return array|bool
     */
    public function get($userId = null, $sessionId = null)
    {
        $row = $this->getItem($userId, $sessionId);
        if($row && !empty($row->context)){
            return $row->context;
        }
        return false;
    }

    /**
     * @param string $command
     * @param string|null $userId
     * @param string|null $sessionId
     * @param bool|true $isAddCommand
     *
     * @return array|bool
     */
    public function getByCommand($command, $userId = null, $sessionId = null, $isAddCommand = true)
    {
        if($context = $this->get($userId, $sessionId)){
            $contextData = json_decode($context, true, JSON_UNESCAPED_UNICODE);
            foreach ($contextData as $contextItem) {
                // default action to all message (save message)
                if(count($contextData) == 1 && empty($contextItem['title'])){
                    if(!empty($contextItem['payload']) && $isAddCommand){
                        $contextItem['payload']['vars']['command'] = $command;
                    }
                    return $contextItem;

                // find personal action to massage (no save message)
                } elseif (StringHelper::equals($contextItem['title'], $command)){ //@todo use $this->caseSensitive == true
                    return $contextItem;
                }
            }
        }
        return false;
    }

    /**
     * @param string|null $userId
     * @param string|null $sessionId
     */
    public function remove($userId = null, $sessionId = null)
    {
        $userId = ($userId) ? $userId : $this->userId;
        $sessionId = ($sessionId) ? $sessionId : $this->sessionId;
        $this->contextRepository->remove($userId, $sessionId);
    }

    /**
     * @param array $context
     * @param string|null $userId
     * @param string|null $sessionId
     */
    public function update($context, $userId = null, $sessionId = null)
    {
        if(empty($context)){
            $this->remove($userId, $sessionId);
        } else {
            $userId = ($userId) ? $userId : $this->userId;
            $sessionId = ($sessionId) ? $sessionId : $this->sessionId;
            $this->contextRepository->update($userId, $sessionId, $context);
        }
    }
}