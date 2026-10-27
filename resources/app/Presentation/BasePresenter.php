<?php

declare(strict_types=1);

namespace App\Presentation;

use App\Core\Settings\SettingsRequire;
use App\Model\Page\PageMenu;
use Drago\Attr\AttributeDetectionException;
use Drago\Datagrid\DataGrid;
use Drago\Localization\TranslatorAdapter;
use Drago\Permission\Authorization;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;


/** @property-read BaseTemplate $template */
abstract class BasePresenter extends Presenter
{
	use TranslatorAdapter;
	use Authorization;
	use SettingsRequire;

	/** Static pages of the front menu (project-static-page), cached. */
	#[Inject]
	public PageMenu $pageMenu;


	/** @throws AttributeDetectionException */
	protected function beforeRender(): void
	{
		parent::beforeRender();

		// only the front layout shows the menu of static pages
		if (str_starts_with((string) $this->getName(), 'Front:')) {
			$this->template->menuPages = $this->pageMenu->getItems();
		}
	}


	/** Sorting, filtering and paging a grid only reads, so it is checked as "{component}-read". */
	protected function isReadOnlySignal(string $receiver, string $name): bool
	{
		return DataGrid::isReadOnlySignal($this, $receiver, $name);
	}
}
