<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

function deployScriptPath(): string
{
    return base_path('scripts/deploy.sh');
}

function deployScript(): string
{
    return file_get_contents(deployScriptPath());
}

/**
 * Artisan commands the script runs, e.g. ["optimize:clear", "migrate", …].
 *
 * @return array<int, string>
 */
function deployArtisanCommands(): array
{
    preg_match_all('/^\s*(?:"\$PHP_BIN"|php) artisan ([a-z][a-z0-9:-]*)/m', deployScript(), $matches);

    return $matches[1];
}

it('only runs artisan commands that exist', function () {
    $available = array_keys(Artisan::all());

    expect(deployArtisanCommands())->not->toBeEmpty();

    foreach (deployArtisanCommands() as $command) {
        expect($available)->toContain($command);
    }
});

it('stops on the first failing step', function () {
    expect(deployScript())->toContain('set -euo pipefail');
});

it('runs the essential steps in a safe order', function () {
    $script = deployScript();

    $position = fn (string $needle): int => strpos($script, $needle) !== false
        ? strpos($script, $needle)
        : throw new RuntimeException("deploy.sh is missing: {$needle}");

    expect($position('install --no-dev'))->toBeLessThan($position('artisan migrate --force'))
        ->and($position('npm run build'))->toBeLessThan($position('artisan optimize'."\n"))
        ->and($position('artisan optimize:clear'))->toBeLessThan($position('artisan optimize'."\n"))
        ->and($position('artisan migrate --force'))->toBeLessThan($position('artisan optimize'."\n"));
});

it('installs the node packages before building the frontend', function () {
    expect(strpos(deployScript(), 'npm ci'))->toBeLessThan(strpos(deployScript(), 'npm run build'));
});

it('lives exactly one level below the project root, because it changes to its parent directory', function () {
    expect(deployScript())->toContain('cd "$(dirname "$(readlink -f "$0")")/.."')
        ->and(realpath(dirname(deployScriptPath()).'/..'))->toBe(realpath(base_path()))
        ->and(file_exists(dirname(deployScriptPath()).'/../artisan'))->toBeTrue();
});

it('takes the site out of maintenance mode even when a step fails', function () {
    expect(deployScript())->toContain('artisan down')
        ->toContain('trap ')
        ->toContain('artisan up');
});

it('is valid bash and executable', function () {
    exec('bash -n '.escapeshellarg(deployScriptPath()).' 2>&1', $output, $exitCode);

    expect($exitCode)->toBe(0, implode("\n", $output))
        ->and(is_executable(deployScriptPath()))->toBeTrue();
});
