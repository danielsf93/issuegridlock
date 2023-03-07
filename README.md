# issuegridlock

![image](https://user-images.githubusercontent.com/114300053/222162102-8e69a57e-8dc2-47e7-b895-dd700140c526.png)



Verificar: controllers/grid/issues/IssueGridHandler.inc.php:36 e 407




conferirir

<h1>AQUIIII</h1>
Order 
Enable ordering mode



<a href="#" id="component-grid-toc-tocgrid-category-1-row-1-moveItem-button-63ff4872d9272" title="Mover item" class="pkp_controllers_linkAction pkp_linkaction_moveItem pkp_linkaction_icon_order_items" style=""></a>

<a href="#" id="component-grid-toc-tocgrid-category-1-row-22-moveItem-button-63ff4872d3df3" title="Mover item" class="pkp_controllers_linkAction pkp_linkaction_moveItem pkp_linkaction_icon_order_items" style=""></a>

<a href="#" id="component-grid-toc-tocgrid-category-5-row-5-moveItem-button-63ff4872d9eec" title="Mover item" class="pkp_controllers_linkAction pkp_linkaction_moveItem pkp_linkaction_icon_order_items" style=""></a>
*************************************************************

	function __construct() {
		parent::__construct();
		$this->addRoleAssignment(
			array(ROLE_ID_MANAGER),
			array(
				'fetchGrid', 'fetchRow',
				'addIssue', 'editIssue', 'editIssueData', 'updateIssue',
				'uploadFile', 'deleteCoverImage',
				'issueToc',
				'issueGalleys',
				'deleteIssue', 'publishIssue', 'unpublishIssue', 'setCurrentIssue',
				'identifiers', 'updateIdentifiers', 'clearPubId', 'clearIssueObjectsPubIds',
				'access', 'updateAccess',
			)
		);
	}


************************************

function issueToc($args, $request) {
		$templateMgr = TemplateManager::getManager($request);
		$issue = $this->getAuthorizedContextObject(ASSOC_TYPE_ISSUE);
		$templateMgr->assign('issue', $issue);
		return new JSONMessage(true, $templateMgr->fetch('controllers/grid/issues/issueToc.tpl'));
	}



verificar https://github.com/pkp/ojs/blob/ojs-stable-3_1_1/templates/frontend/objects/issue_toc.tpl#L96-L115


também controllers/grid/toc/TocGridHandler.inc.php:96, 137, 144.

ver https://ajnyga.gitbooks.io/ojs3-hooks/content/chapter1.html
