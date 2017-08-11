<?php
namespace Drush\Preflight;

class ArgsPreprocessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider argTestValues
     */
    public function testArgPreprocessor(
        $argv,
        $alias,
        $selectedSite,
        $configPath,
        $aliasPath,
        $commandPath,
        $isLocal,
        $unprocessedArgs)
    {
        $home = __DIR__ . '/fixtures/home';

        $argProcessor = new ArgsPreprocessor($home);
        $preflightArgs = new PreflightArgs();
        $argProcessor->parse($argv, $preflightArgs);

        $this->assertEquals($unprocessedArgs, implode(',', $preflightArgs->args()));
        $this->assertEquals($alias, $preflightArgs->alias());
        $this->assertEquals($selectedSite, $preflightArgs->selectedSite());
        $this->assertEquals($configPath, $preflightArgs->configPath());
        $this->assertEquals($aliasPath, $preflightArgs->aliasPath());
    }

    public static function argTestValues()
    {
        return [
            [
                [
                    'drush',
                    '@alias',
                    'status',
                    'version',
                ],

                '@alias',
                null,
                null,
                null,
                null,
                null,
                'drush,status,version',
            ],

            [
                [
                    'drush',
                    '#multisite',
                    'status',
                    'version',
                ],

                '#multisite',
                null,
                null,
                null,
                null,
                null,
                'drush,status,version',
            ],

            [
                [
                    'drush',
                    'user@server/path',
                    'status',
                    'version',
                ],

                'user@server/path',
                null,
                null,
                null,
                null,
                null,
                'drush,status,version',
            ],

            [
                [
                    'drush',
                    'rsync',
                    '@from',
                    '@to',
                    '--delete',
                ],

                null,
                null,
                null,
                null,
                null,
                null,
                'drush,rsync,@from,@to,--delete',
            ],

            [
                [
                    'drush',
                    '--root',
                    '/path/to/drupal',
                    'status',
                    '--verbose',
                ],

                null,
                '/path/to/drupal',
                null,
                null,
                null,
                null,
                'drush,status,--verbose',
            ],

            [
                [
                    'drush',
                    '--root=/path/to/drupal',
                    'status',
                    '--verbose',
                ],

                null,
                '/path/to/drupal',
                null,
                null,
                null,
                null,
                'drush,status,--verbose',
            ],

            [
                [
                    'drush',
                    'status',
                    '--verbose',
                    '--config',
                    '/path/to/config',
                ],

                null,
                null,
                '/path/to/config',
                null,
                null,
                null,
                'drush,status,--verbose',
            ],

            [
                [
                    'drush',
                    'status',
                    '--verbose',
                    '--config=/path/to/config',
                ],

                null,
                null,
                '/path/to/config',
                null,
                null,
                null,
                'drush,status,--verbose',
            ],

            [
                [
                    'drush',
                    'status',
                    '--verbose',
                    '--alias-path',
                    '/path/to/aliases',
                ],

                null,
                null,
                null,
                '/path/to/aliases',
                null,
                null,
                'drush,status,--verbose',
            ],

            [
                [
                    'drush',
                    'status',
                    '--verbose',
                    '--alias-path=/path/to/aliases',
                ],

                null,
                null,
                null,
                '/path/to/aliases',
                null,
                null,
                'drush,status,--verbose',
            ],

            [
                [
                    'drush',
                    'status',
                    '--verbose',
                    '--include',
                    '/path/to/commands',
                ],

                null,
                null,
                null,
                null,
                'path/to/commands',
                null,
                'drush,status,--verbose',
            ],

            [
                [
                    'drush',
                    'status',
                    '--verbose',
                    '--include=/path/to/commands',
                ],

                null,
                null,
                null,
                null,
                'path/to/commands',
                null,
                'drush,status,--verbose',
            ],

            [
                [
                    'drush',
                    'status',
                    '--verbose',
                    '--local',
                ],

                null,
                null,
                null,
                null,
                null,
                true,
                'drush,status,--verbose',
            ],

            [
                [
                    'drush',
                    '@alias',
                    'status',
                    '--verbose',
                    '--local',
                    '--alias-path=/path/to/aliases',
                    '--config=/path/to/config',
                    '--root=/path/to/drupal',
                    '--include=/path/to/commands',
                ],

                '@alias',
                '/path/to/drupal',
                '/path/to/config',
                '/path/to/aliases',
                'path/to/commands',
                true,
                'drush,status,--verbose',
            ],
        ];
    }
}
