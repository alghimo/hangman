<?php

namespace Apps\Hangman\Model\Game;

/**
 * UnitTest for the "Repository" class.
 *
 * @author Albert Giménez Morales
 * @package Apps\Hangman
 * @uses \Apps\Hangman\Model\Game\Repository
 * @uses \PHPUnit_Framework_TestCase
 */
class RepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Object we're testing.
     *
     * @var Apps\Hangman\Model\Game\Repository
     */
    private $repository;

    /**
     * Mocked DB.
     *
     * @var Framework\TestExtensions\MockedPDO
     */
    private $mocked_db;

    /**
     * Mocked Game factory.
     *
     * @var Apps\Hangman\Model\Game\Factory
     */
    private $mocked_factory;

    public function setUp()
    {
        $this->mocked_db      = $this->getMockedDb();
        $this->mocked_factory = $this->getMockedFactory();
        $this->repository     = new Repository($this->mocked_db, $this->mocked_factory);
    }

    public function tearDown()
    {
        $this->repository     = null;
        $this->mocked_factory = null;
        $this->mocked_db      = null;
    }

    /**
     * Test db accessor methods.
     */
    public function testDbAccessors()
    {
        $mocked_db = $this->getMockedDb();

        $this->repository->setDb($mocked_db);

        $this->assertSame($mocked_db, $this->repository->getDb(), 'The Db instance was not properly set.');
    }

    /**
     * Test that setDb implements fluent interface.
     */
    public function testSetDbImplementsFluentInterface()
    {
        $mocked_db = $this->getMockedDb();

        $result = $this->repository->setDb($mocked_db);

        $this->assertSame($this->repository, $result, 'setDb does not implement fluent interface.');
    }

    /**
     * Test factory accessor methods.
     */
    public function testFactoryAccessors()
    {
        $mocked_factory = $this->getMockedFactory();

        $this->repository->setFactory($mocked_factory);

        $this->assertSame($mocked_factory, $this->repository->getFactory(), 'The Factory instance was not properly set.');
    }

    /**
     * Test that setFactory implements fluent interface.
     */
    public function testSetFactoryImplementsFluentInterface()
    {
        $mocked_factory = $this->getMockedFactory();

        $result = $this->repository->setFactory($mocked_factory);

        $this->assertSame($this->repository, $result, 'setFactory does not implement fluent interface.');
    }

    /**
     * Test createNew method.
     */
    public function testCreateNew()
    {
        $mocked_game = $this->getMock('MockedGame');
        $this->mocked_factory
            ->expects($this->once())
            ->method('createNewGame')
            ->will($this->returnValue($mocked_game))
        ;

        $this->assertSame($mocked_game, $this->repository->createNew(), 'The new game was not created properly.');
    }

    /**
     * Test findById method.
     */
    public function testFindById()
    {
        $raw_game       = array(
            'id'         => 2,
            'full_word'  => 'foo',
            'word'       => 'f..',
            'max_tries'  => 11,
            'tries_left' => 10,
        );
        $expected_query = <<<QUERY
SELECT
    id,
    full_word,
    word,
    max_tries,
    tries_left
FROM
    games
WHERE
    id = :id_game
QUERY;
        $execute_result   = true;
        $mocked_statement = $this->getMockedStatement($expected_query, $execute_result);
        $mocked_statement
            ->expects($this->once())
            ->method('bindValue')
            ->with(':id_game', $raw_game['id'], \PDO::PARAM_INT)
            ->will($this->returnValue($raw_game))
        ;

        /**
         * @todo Do we really need the "with" call?
         * Doesn't really matter if internally we use FETCH_ASSOC or any other..
         */
        $mocked_statement
            ->expects($this->once())
            ->method('fetch')
            ->with(\PDO::FETCH_ASSOC)
            ->will($this->returnValue($raw_game))
        ;

        $mocked_game = $this->getMockedGame($raw_game);
        $this->mocked_factory
            ->expects($this->once())
            ->method('createEmptyGame')
            ->will($this->returnValue($mocked_game))
        ;

        $this->assertSame($mocked_game, $this->repository->findById($raw_game['id']), 'The expected game was not returned.');
    }

    /**
     * Test findAll method.
     */
    public function testFindAll()
    {
        $raw_games       = array(
            array(
                'id'         => 2,
                'full_word'  => 'foo',
                'word'       => 'f..',
                'max_tries'  => 11,
                'tries_left' => 10,
            ),
            array(
                'id'         => 5,
                'full_word'  => 'bar',
                'word'       => '.a.',
                'max_tries'  => 11,
                'tries_left' => 5,
            ),
        );
        $expected_query = <<<QUERY
SELECT
    id,
    full_word,
    word,
    max_tries,
    tries_left
FROM
    games
QUERY;
        $execute_result = true;

        $mocked_statement = $this->getMockedStatement($expected_query, $execute_result);

        /**
         * @todo Do we really need the "with" call?
         * Doesn't really matter if internally we use FETCH_ASSOC or any other..
         */
        $mocked_statement
            ->expects($this->once())
            ->method('fetchAll')
            ->with(\PDO::FETCH_ASSOC)
            ->will($this->returnValue($raw_games))
        ;

        $mocked_games = array(
            $this->getMockedGame($raw_games[0]),
            $this->getMockedGame($raw_games[1])
        );

        $this->mocked_factory
            ->expects($this->exactly(2))
            ->method('createEmptyGame')
            ->will($this->onConsecutiveCalls($mocked_games[0], $mocked_games[1]))
        ;

        $this->assertSame($mocked_games, $this->repository->findAll(), 'The expected games were not returned.');
    }

    /**
     * Test the game update.
     *
     * The "update" method itself is private, we update games via the "save" method.
     */
    public function testUpdate()
    {
        $expected_query = <<<QUERY
UPDATE
    games
SET
    full_word = :full_word,
    word = :word,
    max_tries = :max_tries,
    tries_left = :tries_left
WHERE
    id = :id_game
QUERY;
        $execute_result = true;

        $mocked_statement = $this->getMockedStatement($expected_query, $execute_result);
        $mocked_game = $this->getMock('\Apps\Hangman\Entity\Game');
        $mocked_game
            ->expects($this->exactly(2))
            ->method('getId')
            ->will($this->returnValue(1))
        ;
        $mocked_game
            ->expects($this->once())
            ->method('getFullWord')
            ->will($this->returnValue("foo"))
        ;
        $mocked_game
            ->expects($this->once())
            ->method('getWord')
            ->will($this->returnValue("f.."))
        ;
        $mocked_game
            ->expects($this->once())
            ->method('getMaxTries')
            ->will($this->returnValue(11))
        ;
        $mocked_game
            ->expects($this->once())
            ->method('getTriesLeft')
            ->will($this->returnValue(7))
        ;
        $mocked_statement
            ->expects($this->exactly(5))
            ->method('bindValue')
        ;

        $this->assertSame($mocked_game, $this->repository->save($mocked_game), 'Mocked game was updated.');
    }

    /**
     * Test the game insert.
     *
     * The "insert" method itself is private, we insert games via the "save" method.
     */
    public function testInsert()
    {
        $new_id         = 1;
        $expected_query = <<<QUERY
INSERT INTO
    games
SET
    full_word = :full_word,
    word = :word,
    max_tries = :max_tries,
    tries_left = :tries_left
QUERY;
        $execute_result = true;

        $mocked_statement = $this->getMockedStatement($expected_query, $execute_result);
        $mocked_statement
            ->expects($this->exactly(4))
            ->method('bindValue')
        ;
        $this->mocked_db
            ->expects($this->once())
            ->method('lastInsertId')
            ->will($this->returnValue($new_id))
        ;

        $mocked_game = $this->getMock('\Apps\Hangman\Entity\Game');
        $mocked_game
            ->expects($this->once())
            ->method('setId')
            ->with($new_id)
            ->will($this->returnValue($mocked_game))
        ;
        $mocked_game
            ->expects($this->once())
            ->method('getFullWord')
            ->will($this->returnValue("foo"))
        ;
        $mocked_game
            ->expects($this->once())
            ->method('getWord')
            ->will($this->returnValue("f.."))
        ;
        $mocked_game
            ->expects($this->once())
            ->method('getMaxTries')
            ->will($this->returnValue(11))
        ;
        $mocked_game
            ->expects($this->once())
            ->method('getTriesLeft')
            ->will($this->returnValue(7))
        ;

        $this->assertSame($mocked_game, $this->repository->save($mocked_game), 'Mocked game was updated.');
    }

    /**
     * Get a mocked db instance.
     *
     * @return \PDO
     */
    private function getMockedDb()
    {
        return $this->getMock('Framework\TestExtensions\MockPDO');
    }

    /**
     * Get a mocked factory instance.
     *
     * @return \Apps\Hangman\Model\Game\Factory
     */
    private function getMockedFactory()
    {
        return $this->getMockBuilder('\Apps\Hangman\Model\Game\Factory')
                    ->disableOriginalConstructor()
                    ->getMock()
        ;
    }

    /**
     * Get a mocked PDOStatement
     *
     * @return \PDOStatement
     */
    private function getMockedStatement($expected_query, $execution_result)
    {
        $mocked_statement = $this->getMock('\PDOStatement');
        $this->mocked_db
            ->expects($this->once())
            ->method('prepare')
            ->with($expected_query)
            ->will($this->returnValue($mocked_statement))
        ;
        $mocked_statement
            ->expects($this->once())
            ->method('execute')
            ->will($this->returnValue(true))
        ;

        return $mocked_statement;
    }

     /**
     * Get a mocked Game entity
     *
     * @return \Apps\Hangman\Entity\Game
     */
    private function getMockedGame(array $raw_game)
    {
        $mocked_game = $this->getMock('\Apps\Hangman\Entity\Game');
        $mocked_game
            ->expects($this->once())
            ->method('setId')
            ->with($raw_game['id'])
            ->will($this->returnValue($mocked_game))
        ;
        $mocked_game
            ->expects($this->once())
            ->method('setFullWord')
            ->with($raw_game['full_word'])
            ->will($this->returnValue($mocked_game))
        ;
        $mocked_game
            ->expects($this->once())
            ->method('setWord')
            ->with($raw_game['word'])
            ->will($this->returnValue($mocked_game))
        ;
        $mocked_game
            ->expects($this->once())
            ->method('setMaxTries')
            ->with($raw_game['max_tries'])
            ->will($this->returnValue($mocked_game))
        ;
        $mocked_game
            ->expects($this->once())
            ->method('setTriesLeft')
            ->with($raw_game['tries_left'])
            ->will($this->returnValue($mocked_game))
        ;

        return $mocked_game;
    }
}