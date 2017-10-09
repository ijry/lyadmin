<?php
/**
 * User: matteo
 * Date: 28/05/13
 * Time: 21.42
 * Just for fun...
 */

namespace GitElephant\Status;

use \GitElephant\TestCase;

/**
 * Class StatusTest
 *
 * @package GitElephant\Status
 */
class StatusTest extends TestCase
{
    /**
     * setUp
     */
    public function setUp()
    {
        $this->getRepository()->init();
        $this->addFile('initial');
        $this->getRepository()->commit('initial commit', true);
    }

    /**
     * testStatusIndexWithoutUntracked
     */
    public function testStatusIndexWithoutUntracked()
    {
        $this->addFile('test');
        $s = $this->repository->getIndexStatus();
        $this->assertCount(0, $s->all());
        $this->assertCount(0, $s->untracked());
    }

    /**
     * status test
     */
    public function testUntracked()
    {
        $this->addFile('test');
        $s = $this->repository->getStatus();
        $this->assertCount(1, $s->untracked());
        $this->assertInstanceOf('\Traversable', $s->untracked());
        $this->assertEquals('untracked', $s->untracked()->first()->get()->getDescription());
        $this->assertFalse($s->untracked()->first()->get()->isRenamed());
        $this->assertInterfaces($s->untracked());
        foreach ($s->untracked() as $file) {
            $this->assertInstanceOf('GitElephant\Status\StatusFile', $file);
        }
    }

    /**
     * modified
     */
    public function testModified()
    {
        $this->addFile('test', null, 'test');
        $this->repository->stage();
        $this->updateFile('test', 'test content');
        $s = $this->repository->getStatus();
        $this->assertCount(1, $s->modified());
        $this->assertFalse($s->modified()->first()->get()->isRenamed());
        $this->assertInterfaces($s->modified());
        foreach ($s->modified() as $file) {
            $this->assertInstanceOf('GitElephant\Status\StatusFile', $file);
        }
    }

    /**
     * added
     */
    public function testAdded()
    {
        $this->addFile('test');
        $this->repository->stage();
        $s = $this->repository->getStatus();
        $this->assertCount(1, $s->added());
        $this->assertFalse($s->added()->first()->get()->isRenamed());
        $this->assertInterfaces($s->added());
        foreach ($s->added() as $file) {
            $this->assertInstanceOf('GitElephant\Status\StatusFile', $file);
        }
    }

    /**
     * deleted
     */
    public function testDeleted()
    {
        $this->addFile('test');
        $this->repository->commit('test message', true);
        $this->removeFile('test');
        $s = $this->repository->getStatus();
        $this->assertCount(1, $s->deleted());
        $this->assertFalse($s->deleted()->first()->get()->isRenamed());
        $this->assertInterfaces($s->deleted());
        foreach ($s->deleted() as $file) {
            $this->assertInstanceOf('GitElephant\Status\StatusFile', $file);
        }
    }

    /**
     * renamed
     */
    public function testRenamed()
    {
        $this->addFile('test', null, 'test content');
        $this->repository->commit('test message', true);
        $this->renameFile('test', 'test2');
        $s = $this->repository->getStatus();
        $this->assertCount(1, $s->renamed());
        $this->assertTrue($s->renamed()->first()->get()->isRenamed());
        $this->assertInterfaces($s->renamed());
        foreach ($s->renamed() as $file) {
            $this->assertInstanceOf('GitElephant\Status\StatusFile', $file);
        }
    }

    /**
     * testWorkingTreeStatus
     */
    public function testWorkingTreeStatus()
    {
        /*$this->markTestSkipped(
            'Caller::execute throws a RuntimeException here because. Repository::unstage
invokes "git reset HEAD -- test", which returns 1 (not 0) on git < 1.8, even though it executes successfully.
On new git version this is not happening anymore.'
        );*/

        $this->addFile('test', null, 'test content');
        $wt = $this->repository->getWorkingTreeStatus();
        $this->assertCount(1, $wt->untracked());

        $this->repository->stage('test');
        $wt = $this->repository->getWorkingTreeStatus();
        $index = $this->repository->getIndexStatus();
        $this->assertCount(0, $wt->untracked());
        $this->assertCount(1, $index->added());

        $this->repository->unstage('test');
        $wt = $this->repository->getWorkingTreeStatus();
        $index = $this->repository->getIndexStatus();
        $this->assertCount(1, $wt->untracked());
        $this->assertCount(0, $index->added());

        $this->repository->commit('test-commit', true);
        $wt = $this->repository->getWorkingTreeStatus();
        $index = $this->repository->getIndexStatus();
        $this->assertCount(0, $wt->all());
        $this->assertCount(0, $index->all());

        $this->addFile('test', null, 'new content');
        $wt = $this->repository->getWorkingTreeStatus();
        $index = $this->repository->getIndexStatus();
        $this->assertCount(1, $wt->modified());
        $this->assertCount(0, $index->modified());

        $this->repository->stage('test');
        $wt = $this->repository->getWorkingTreeStatus();
        $index = $this->repository->getIndexStatus();
        $this->assertCount(0, $wt->modified());
        $this->assertCount(1, $index->modified());

        $this->removeFile('test');
        $wt = $this->repository->getWorkingTreeStatus();
        $index = $this->repository->getIndexStatus();
        $this->assertCount(1, $wt->deleted());
        $this->assertCount(1, $index->modified());

        // Caller::execute throws a RuntimeException here because
        // Repository::unstage invokes 'git reset HEAD -- test',
        // which returns 1 (not 0), even though it executes successfully
        //
        // @see http://stackoverflow.com/questions/9154674/why-git-reset-file-returns-1
        $this->repository->unstage('test');
        $wt = $this->repository->getWorkingTreeStatus();
        $index = $this->repository->getIndexStatus();
        $this->assertCount(1, $wt->deleted());
        $this->assertCount(0, $index->all());
    }

    /**
     * @param mixed $subject
     */
    private function assertInterfaces($subject)
    {
        $this->assertInstanceOf('\Countable', $subject);
        $this->assertInstanceOf('\Traversable', $subject);
        $this->assertInstanceOf('\IteratorAggregate', $subject);
    }
}
