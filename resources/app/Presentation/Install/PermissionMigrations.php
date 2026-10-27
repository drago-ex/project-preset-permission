<?php

declare(strict_types=1);

namespace App\Presentation\Install;

use Composer\InstalledVersions;
use Drago\Migration\MigrationFiles;


/**
 * The privileges of the installed packages (their migrations-permission/, imported by hand otherwise) for the web
 * installer of project-install, which runs them after the package migrations and before the project's own ones,
 * so roles can be given them in the administration right after the installation.
 */
final class PermissionMigrations
{
	/** Directory of the privileges in a package. */
	private const string PermissionDir = 'migrations-permission';


	/**
	 * The migrations-permission/ files in the migration-priority order of their packages. Paths are resolved like
	 * MigrationFiles::forProject() (not vendor/composer/../), so the runner records them under their package; the
	 * project itself and drago-ex/migration are left out.
	 * @return list<array{file: string, package: string, priority: ?int}>
	 */
	public function __invoke(string $projectRoot): array
	{
		$packages = [];
		$root = realpath($projectRoot);
		$migration = InstalledVersions::getInstallPath('drago-ex/migration');
		$migration = $migration !== null ? realpath($migration) : false;
		foreach (InstalledVersions::getInstalledPackages() as $name) {
			$path = InstalledVersions::getInstallPath($name);
			$path = $path !== null ? realpath($path) : false;
			if ($path === false || $path === $root || $path === $migration) {
				continue;
			}

			if (is_dir($path . '/' . self::PermissionDir)) {
				$packages[] = [
					'name' => $name,
					'priority' => MigrationFiles::packagePriority($path),
					'files' => MigrationFiles::list($path . '/' . self::PermissionDir),
				];
			}
		}

		usort($packages, fn(array $a, array $b): int => [$a['priority'], $a['name']] <=> [$b['priority'], $b['name']]);

		$migrations = [];
		foreach ($packages as $package) {
			foreach ($package['files'] as $file) {
				$migrations[] = ['file' => $file, 'package' => $package['name'], 'priority' => $package['priority']];
			}
		}

		return $migrations;
	}
}
