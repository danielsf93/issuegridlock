<?php
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
	
}
