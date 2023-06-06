<?php
//setfacl -R -d -m g:deployment:rwx www.prepaid-finder.de-s4
//eval "$(ssh-agent -s)"

namespace Deployer;

use Exception;

require 'recipe/symfony.php';
require 'contrib/cloudflare.php';

set('APP_ENV', 'prod');

set('bin/php', static function(): string {
    return '/usr/bin/php8.1';
});

set('bin/composer', static function() {
    return get('bin/php') . ' /usr/local/bin/composer';
});

set('bin_dir', 'bin');
set('var_dir', 'var');

set('env', static function() {
    return [
        'APP_ENV'      => get('APP_ENV'),
        'GITHUB_TOKEN' => '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',
    ];
});

// Project name
set('application', 'httpstatus.codinghood.de');

// Project repository
set('repository', 'git@github.com:TheCodingHood/httpstatus.codinghood.de');

// Shared files/dirs between deploys
add('shared_files', [
]);

// Symfony shared dirs
//set('shared_dirs', ['var/log', 'public/images/posts']);
add('shared_dirs', []);

// Symfony writable dirs
set('writable_dirs', [
    'var',
    'var/cache',
    'var/log'
]);

// Symfony writable dirs
set('cloudflare', [
    'service_key' => '',
    'api_key'     => '18d697822981799cd3d6b87484cec220b545a',
    'email'       => 'mfoitzik@prepaid-finder.de',
    'domain'      => 'codinghood.de',
    'zone_id'     => '',
]);


set('allow_anonymous_stats', false);

set('composer_options', static function() {
    $debug = get('APP_ENV') === 'dev';

    return sprintf(' --verbose --prefer-dist --no-progress --ignore-platform-req php --no-interaction %s --optimize-autoloader',
        (!$debug ? '--no-dev' : '')
    );
});

// Hosts
host(
    'web1.cloud.codinghood.de'
)
    ->set('labels', ['stage' => 'production'])
    ->setRemoteUser('codinghood')
    ->set('roles', 'deployment')
    ->set('deploy_path', '~/applications/{{application}}')
    ->set('chgrp', 'deployment');



// Tasks

task('build', static function() {
//    run('cd {{release_path}} && build');
    run('cd {{release_path}} && ln -s ');
});


task('deploy:yarn-install', static function() {
    $debug = get('APP_ENV') === 'dev';
//    run('yarn install ' . (!$debug ? '--production' : ''));
    run('cd {{release_path}} && yarn install');
})->desc('Install NodeJS Modules');


task('deploy:symfony-encore', static function() {
    $debug = get('APP_ENV') === 'dev';
    run('cd {{release_path}} && yarn run encore ' . ($debug ? 'dev' : 'production'));
})->desc('Compile Webpack');


task('deploy:acl', static function() {
    run('chmod -R g+w {{release_path}}/vendor');
//    run('chmod -R g+w {{release_path}}/node_modules');
})->desc('Fix Group-Rights');


task('deploy:opcache:clear', static function() {
    run('sudo -uwww-data /usr/local/bin/cachetool opcache:reset --fcgi=/run/php/php8.1-fpm-codinghood.sock');
})->desc('Flush OpCache and Redis');


// Install assets from public dir of bundles
task('deploy:assets:install', static function() {

    run('{{bin/console}} assets:install {{console_options}} {{release_path}}/public');

})->desc('Install bundle assets');


task('deploy:link-dotenv', static function() {

    run('rm -f {{release_or_current_path}}/.env');
    run(sprintf('ln -s {{release_or_current_path}}/config/dotenv/.env.%s {{release_or_current_path}}/.env', get('APP_ENV')));
//    run(sprintf('cd {{release_path}} && composer dump-env %s', $env));

})->desc('Link .env File');


task('database:migrate', static function() {
    cd('{{release_path}}');
    run('{{bin/console}} doctrine:migrations:migrate --no-interaction');

})
    ->desc('Migrate database')
    ->select('dbm1.cloud.uptimeguard.io');


task('deploy:js-routing', static function() {
    cd('{{release_path}}');
    run('{{bin/console}} fos:js-routing:dump --format=json --target=public/js/routes.json');
})->desc('JS Routing');


task('deploy:js-translation', static function() {
    cd('{{release_path}}');
    run('{{bin/console}} bazinga:js-translation:dump {{release_path}}/public/js');
})->desc('JS Translation');


after('deploy:symlink', 'deploy:opcache:clear');

after('deploy:symlink', 'deploy:cloudflare');

// Migrate database before symlink new release.

//before('deploy:symlink', 'database:migrate');




after('deploy:failed', 'deploy:unlock');


task('deploy', [
    'deploy:prepare',
    'deploy:link-dotenv',
    'deploy:vendors',
//    'deploy:yarn-install',
//    'deploy:symfony-encore',
    'deploy:cache:clear',
    'deploy:publish',
]);
