<?php declare(strict_types=1);

namespace App\Presentation\Install;

use App\Presentation\Backend\Permission\Component\Users\UsersRolesEntity;
use Dibi\Connection;
use Dibi\Exception;


/**
 * Gives the administrator created by the web installer of project-install the `admin` role of project-permission
 * (role 1, seeded by its migrations).
 */
final readonly class AdminRole
{
	public function __construct(
		private Connection $connection,
	) {
	}


	/** @throws Exception */
	public function __invoke(int $userId): void
	{
		$this->connection->insert(UsersRolesEntity::Table, [
			UsersRolesEntity::ColumnUserId => $userId,
			UsersRolesEntity::ColumnRoleId => 1,
		])->execute();
	}
}
