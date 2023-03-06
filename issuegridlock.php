<?php

import('lib.pkp.classes.plugins.GenericPlugin');

class issuegridlock extends GenericPlugin {
    public function register($category, $path, $mainContextId = NULL) {
        $success = parent::register($category, $path);
            if ($success && $this->getEnabled()) {
               HookRegistry::register('TemplateResource::getFilename', array($this, '_overridePluginTemplates'));
               HookRegistry::register('TemplateResource::getFilename', array($this, '_overrideOrderCategoryGridItemsFeature'));

            }
        return $success;
    }


public function _overrideOrderCategoryGridItemsFeature($hookName, $args) {
    $templatePath = $args[0];
    if ($templatePath === 'classes/controllers/grid/feature/OrderCategoryGridItemsFeature.inc.php') {
        $args[0] = 'plugins/generic/issuegridlock/OrderCategoryGridItemsFeature.inc.php';
    }
    return false;
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

	
}
