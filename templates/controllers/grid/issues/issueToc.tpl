{**
 * templates/controllers/grid/issues/issueToc.tpl
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Display the issue's table of contents
 *}
{help file="issue-management" section="edit-issue-toc" class="pkp_help_tab"}
<script>
	$(function() {ldelim}
		// Attach the form handler.
		$('#issueTocForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>
<h2>Somente modificações na ordem dos arquivos serão salvas.</h2>

{capture assign=issueTocGridUrl}{url router=$smarty.const.ROUTE_COMPONENT component="grid.toc.TocGridHandler" op="fetchGrid" issueId=$issue->getId() escape=false}{/capture}
{load_url_in_div id="issueTocGridContainer" url=$issueTocGridUrl}

