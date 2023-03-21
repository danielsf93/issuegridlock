<?php

/*-----------------------------------------------------------------------------------------------------------------------
 * inicio Tentativa 01 com HookRegistry
 * */
import('lib.pkp.classes.plugins.GenericPlugin');

class issuegridlock extends GenericPlugin {
    public function register($category, $path, $mainContextId = NULL) {
        $success = parent::register($category, $path);
            if ($success && $this->getEnabled()) {
    HookRegistry::register('TemplateResource::getFilename', array($this, '_overridePluginTemplates'));
	HookRegistry::register('TemplateResource::getFilename', array($this, '_overridePluginTemplatesdois'));

}

        return $success;
    }



public function _overridePluginTemplatesdois($hookName, $args) {
    $templatePath = $args[0];
    if ($templatePath === 'lib/pkp/templates/controllers/grid/gridRow.tpl') {
        $args[0] = 'plugins/generic/issuegridlock/templates/controllers/grid/gridRow.tpl';
    }
    return true;
}
  
	public function getDisplayName() {
		return __('issuegridlock');
	}

	public function getDescription() {
		return __('Impedir confusão em reordenação de artigos em submissões');
	}
	
	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

/*------------------------------------------------------------------------------------------------------------------------------------------------
 * Final da tentativa 01. Para testar a tentativa 02 é necessário trocar os sinais de comentário
 * *

*--------------------------------------------------------------------------------------------------------------------------------------------------
 *inicio Tentativa 02 com implementação do código em um só arquivo
 * *
 
 
 
import('lib.pkp.classes.plugins.GenericPlugin');
import('lib.pkp.classes.controllers.grid.feature.OrderItemsFeature');

define('ORDER_CATEGORY_GRID_CATEGORIES_ONLY', 0x01);
define('ORDER_CATEGORY_GRID_CATEGORIES_ROWS_ONLY', 0x02);
define('ORDER_CATEGORY_GRID_CATEGORIES_AND_ROWS', 0x03);


class issuegridlock extends GenericPlugin {
    public function register($category, $path, $mainContextId = NULL) {
        $success = parent::register($category, $path);
            if ($success && $this->getEnabled()) {
    HookRegistry::register('TemplateResource::getFilename', array($this, '_overridePluginTemplates'));
    HookRegistry::register('OrderCategoryGridItemsFeature::view', array($this, 'saveSequence'));
    HookRegistry::register('OrderCategoryGridItemsFeature::view', array($this, '_saveRowsInCategoriesSequence'));

}

        return $success;
    }

	public function getDisplayName() {
		return __('issuegridlock');
	}

	public function getDescription() {
		return __('Impedir confusão em reordenação de artigos em submissões');
	}
	
	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

function __construct($typeOption = ORDER_CATEGORY_GRID_CATEGORIES_AND_ROWS, $overrideRowTemplate = true, $grid = null) {
		parent::__construct($overrideRowTemplate);

		if ($grid) {
			$grid->_constants['ORDER_CATEGORY_GRID_CATEGORIES_ONLY'] = ORDER_CATEGORY_GRID_CATEGORIES_ONLY;
			$grid->_constants['ORDER_CATEGORY_GRID_CATEGORIES_ROWS_ONLY'] = ORDER_CATEGORY_GRID_CATEGORIES_ROWS_ONLY;
			$grid->_constants['ORDER_CATEGORY_GRID_CATEGORIES_AND_ROWS'] = ORDER_CATEGORY_GRID_CATEGORIES_AND_ROWS;
		}

		// talvez ess linha não seja necessária $this->addOptions(array('type' => $typeOption));
	}

	function getType() {
		$options = $this->getOptions();
		return $options['type'];
	}


	function getJSClass() {
		return '$.pkp.classes.features.OrderCategoryGridItemsFeature';
	}


	function getInitializedRowInstance($args) {
		if ($this->getType() != ORDER_CATEGORY_GRID_CATEGORIES_ONLY) {
			parent::getInitializedRowInstance($args);
		}
	}


	function getInitializedCategoryRowInstance($args) {
		if ($this->getType() != ORDER_CATEGORY_GRID_CATEGORIES_ROWS_ONLY) {
			$row =& $args['row'];
			$this->addRowOrderAction($row);
		}
	}

	function saveSequence($args) {
		$request =& $args['request'];
		$grid =& $args['grid'];

		$data = json_decode($request->getUserVar('data'));
		$gridCategoryElements = $grid->getGridDataElements($request);

		if ($this->getType() != ORDER_CATEGORY_GRID_CATEGORIES_ROWS_ONLY) {
			$categoriesData = array();
			foreach($data as $categoryData) {
				$categoriesData[] = $categoryData->categoryId;
			}

			
		}

		// Save rows sequence, if this grid has also orderable rows inside each category.
		$this->_saveRowsInCategoriesSequence($request, $grid, $gridCategoryElements, $data);
	}

	function _saveRowsInCategoriesSequence($request, &$grid, $gridCategoryElements, $data) {
		if ($this->getType() != ORDER_CATEGORY_GRID_CATEGORIES_ONLY) {
			foreach($gridCategoryElements as $categoryId => $element) {
				$gridRowElements = $grid->getGridCategoryDataElements($request, $element);
				if (!$gridRowElements) continue;

				// Get the correct rows sequence data.
				$rowsData = null;
				foreach ($data as $categoryData) {
					if ($categoryData->categoryId == $categoryId) {
						$rowsData = $categoryData->rowsId;
						break;
					}
				}

				unset($rowsData[0]); // remove the first element, it is always the parent category ID
				foreach ($gridRowElements as $rowId => $element) {
					$newSequence = array_search($rowId, $rowsData);
					$currentSequence = $grid->getDataElementInCategorySequence($categoryId, $element);
					if ($newSequence != $currentSequence) {
						$grid->setDataElementInCategorySequence($categoryId, $element, $newSequence);
					}
				}
			}
		}
	}



/*------------------------------------------------------------------------------------------------------------------------------------------------
 * Final da tentativa 02.
 * */




	
}
