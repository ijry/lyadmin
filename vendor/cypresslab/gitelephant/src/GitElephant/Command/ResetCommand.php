<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 3/2/16
 * Time: 11:29 AM
 */

namespace GitElephant\Command;


use GitElephant\Objects\Commit;
use GitElephant\Objects\TreeishInterface;
use \GitElephant\Repository;

class ResetCommand extends BaseCommand
{
    const GIT_RESET_COMMAND = 'reset';

    const OPTION_HARD  = '--hard';
    const OPTION_MERGE = '--merge';
    const OPTION_SOFT  = '--soft';

    /**
     * constructor
     *
     * @param \GitElephant\Repository $repo The repository object this command
     *                                      will interact with
     */
    public function __construct(Repository $repo = null)
    {
        parent::__construct($repo);
    }

    /**
     * @param TreeishInterface|Commit|string $arg
     * @param array $options
     *
     * @throws \RuntimeException
     * @return string
     */
    public function reset($arg = null, array $options = array())
    {
        $this->clearAll();
        $this->addCommandName(self::GIT_RESET_COMMAND);
        // if there are options add them.
        if (! is_null($options)) {
            foreach ($options as $option) {
                $this->addCommandArgument($option);
            }
        }
        if($arg!=null){
            $this->addCommandSubject2($arg);
        }

        return $this->getCommand();
    }

    /**
     * @param Repository $repository
     * @return ResetCommand
     */
    public static function getInstance(Repository $repository=null)
    {
        return new self($repository);
    }



}
