<?php

declare(strict_types=1);

namespace App\Model\User;

use App\Presentation\Backend\Permission\Component\Roles\RolesEntity;
use App\Presentation\Backend\Permission\Component\Users\UsersRolesEntity;
use Dibi\Connection;
use Dibi\Exception;
use Drago\Attr\AttributeDetectionException;
use Drago\Attr\Table;
use Drago\Database\Database;
use Drago\Permission\Role;


#[Table(UserEntity::Table, UserEntity::ColumnId, entity: UserEntity::class)]
class UserRepository
{
	/** @use Database<UserEntity> */
	use Database;

	public function __construct(
		protected readonly Connection $connection,
	) {
	}


	/**
	 * Finds a user by their email.
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function findUserByEmail(string $email): ?UserEntity
	{
		return $this->find(UserEntity::ColumnEmail, $email)
			->record();
	}


	/**
	 * Finds a user by their email or throws an exception if not found.
	 * @throws UserNotFoundException
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function getUserByEmail(string $email): UserEntity
	{
		$user = $this->findUserByEmail($email);
		if ($user === null) {
			throw new UserNotFoundException("User with email '$email' was not found.", 1001);
		}
		return $user;
	}


	/**
	 * Finds a user by their token.
	 * @throws AttributeDetectionException
	 * @throws Exception
	 */
	public function findUserByToken(string $token): ?UserEntity
	{
		return $this->find(UserEntity::ColumnToken, $token)
			->record();
	}


	/** @return list<string> */
	public function getRolesByUser(int $userId): array
	{
		$roles = $this->getConnection()
			->select('r.*')->from(RolesEntity::Table)->as('r')
			->innerJoin(UsersRolesEntity::Table)->as('ur')->on('ur.role_id = r.id')
			->where('ur.%n = ?', UsersRolesEntity::ColumnUserId, $userId)
			->fetchPairs(value: RolesEntity::ColumnName);

		$roles = array_values($roles);
		return $roles ?: [Role::RoleUser];
	}
}
