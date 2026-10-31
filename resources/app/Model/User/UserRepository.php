<?php declare(strict_types=1);

namespace App\Model\User;

use App\Presentation\Backend\Permission\Component\Roles\RolesEntity;
use App\Presentation\Backend\Permission\Component\Users\UsersRolesEntity;
use Dibi\Connection;
use Dibi\Exception;
use Drago\Database\Table;
use Drago\Permission\Role;


class UserRepository
{
	/** @var Table<UserEntity> */
	public Table $table;


	public function __construct(Connection $db)
	{
		$this->table = new Table($db, UserEntity::Table, UserEntity::ColumnId, UserEntity::class);
	}


	/**
	 * Finds a user by their email.
	 * @throws Exception
	 */
	public function findUserByEmail(string $email): ?UserEntity
	{
		return $this->table->find(UserEntity::ColumnEmail, $email)
			->record();
	}


	/**
	 * Finds a user by their email or throws an exception if not found.
	 * @throws UserNotFoundException
	 * @throws Exception
	 */
	public function getUserByEmail(string $email): UserEntity
	{
		return $this->findUserByEmail($email) ?? throw new UserNotFoundException("User with email '$email' was not found.", 1001);
	}


	/**
	 * Finds a user by their token.
	 * @throws Exception
	 */
	public function findUserByToken(string $token): ?UserEntity
	{
		return $this->table->find(UserEntity::ColumnToken, $token)
			->record();
	}


	/** @return list<string> */
	public function getRolesByUser(int $userId): array
	{
		$roles = $this->table->getConnection()
			->select('r.*')
			->from(RolesEntity::Table)
			->as('r')
			->innerJoin(UsersRolesEntity::Table)
			->as('ur')
			->on('ur.role_id = r.id')
			->where('ur.%n = ?', UsersRolesEntity::ColumnUserId, $userId)
			->fetchPairs(value: RolesEntity::ColumnName);

		$roles = array_values($roles);
		return $roles ?: [Role::RoleUser];
	}
}
