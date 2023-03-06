<?php

import('lib.pkp.classes.plugins.GenericPlugin');

class issuegridlock extends GenericPlugin {
    public function register($category, $path, $mainContextId = NULL) {
        $success = parent::register($category, $path);
          if ($success && $this->getEnabled()) {
    HookRegistry::register('TemplateResource::getFilename', array($this, '_overridePluginTemplates'));
    HookRegistry::register('OrderItemsFeature::_saveRowsInCategoriesSequence', array(&$this, '_saveRowsInCategoriesSequence'));
}

        return $success;
    }

  /**
   * Provide a name for this plugin
   *
   * The name will appear in the plugins list where editors can
   * enable and disable plugins.
   */
	public function getDisplayName() {
		return __('issuegridlock');
	}

	/**
   * Provide a description for this plugin
   *
   * The description will appear in the plugins list where editors can
   * enable and disable plugins.
   */
	public function getDescription() {
		return __('Impedir confusão em reordenação de artigos em submissões');
	}
	
	/**
	 * Get the name of the settings file to be installed on new context
	 * creation.
	 * @return string
	 */
	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}






public function saveSequence($args) {
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

 
	public function _saveRowsInCategoriesSequence($request, &$grid, $gridCategoryElements, $data) {
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


	
}
