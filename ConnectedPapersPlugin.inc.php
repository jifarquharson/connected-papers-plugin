<?php

/**
 * @file ConnectedPapersPlugin.inc.php
 *
 * Copyright (c) 2022 Gonzalo Faramiñan - Universidad Nacional del Sur
 * Distributed under the GNU GPL v3 or later. For full terms see the file LICENSE
 *
 * @class ConnectedPapersPlugin
 * @brief Plugin class for the Connected Papers plugin.
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class ConnectedPapersPlugin extends GenericPlugin {

	public function register($category, $path, $mainContextId = NULL) {
		$success = parent::register($category, $path);
		if (!Config::getVar('general', 'installed') || defined('RUNNING_UPGRADE')) return $success;
		if ($success && $this->getEnabled()) {
			HookRegistry::register('Templates::Article::Details', array($this, 'insertBadge'));
		}
		return $success;
  	}

	/**
	 * Provide a name for this plugin
	 * @return string
	 */
	function getDisplayName() {
		return __('plugins.generic.connectedPapers.displayName');
	}

	/**
	 * Provide a description for this plugin
	 * @return String
	 */
	function getDescription() {
		return __('plugins.generic.connectedPapers.description');
	}

	/**
	 * Insert Connected Papers badge
	 * 
	 * @param string $hookName Name of hook calling function
	 * @param array $params 
	 * @return boolean
	 */
	function insertBadge($hookName, $params) {

		$templateMgr = TemplateManager::getManager();

		// get DOI
		$submission = $templateMgr->getTemplateVars('article');
		$doi = $submission->getStoredPubId('doi');
		$templateMgr->assign('cpDoi', $doi);

		// get CP badge template
		$badge = $this->getTemplateResource('connectedPapersBadge.tpl');

		if ($doi) {
			$output =& $params[2]; 
			$output .= $templateMgr->fetch($badge);
		}

		return false;
	}

}
?>
